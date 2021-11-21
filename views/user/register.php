<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/registerAndLogin.css" />
    <link rel="stylesheet" href="/template/css/validation.css">
    <title>Register</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

<!--enctype="multipart/form-data" - for uploading files-->
<form class="authorization-form" action="#" method="post" enctype="multipart/form-data">
    <?php include ROOT . '/views/shared/outputSection.php'; ?>
    <label>Display name <span class="required">*</span></label>
    <input type="text" name="username" placeholder="Enter username"
           value="<?php if(isset($username)) { echo $username; } ?>" required>
    <label>Email <span class="required">*</span></label>
    <input type="text" name="email" placeholder="Enter email"
           value="<?php if(isset($email)) { echo $email; } ?>" required>
    <label>Profile picture</label>
    <input type="file" name="profilePicture" placeholder="Pick a photo"
           value="<?php if(isset($profilePicture)) { echo $profilePicture; } ?>">
    <label>Password <span class="required">*</span></label>
    <input type="password" name="password" placeholder="Enter password" required>
    <label>Confirm password <span class="required">*</span></label>
    <input type="password" name="confirmPassword" placeholder="Confirm password" required>
    <button type="submit">Sign up</button>
    <p><a href="/login">Log in</a></p>
</form>

<?php include ROOT . '/views/shared/footer.php'; ?>