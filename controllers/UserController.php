<?php

class UserController
{
    /*
     * Checks before registration:
     * confirm password = password
     * username - required , unique (min 6, max 50)
     * email - required, unique
     * password - required (min 8, max 50)
     * successful picture uploading
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
        $profilePicture = $_FILES['profilePicture'];

        $errors = User::validateUser($username, $email, $password, $confirmPassword);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/user/register.php');
            return true;
        }

        $path = null;

        if ($profilePicture['error'] != 4) {
            $path = 'uploads/' . time() . '_' . $profilePicture['name'];
            if (!move_uploaded_file($profilePicture['tmp_name'], '../' . $path)) {
                $_SESSION['errors'] = ["Errors occurred while uploading the image"];
                unset($_FILES['profilePicture']);
                require_once(ROOT . '/views/user/register.php');
                return true;
            }
            unset($_FILES['profilePicture']);
        }

        $password = md5($password); // hash the password
        $registerSuccess = User::register($username, $email, $password, $path);
        $userAuthorized = User::authorize($email, $password);
        if ($registerSuccess == TRUE and $userAuthorized == TRUE) {
            $_SESSION['success'] = 'Successful registration';
        }
        else {
            $_SESSION['errors'] = ['Errors occurred while registration. Try to contact the administrator.'];
        }

        require_once(ROOT . '/views/user/register.php');
        return true;
    }

    public function actionLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            require_once(ROOT . '/views/user/login.php');
            return true;
        }

        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $userAuthorized = User::authorize($email, $password);

        if ($userAuthorized == TRUE) {
            $_SESSION['success'] = 'Successful authorization';
        }
        else {
            $_SESSION['errors'] = ['Email or password is not correct'];
        }

        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    public function actionLogout()
    {
        unset($_SESSION['userAuthorized']);
        header("Location: /");
    }
}