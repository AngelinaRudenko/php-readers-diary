<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/static/css/book.css"/>
    <title>My books</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <div class="content">
        <section class="book">
            <img class="img-book" alt="" src="<?php
            if (isset($_SESSION['book']['bookCoverImage'])) {
                $fileName = basename($_SESSION['book']['bookCoverImage']);
                echo htmlspecialchars('/uploads/cachedBookCoverPictures/bookPage_' . $fileName);
            } else {
                echo '/uploads/cachedBookCoverPictures/bookListPage_defaultBookCoverImage.jpg';
            }
            ?>"/>
            <div class="card-content">
                <h2 class="book-name"><?= htmlspecialchars($_SESSION['book']['name']) ?></h2>
                <span>by <?= htmlspecialchars($_SESSION['book']['author']) ?></span>
                <p>Rating: <?= $_SESSION['book']['avgRating'] ?></p>
                <p class="book-sum"><?= htmlspecialchars($_SESSION['book']['description']) ?></p>
                <?php
                    if (isset($_SESSION['userAuthorized']) && $_SESSION['userAuthorized']) {
                        if (empty($_SESSION['review'])) {
                            $_SESSION['readBookId'] = $_SESSION['book']['bookId'];
                            echo '<a href="/addReview" class="button">Mark as read</a>';
                        }
                        else {
                            echo '<a href="/editReview/'.$_SESSION['review']['userBookId'].'" class="button">Edit review</a>';
                        }
                    }
                ?>
            </div>
        </section>
        <?php if (isset($_SESSION['userAuthorized']) && $_SESSION['userAuthorized'] && isset($_SESSION['review']) && !empty($_SESSION['review'])): ?>
            <section class="feedback">
                <h2>My feedback</h2>
                <p><strong class="book-name">Date read:</strong> <?= $_SESSION['review']['dateRead'] ?></p>
                <p><strong class="book-name">My grade:</strong> <?= $_SESSION['review']['grade'] ?></p>
                <p><strong class="book-name">Comment:</strong> <?= htmlspecialchars($_SESSION['review']['comment']) ?></p>
                <p><strong class="book-name">Note:</strong> <?= htmlspecialchars($_SESSION['review']['note']) ?></p>
            </section>
        <?php endif; ?>
        <?php if (isset($_SESSION['comments']) && !empty($_SESSION['comments'])): ?>
            <section class="comments">
                <h2>Comments</h2>
                <?php foreach ($_SESSION['comments'] as $comment): ?>
                <div class="comment">
                    <p class="name"><?= htmlspecialchars($comment['username']) ?></p>
                    <p class="text"><?= htmlspecialchars($comment['comment']) ?></p>
                </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </div>

<?php
    unset($_SESSION['review']);
    unset($_SESSION['book']);
    include ROOT . '/views/shared/footer.php';
?>