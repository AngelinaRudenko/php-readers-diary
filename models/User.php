<?php

class User
{
    /*
    username - required, unique (min 6, max 50)
    email - required, unique
    password - required (min 8, max 50)
    profile picture
    */

    public static function register($username, $email, $password, $path)
    {
        $connection = DB::createConnection();
        $sql = "INSERT INTO `user` (`id`, `username`, `email`, `password`, `profilePicture`) 
                VALUES (NULL, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $password, $path);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            return TRUE;
        }
        mysqli_close($connection);
        return FALSE;
    }

    public static function validateUser($username, $email, $password, $confirmPassword) {
        $errors = [];

        // username
        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        else if (strlen($username) < 6) {
            array_push($errors, "Username length must be at least 6 characters");
        }
        else if (strlen($username) > 50) {
            array_push($errors, "Username length must be no more than 50 characters");
        }
        else if (self::parameterIsUnique("username", $username) == false) {
            array_push($errors, "Username must be unique");
        }

        // email
        if (empty($email)) {
            array_push($errors, "Email is required");
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            array_push($errors, "Email is not valid");
        }
        else if (self::parameterIsUnique("email", $email) == false) {
            array_push($errors, "Email must be unique");
        }

        // password
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
        else if (strlen($password) < 8) {
            array_push($errors, "Password length must be at least 8 characters");
        }
        else if (strlen($password) > 50) {
            array_push($errors, "Username length must be no more than 50 characters");
        }
        else if ($password != $confirmPassword) {
            array_push($errors, "Passwords don't match");
        }

        return $errors;
    }

    private static function parameterIsUnique($parameterName, $parameter)
    {
        $connection = DB::createConnection();
        $sql = "SELECT count(`id`) FROM `user` WHERE `$parameterName` = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $parameter);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userCount);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            return $userCount == 0;
        }
        mysqli_close($connection);
        return false;
    }

    public static function authorize($email, $password)
    {
        $connection = DB::createConnection();
        $sql = "SELECT TRUE FROM `user` 
                WHERE `email`=? AND `password`=?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $exists);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            if ($exists == TRUE) {
                $_SESSION['userAuthorized'] = TRUE;
            }
            return $exists == TRUE;
        }
        mysqli_close($connection);
        return FALSE;
    }
}