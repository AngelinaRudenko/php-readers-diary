<?php

    session_start();
    require_once 'connect.php';

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $user = mysqli_query($connect, "SELECT * FROM `user` WHERE `email`='$email' AND `password`='$password'");
    if (mysqli_num_rows($user) > 0) {
        $_SESSION['success'] = 'Successful authorization';
        header('Location: ../index.php');
    } else {
        $_SESSION['errors'] = 'Invalid email or password';
        header('Location: ../index.php');
    }