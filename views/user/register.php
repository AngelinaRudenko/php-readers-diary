<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/static/css/registerAndLogin.css"/>
    <link rel="stylesheet" href="/static/css/validation.css">
    <title>Register</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <!--enctype="multipart/form-data" - for uploading files-->
    <form id="registrationForm" class="authorization-form" action="#" method="post" enctype="multipart/form-data">
        <?php include ROOT . '/views/shared/outputSection.php'; ?>

        <label for="username">Display name <span class="required">*</span></label>
        <span id="usernameValidationError" class="small-text"></span>
        <input id="username" type="text" name="username" placeholder="Enter username"
               value="<?= isset($username) ? htmlspecialchars($username) : ""?>"
               pattern="^\S+[A-z\d\.]{6,50}" required>
        <label for="email">Email <span class="required">*</span></label>
        <span id="emailValidationError" class="small-text"></span>
        <input id="email" type="email" name="email" placeholder="Enter email"
               value="<?= isset($email) ? htmlspecialchars($email) : ""?>" required>
        <label for="password">Password <span class="required">*</span></label>
        <span id="passwordValidationError" class="small-text"></span>
        <input id="password" type="password" name="password" placeholder="Enter password"
               pattern="^[A-z\d]{8,50}" required>
        <label for="confirmPassword">Confirm password <span class="required">*</span></label>
        <span id="confirmPasswordValidationError" class="small-text"></span>
        <input id="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm password" required>
        <button type="submit">Register</button>
        <a href="/login">Log in</a>
    </form>

<script src="/static/js/registrationValidation.js"></script>
<script src="/static/js/commonFieldsValidation.js"></script>
<?php include ROOT . '/views/shared/footer.php'; ?>