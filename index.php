<?php
    session_start();
?>

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
            if ($_SESSION['success']) {
                echo '<p class="success">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            }
            else if ($_SESSION['errors']) {
                echo '<p class="errors">' . $_SESSION['errors'] . '</p>';
                unset($_SESSION['errors']);
            }
        ?>
        <form class="authorization-form" action="/vendor/signin.php" method="post">
            <label>Email</label>
            <input type="text" name="email" placeholder="Enter email">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password">
            <button type="submit">Log in</button>
            <p><a href="/registration.php">Sign up</a></p>
        </form>
    </div>
</body>
</html>