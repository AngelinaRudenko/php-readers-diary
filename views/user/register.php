<?php include ROOT . '/views/shared/header.php'; ?>
<div class="flex-vertical-and-horizontal-center">
    <!--enctype="multipart/form-data" - for uploading files-->
    <form class="authorization-form" action="#" method="post" enctype="multipart/form-data">
        <?php include ROOT . '/views/shared/outputSection.php'; ?>
        <label>Display name <span class="required">*</span></label>
        <input type="text" name="username" placeholder="Enter username"
               value="<?php if(isset($username)) { echo $username; } ?>">
        <label>Email <span class="required">*</span></label>
        <input type="text" name="email" placeholder="Enter email"
               value="<?php if(isset($email)) { echo $email; } ?>">
        <label>Profile picture</label>
        <input type="file" name="profilePicture" placeholder="Pick a photo"
               value="<?php if(isset($profilePicture)) { echo $profilePicture; } ?>">
        <label>Password <span class="required">*</span></label>
        <input type="password" name="password" placeholder="Enter password">
        <label>Confirm password <span class="required">*</span></label>
        <input type="password" name="confirmPassword" placeholder="Confirm password">
        <button type="submit">Sign up</button>
        <p><a href="/login">Log in</a></p>
    </form>
</div>
<?php include ROOT . '/views/shared/footer.php'; ?>