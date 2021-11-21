<?php include ROOT . '/views/shared/header.php'; ?>
<link rel="stylesheet" href="/template/css/registerAndLogin.css" />
<link rel="stylesheet" href="/template/css/validation.css">
<title>Log in</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

<form class="authorization-form" action="#" method="post">
    <?php include ROOT . '/views/shared/outputSection.php'; ?>
    <label>Email <span class="required">*</span></label>
    <input type="text" name="email" placeholder="Enter email"
           value="<?php if(isset($email)) { echo $email; } ?>" required>
    <label>Password <span class="required">*</span></label>
    <input type="password" name="password" placeholder="Enter password" required>
    <button type="submit">Log in</button>
    <p><a href="/register">Register</a></p>
</form>

<?php include ROOT . '/views/shared/footer.php'; ?>

