<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/static/css/reviewList.css"/>
    <title>My books</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <form id="filter" method="post" enctype="multipart/form-data">
        <span>Sort by</span>
        <select id="orderBy" name="orderBy">
            <option value="name" <?= !empty($orderBy) && $orderBy == 'name' ? 'selected' : '' ?>>Book name</option>
            <option value="author" <?= !empty($orderBy) && $orderBy == 'author' ? 'selected' : '' ?>>Author</option>
            <option value="rating" <?= !empty($orderBy) && $orderBy == 'rating' ? 'selected' : '' ?>>Rating</option>
            <option value="dateRead" <?= empty($orderBy) || $orderBy == 'dateRead' ? 'selected' : '' ?>>Date read</option>
            <option value="grade" <?= !empty($orderBy) && $orderBy == 'grade' ? 'selected' : '' ?>>My grade</option>
        </select>
        <button type="submit">Apply</button>
    </form>
    <a href="/addReview" class="button">Add book review</a>
    <div class="book-list">
        <?php if (!empty($_SESSION['userReviews'])) : ?>
            <?php foreach ($_SESSION['userReviews'] as $book): ?>
                <article class="book-row">
                    <h2 class="book-name"><?= !empty($book['name']) ? htmlspecialchars($book['name']) : "" ?></h2>
                    <span>by <?= !empty($book['author']) ? htmlspecialchars($book['author']) : ""?></span>
                    <p><strong class="book-name">Date read:</strong> <?= !empty($book['dateRead']) ? $book['dateRead'] : ""?></p>
                    <p><strong class="book-name">My grade:</strong> <?= !empty($book['grade']) ? $book['grade'] : "" ?></p>
                    <p><strong class="book-name">Comment:</strong> <?= !empty($book['comment']) ? htmlspecialchars($book['comment']) : ""?></p>
                    <p><strong class="book-name">Note:</strong> <?= !empty($book['note']) ? htmlspecialchars($book['note']) : "" ?></p>
                    <a href="/book/<?= $book['bookId'] ?>" class="button">See book</a>
                    <a href="/editReview/<?= $book['userBookId'] ?>" class="button">Edit</a>
                    <a href="/deleteReview/<?= $book['userBookId'] ?>" class="button">Delete</a>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p>You didn't read any books yet</p>
        <?php endif; ?>
    </div>

<?php
include ROOT . '/views/shared/pagination.php';
include ROOT . '/views/shared/footer.php';
unset($_SESSION['userReviews']);
?>