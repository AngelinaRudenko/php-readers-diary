<?php include ROOT . '/views/shared/header.php'; ?>
<link rel="stylesheet" href="/template/css/registerAndLogin.css"/>
<link rel="stylesheet" href="/template/css/validation.css">
<title>Log in</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

<form class="authorization-form" action="#" method="post">
    <?php include ROOT . '/views/shared/outputSection.php'; ?>
    <label for="email">Email <span class="required">*</span></label>
    <input id="email" type="email" name="email" placeholder="Enter email"
           value="<?= isset($email)? htmlspecialchars($email) : ""?>"
           pattern="[a-z0-9._%+-]+@[a-z0-9s.-]+\.[a-z]{2,}$" required>

    <label for="password">Password <span class="required">*</span></label>
    <input id="password" type="password" name="password" placeholder="Enter password" required>

    <button type="submit">Log in</button>
    <a href="/register">Register</a>
</form>

<?php include ROOT . '/views/shared/footer.php'; ?>

