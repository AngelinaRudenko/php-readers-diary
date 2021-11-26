<?php

class UserController
{
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

        unset($_SESSION['errors']);
        unset($_SESSION['success']);

        $errors = User::validateUser($username, $email, $password, $confirmPassword);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/user/register.php');
            return true;
        }

        $path = null;

        if ($profilePicture['error'] != 4) {
            try {
                $path = 'profilePictures/' . time() . '_' . $profilePicture['name'];
                move_uploaded_file($profilePicture['tmp_name'], 'uploads/' . $path);
            } catch (Exception $e) {
                $_SESSION['errors'] = ["Errors occurred while uploading the image"];
                require_once(ROOT . '/views/user/register.php');
                return true;
            } finally {
                unset($_FILES['profilePicture']);
            }
        }

        $password = md5($password); // hash the password
        $registerSuccess = User::register($username, $email, $password, $path);
        if ($registerSuccess == TRUE) {
            $userAuthorized = User::authorize($email, $password);
            if ($userAuthorized == TRUE) {
                $_SESSION['success'] = 'Successful registration';
            }
        } else {
            $_SESSION['errors'] = ['Errors occurred while registration. Try to contact the administrator.'];
            if (isset($path)) {
                unlink($path);
            }
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

        unset($_SESSION['errors']);
        unset($_SESSION['success']);

        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $userAuthorized = User::authorize($email, $password);

        if ($userAuthorized == TRUE) {
            $_SESSION['success'] = 'Successful authorization';
        } else {
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