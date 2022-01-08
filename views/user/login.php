<?php include ROOT . '/views/shared/header.php'; ?>
<link rel="stylesheet" href="/static/css/registerAndLogin.css"/>
<link rel="stylesheet" href="/static/css/validation.css">
<title>Log in</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

<form class="authorization-form" action="#" method="post">
    <?php include ROOT . '/views/shared/outputSection.php'; ?>
    <label for="email">Email <span class="required">*</span></label>
    <span id="emailValidationError" class="small-text">Email is required</span>
    <input id="email" type="email" name="email" placeholder="Enter email"
           value="<?= isset($email) ? htmlspecialchars($email) : "" ?>" required>
    <label for="password">Password <span class="required">*</span></label>
    <span id="passwordValidationError" class="small-text">Password is required</span>
    <input id="password" type="password" name="password" placeholder="Enter password" required>
    <button type="submit">Log in</button>
    <a href="/register">Register</a>
</form>

<?php include ROOT . '/views/shared/footer.php'; ?>

