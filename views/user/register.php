<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/registerAndLogin.css"/>
    <link rel="stylesheet" href="/template/css/validation.css">
    <title>Register</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <!--enctype="multipart/form-data" - for uploading files-->
    <form class="authorization-form" action="#" method="post" enctype="multipart/form-data">
        <?php include ROOT . '/views/shared/outputSection.php'; ?>

        <label for="username">Display name <span class="required">*</span></label>
        <input id="username" type="text" name="username" placeholder="Enter username"
               value="<?= isset($username) ? htmlspecialchars($username) : ""?>"
                   pattern="^[A-z\d\.]{6,50}" required>

        <label for="email">Email <span class="required">*</span></label>
        <input id="email" type="email" name="email" placeholder="Enter email"
               value="<?= isset($email) ? htmlspecialchars($email) : ""?>"
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>

        <label for="profilePicture">Profile picture</label>
        <input id="profilePicture" type="file" name="profilePicture">

        <label for="password">Password <span class="required">*</span></label>
        <input id="password" type="password" name="password" placeholder="Enter password"
                   pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" required>

        <label for="confirmPassword">Confirm password <span class="required">*</span></label>
        <input id="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm password" required>

        <button type="submit">Register</button>
        <a href="/login">Log in</a>
    </form>

<?php include ROOT . '/views/shared/footer.php'; ?>