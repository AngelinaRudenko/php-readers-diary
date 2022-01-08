<?php

class UserController
{
    /**
     * Register new user after successful validation or throws errors on UI.
     * Also refreshes the page.
     * @return bool - just for redirect
     */
    public function actionRegister()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            require_once(ROOT . '/views/user/register.php');
            return true;
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        $errors = User::validateUser($username, $email, $password, $confirmPassword);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/user/register.php');
            return true;
        }

        $password = password_hash($password, PASSWORD_DEFAULT); // hash the password
        User::register($username, $email, $password);

        require_once(ROOT . '/views/user/register.php');
        return true;
    }

    /**
     * Authorizes if user entered correct email and password. Otherwise, shows errors on UI.
     * @return bool - just for redirect
     */
    public function actionLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            require_once(ROOT . '/views/user/login.php');
            return true;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (isset($email) && isset($password)) {
            User::authorize($email, $password);
        } else {
            $_SESSION['errors'] = ['Please enter both email and password'];
        }

        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    /**
     * User authorization of user and redirects to home page.
     */
    public function actionLogout()
    {
        unset($_SESSION['userAuthorized']);
        header("Location: /");
    }
}