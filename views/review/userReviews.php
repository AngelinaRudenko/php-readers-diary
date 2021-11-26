<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/userBookList.css"/>
    <title>My books</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <a href="/addReview" class="button">Add book review</a>
    <div class="book-list">
        <?php if (!empty($_SESSION['userReviews'])) : ?>
            <?php foreach ($_SESSION['userReviews'] as $book): ?>
                <article class="book-row">
                    <h2 class="book-name"><?= $book['name'] ?></h2>
                    <span>by <?= htmlspecialchars($book['author']) ?></span>
                    <p><strong class="book-name">My grade:</strong> <?= htmlspecialchars($book['grade']) ?></p>
                    <p><strong class="book-name">Comment:</strong> <?= htmlspecialchars($book['comment']) ?></p>
                    <a href="/book/<?= htmlspecialchars($book['bookId']) ?>" class="button">See more</a>
                    <a href="/deleteReview/<?= htmlspecialchars($book['userBookId']) ?>" class="button">Delete</a>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p>You didn't read any books yet</p>
        <?php endif; ?>
    </div>

<?php include ROOT . '/views/shared/footer.php'; ?>