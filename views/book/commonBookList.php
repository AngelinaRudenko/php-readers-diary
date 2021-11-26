<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/commonBookList.css"/>
    <title>Reader's diary</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <div class="cards">
        <?php if (isset($_SESSION['commonBooks']) and count($_SESSION['commonBooks']) > 0) : ?>
            <?php foreach ($_SESSION['commonBooks'] as $book): ?>
                <article class="card">
                    <img class="img-book"
                         src="<?= isset($book['bookCoverImage']) ?
                             "uploads/".$book['bookCoverImage'] : '/template/defaultBookCoverImage.jpg' ?>"
                         alt=""/>
                    <div class="card-content">
                        <h2 class="book-name"><?= htmlspecialchars($book['name']) ?></h2>
                        <span>by <?= htmlspecialchars($book['author']) ?></span>
                        <!--                        TODO: rating-->
                        <p>Rating: 1.2</p>
                        <p class="book-sum"><?= htmlspecialchars($book['description']) ?></p>
                        <a href="/book/<?= htmlspecialchars($book['bookId']) ?>" class="button">See more</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p>There are no books yet. Be the first one to add them!</p>
        <?php endif; ?>
    </div>

<?php include ROOT . '/views/shared/footer.php'; ?>