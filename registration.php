<?php session_start() ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css">
    <title>Reader's diary</title>
</head>
<body>
<div class="flex-vertical-and-horizontal-center">
    <?php
        if ($_SESSION['errors']) {
            echo '<p class="errors">' . $_SESSION['errors'] . '</p>';
            unset($_SESSION['errors']);
        }
    ?>

    <!--enctype="multipart/form-data" - for uploading files-->
    <form class="authorization-form" action="/vendor/registration.php" method="post" enctype="multipart/form-data">
        <label>Display name <span class="required">*</span></label>
        <input type="text" name="username" placeholder="Enter username">
        <label>Email <span class="required">*</span></label>
        <input type="text" name="email" placeholder="Enter email">
        <label>Profile picture</label>
        <input type="file" name="profilePicture" placeholder="Pick a photo">
        <label>Password <span class="required">*</span></label>
        <input type="password" name="password" placeholder="Enter password">
        <label>Confirm password <span class="required">*</span></label>
        <input type="password" name="confirmPassword"" placeholder="Confirm password">
        <button type="submit">Sign up</button>
        <p><a href="/index.php">Log in</a></p>
    </form>
</div>
</body>
</html>