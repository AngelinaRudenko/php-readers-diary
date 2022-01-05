<?php include ROOT . '/views/shared/header.php'; ?>
    <?php if (isset($_COOKIE['colorTheme']) && $_COOKIE['colorTheme'] == 'lightTheme'): ?>
    <link rel="stylesheet" href="/template/css/bookList.css"/>
    <link rel="stylesheet" href="/template/css/validation.css">
    <?php else: ?>
        <link rel="stylesheet" href="/template/css/bookListDark.css"/>
    <?php endif; ?>
    <title>Reader's diary</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <form id="filter" method="post" enctype="multipart/form-data">
        <span>Filter by rating</span>
        <span id="ratingFilterValidation" class="small-text"><?= empty($_SESSION['error']) ? '' : $_SESSION['error'] ?></span>
        <input id="minRating" type="number" name="minRating" placeholder="Min"
               value="<?= isset($minRating) ? $minRating : "" ?>"
               min="1" max="10" pattern="[d]{1,2}">
        <input id="maxRating" type="number" name="maxRating" placeholder="Max"
               value="<?= isset($maxRating) ? $maxRating : "" ?>"
               min="1" max="10" pattern="[d]{1,2}">
        <span>Sort by</span>
        <select id="orderBy" name="orderBy">
            <option value="name" <?= empty($orderBy) || $orderBy == 'name' ? 'selected' : '' ?>>Book name</option>
            <option value="author" <?= !empty($orderBy) && $orderBy == 'author' ? 'selected' : '' ?>>Author</option>
            <option value="rating" <?= !empty($orderBy) && $orderBy == 'rating' ? 'selected' : '' ?>>Rating</option>
        </select>
        <button type="submit">Apply</button>
    </form>

    <div class="cards">
        <?php if (isset($_SESSION['commonBooks']) && count($_SESSION['commonBooks']) > 0) : ?>
            <?php foreach ($_SESSION['commonBooks'] as $book): ?>
                <article class="card">
                    <img class="img-book" alt="" src="<?= isset($book['bookCoverImage']) ?
                        htmlspecialchars('/uploads/'.$book['bookCoverImage']) :
                        '/template/defaultBookCoverImage.jpg' ?>"/>
                    <div class="card-content">
                        <h2 class="book-name"><?= htmlspecialchars($book['name']) ?></h2>
                        <span>by <?= htmlspecialchars($book['author']) ?></span>
                        <p>Rating: <?= $book['avgRating'] ?></p>
                        <p class="book-sum"><?= htmlspecialchars($book['description']) ?></p>
                        <a href="/book/<?= $book['bookId'] ?>" class="button">See more</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <p>There are no books yet. Be the first one to add them!</p>
        <?php endif; ?>
    </div>

<?php include ROOT . '/views/shared/pagination.php'; ?>
<script src="/template/js/filter.js"></script>
<?php
    unset($_SESSION['error']);
    unset($_SESSION['commonBooks']);
    include ROOT . '/views/shared/footer.php';
?>