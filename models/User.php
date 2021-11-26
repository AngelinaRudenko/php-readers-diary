<?php

class User
{
    /*
    username - required, unique (min 6, max 50), no spaces, no special characters
    email - required, unique, valid (max 50)
    password - required (min 8, max 50)
    profile picture
    */

    public static function register($username, $email, $password, $path)
    {
        $success = FALSE;
        $connection = Db::createConnection();
        $sql = "INSERT INTO `user` (`username`, `email`, `password`, `profilePicture`) 
                VALUES (?, ?, ?, ?)";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $password, $path);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }

        return $success;
    }

    public static function validateUser($username, $email, $password, $confirmPassword)
    {
        $errors = [];

        // username
        if (empty($username)) {
            array_push($errors, "Username is required");
        } else if ($username != trim($username)) {
            array_push($errors, "Username should not have spaces or tabs");
        } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)) {
            array_push($errors, "Username should not have special characters");
        } else if (strlen($username) < 6) {
            array_push($errors, "Username length must be at least 6 characters");
        } else if (strlen($username) > 50) {
            array_push($errors, "Username length must be no more than 50 characters");
        } else if (self::parameterIsUnique("username", $username) == false) {
            array_push($errors, "Username must be unique");
        }

        // email
        if (empty($email)) {
            array_push($errors, "Email is required");
        } else if (strlen($email) > 254) {
            array_push($errors, "Email is too long");
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            array_push($errors, "Email is not valid");
        } else if (self::parameterIsUnique("email", $email) == false) {
            array_push($errors, "Email must be unique");
        }

        // password
        if (empty($password)) {
            array_push($errors, "Password is required");
        } else if ($password != trim($password)) {
            array_push($password, "Password should not have spaces or tabs");
        } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            array_push($errors, "Password should not have special characters");
        } else if (strlen($password) < 8) {
            array_push($errors, "Password length must be at least 8 characters");
        } else if (strlen($password) > 50) {
            array_push($errors, "Password length must be no more than 50 characters");
        } else if ($password != $confirmPassword) {
            array_push($errors, "Passwords don't match");
        }

        return $errors;
    }

    private static function parameterIsUnique($parameterName, $parameter)
    {
        $success = FALSE;
        $connection = Db::createConnection();
        $sql = "SELECT count(`userId`) FROM `user` WHERE `$parameterName` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "s", $parameter);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $userCount);
                mysqli_stmt_fetch($stmt);
                $success = $userCount == 0;
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }

        return $success;
    }

    public static function authorize($email, $password)
    {
        $success = FALSE;
        $connection = Db::createConnection();
        $sql = "SELECT `userId` FROM `user` WHERE `email` = ? AND `password` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $userId);
                mysqli_stmt_fetch($stmt);
                if (isset($userId)) {
                    unset($_SESSION['userAuthorized']);
                    $_SESSION['userAuthorized'] = array(
                        "isAuthorized" => TRUE,
                        "userId" => $userId);
                    $success = TRUE;
                }
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }

        return $success;
    }
}