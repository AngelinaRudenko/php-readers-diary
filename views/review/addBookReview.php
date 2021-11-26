<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/addBookReview.css">
    <link rel="stylesheet" href="/template/css/validation.css">
    <title>Add book review</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <form class="book-review-form" action="/saveReview" method="post" enctype="multipart/form-data">
        <?php include ROOT . '/views/shared/outputSection.php'; ?>

        <?php if ($_SESSION['showBooksSection']): ?>
            <label for="bookName">Book name <span class="required">*</span></label>
            <input id="bookName" type="text" name="bookName" placeholder="Enter book name"
                   value="<?= isset($bookName) ? htmlspecialchars($bookName) : "" ?>"
                   pattern="[A-z\d\s]{1,50}" required>

            <label for="bookAuthor">Author <span class="required">*</span></label>
            <input id="bookAuthor" type="text" name="bookAuthor" placeholder="Enter book author"
                   value="<?= isset($bookAuthor) ? htmlspecialchars($bookAuthor) : "" ?>"
                   pattern="[A-z\d\s]{6,50}" required>

            <label for="bookDescription">Book description</label>
            <textarea id="bookDescription" rows="10" cols="30" name="bookDescription"
                      placeholder="Enter book description"
                      pattern=".{,2000}"
            ><?= isset($bookDescription) ? htmlspecialchars($bookDescription) : "" ?></textarea>
            <label for="bookCover">Book cover</label>
            <input id="bookCover" type="file" name="bookCover">

        <?php else : ?>

            <label for="bookId">Book <span class="required">*</span></label>
            <div class="select-section">
                <select id="bookId" name="bookId" required>
                    <?php foreach ($_SESSION['commonBooks'] as $book): ?>
                        <option value="<?= $book["bookId"] ?>">
                            <?php echo $book["name"] . " by " . $book["author"] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="/addReviewForNewBook" class="button">Other book</a>
            </div>

        <?php endif; ?>
        <label for="dateRead">Date read <span class="required">*</span></label>
        <input id="dateRead" type="date" name="dateRead"
               value="<?= isset($dateRead) ? htmlspecialchars($dateRead) : "" ?>" required>

        <label for="grade">Grade</label>
        <input id="grade" type="number" name="grade" placeholder="Enter grade"
               value="<?= isset($grade) ? htmlspecialchars($grade) : "" ?>"
               min="1" max="10" pattern="[d]{1,2}">

        <label for="comment">Comment</label>
        <textarea id="comment" rows="10" cols="30" name="comment"
                  placeholder="Enter public comment. It will be visible for everyone."
                  pattern=".{,2000}"
        ><?= isset($comment) ? htmlspecialchars($comment) : "" ?></textarea>

        <label for="note">Note</label>
        <textarea id="note" rows="10" cols="30" name="note"
                  placeholder="Enter private note. It will be visible only to you."
                  pattern=".{,2000}"
        ><?= isset($note) ? htmlspecialchars($note) : "" ?></textarea>
        <button type="submit">Save</button>
    </form>

<?php include ROOT . '/views/shared/footer.php'; ?>