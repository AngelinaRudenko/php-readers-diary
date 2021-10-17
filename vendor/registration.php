<?php

    session_start();
    require_once 'connect.php';

    $profilePicture = $_FILES['profilePicture'];

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password == $confirmPassword) {
        $path = 'uploads/' . time() . '_' . $profilePicture['name'];

        if (!move_uploaded_file($profilePicture['tmp_name'], '../' . $path)) {
            $_SESSION['errors'] = 'Errors occurred while uploading the image';
            header('Location: ../registration.php');
        } else {
            $password = md5($password); // hash the password
            mysqli_query($connect, "INSERT INTO `user` (`id`, `username`, `email`, `password`, `profilePicture`) VALUES (NULL, '$username', '$email', '$password', '$path')");
            $_SESSION['success'] = 'Successful registration';
            header('Location: ../index.php');
        }
    } else {
        $_SESSION['errors'] = 'Passwords don\'t match';
        header('Location: ../registration.php');
    }?>
<!--    <pre>-->
<!--        --><?php
//            print_r($profilePicture['name']);
//        ?>
<!--    </pre>-->