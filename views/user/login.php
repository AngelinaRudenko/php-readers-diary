<?php include ROOT . '/views/shared/header.php'; ?>
<div class="flex-vertical-and-horizontal-center">
    <form class="authorization-form" action="#" method="post">
        <?php include ROOT . '/views/shared/outputSection.php'; ?>
        <label>Email</label>
        <input type="text" name="email" placeholder="Enter email"
               value="<?php if(isset($email)) { echo $email; } ?>">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password">
        <button type="submit">Log in</button>
        <p><a href="/register">Register</a></p>
    </form>
</div>
<?php include ROOT . '/views/shared/footer.php'; ?>