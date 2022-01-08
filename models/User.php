<?php

class User
{
    /*
    username - required, unique (min 6, max 50), no spaces, no special characters
    email - required, unique, valid (max 50)
    password - required (min 8, max 50)
    */

    /**
     * Executes sql query to insert new user to database.
     * Adds success and error messages according to execution results.
     * @param $username - username
     * @param $email - email
     * @param $password - password
     * @return bool
     */
    public static function register($username, $email, $password)
    {
        $connection = Db::createConnection();
        $sql = "INSERT INTO `user` (`username`, `email`, `password`) 
                VALUES (?, ?, ?)";
        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $password);
            mysqli_stmt_execute($stmt);

            unset($_SESSION['userAuthorized']);
            $_SESSION['userAuthorized'] = array(
                "isAuthorized" => TRUE,
                "userId" => mysqli_insert_id($connection));
            $_SESSION['success'] = 'Successful registration';

            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            $_SESSION['errors'] = ['Errors occurred while registration. Try to contact the administrator.'];
            if (isset($path)) {
                unlink($path);
            }
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }

        return TRUE;
    }

    /**
     * Validates all fields of user entity and returns errors.
     * @param $username - username
     * @param $email - email
     * @param $password - password (not salted)
     * @param $confirmPassword - password that was repeated for confirmation
     * @return array - array of errors
     */
    public static function validateUser($username, $email, $password, $confirmPassword)
    {
        $errors = [];

        // username
        $errors = array_merge($errors, self::validateUsername($username));
        if (self::parameterIsUnique("username", $username) == false) {
            array_push($errors, "Username must be unique");
        }

        // email
        $errors = array_merge($errors, self::validateEmail($email));
        if (self::parameterIsUnique("email", $email) == false) {
            array_push($errors, "Email must be unique");
        }

        // password
        $errors = array_merge($errors, self::validatePassword($password));
        if ($password != $confirmPassword) {
            array_push($errors, "Passwords don't match");
        }

        return $errors;
    }

    /**
     * Validates username and returns errors.
     * @param $username - username
     * @return array - array of errors
     */
    public static function validateUsername($username) {
        $errors = [];
        if (empty($username)) {
            array_push($errors, "Username is required");
        } else if ($username != trim($username) || strpos($username, ' ') !== false) {
            array_push($errors, "Username should not have spaces or tabs");
        } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)) {
            array_push($errors, "Username should not have special characters");
        } else if (strlen($username) < 6) {
            array_push($errors, "Username length must be at least 6 characters");
        } else if (strlen($username) > 50) {
            array_push($errors, "Username length must be no more than 50 characters");
        }
        return $errors;
    }

    /**
     * Validates email and returns errors.
     * @param $email - email
     * @return array - array of errors
     */
    public static function validateEmail($email) {
        $errors = [];
        if (empty($email)) {
            array_push($errors, "Email is required");
        } else if (strlen($email) > 254) {
            array_push($errors, "Email is too long");
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            array_push($errors, "Email is not valid");
        }
        return $errors;
    }

    /**
     * Validates password and returns errors.
     * @param $password - password
     * @return array - array of errors
     */
    public static function validatePassword($password) {
        $errors = [];
        if (empty($password)) {
            array_push($errors, "Password is required");
        } else if ($password != trim($password) || strpos($password, ' ') !== false) {
            array_push($errors, "Password should not have spaces or tabs");
        } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            array_push($errors, "Password should not have special characters");
        } else if (strlen($password) < 8) {
            array_push($errors, "Password length must be at least 8 characters");
        } else if (strlen($password) > 50) {
            array_push($errors, "Password length must be no more than 50 characters");
        }
        return $errors;
    }

    /**
     * Checks if value of user parameter is unique.
     * @param $parameterName - name of any parameter of user entity
     * @param $parameter - value that must be unique
     * @return bool - is parameter unique or not
     */
    public static function parameterIsUnique($parameterName, $parameter)
    {
        $success = FALSE;
        $connection = Db::createConnection();
        $sql = "SELECT COUNT(`userId`) FROM `user` WHERE `$parameterName` = ?";

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

    /**
     * Doing authorization, if finds user it in database.
     * @param $email
     * @param $password
     * @return bool
     */
    public static function authorize($email, $password)
    {
        $connection = Db::createConnection();
        $sql = "SELECT `userId`, `password` FROM `user` WHERE `email` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $userId, $passwordInDb);
                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $passwordInDb)) {
                    unset($_SESSION['userAuthorized']);
                    $_SESSION['userAuthorized'] = array(
                        "isAuthorized" => TRUE,
                        "userId" => $userId);
                    $_SESSION['success'] = 'Successful authorization';
                } else {
                    $_SESSION['errors'] = ['Email or password is not correct'];
                }
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            $_SESSION['errors'] = ['Errors occurred while registration. Try to contact the administrator.'];
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }

        return TRUE;
    }
}