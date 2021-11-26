<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/book.css"/>
    <title>My books</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <div class="content">
        <section class="book">
            <img class="img-book"
                 src="<?= isset($_SESSION['book']['bookCoverImage']) ?
                     $_SESSION['book']['bookCoverImage'] : '/template/defaultBookCoverImage.jpg' ?>"
                 alt=""/>
            <div class="card-content">
                <h2 class="book-name"><?= htmlspecialchars($_SESSION['book']['name']) ?></h2>
                <span>by <?= htmlspecialchars($_SESSION['book']['author']) ?></span>
                <!--                TODO: rating-->
                <p>Rating: 1.2</p>
                <p class="book-sum"><?= htmlspecialchars($_SESSION['book']['description']) ?></p>
                <?php if (isset($_SESSION['userAuthorized']) and $_SESSION['userAuthorized'] == TRUE) {
//                    TODO
                    echo '<a class="button">Mark as read</a>';
                } ?>
            </div>
        </section>
        <?php if (isset($_SESSION['userAuthorized']) and $_SESSION['userAuthorized'] == TRUE) {
            echo '<section class="feedback">';
            echo '<h2>My feedback</h2>';
            // TODO: feedback
            echo '</section>';
        } ?>
        <section class="comments">
            <h2>Comments</h2>
            <div class="comment">
                <p class="name">Name</p>
                <p class="text">Comment</p>
            </div>
            <div class="comment">
                <p class="name">Name</p>
                <p class="text">Comment</p>
            </div>
            <div class="comment">
                <p class="name">Name</p>
                <p class="text">Comment</p>
            </div>
        </section>
    </div>

<?php include ROOT . '/views/shared/footer.php'; ?>