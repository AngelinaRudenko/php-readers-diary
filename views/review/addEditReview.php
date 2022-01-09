<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/static/css/addEditReview.css">
    <link rel="stylesheet" href="/static/css/validation.css">
    <title>Add book review</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <form id="reviewForm" class="book-review-form" action="/saveReview" method="post" enctype="multipart/form-data">
        <input type="hidden" name="reviewId" value="<?= isset($reviewId) ? $reviewId : "" ?>">
        <?php include ROOT . '/views/shared/outputSection.php'; ?>

        <?php if (isset($_SESSION['showBooksSection']) && $_SESSION['showBooksSection']): ?>

            <label for="bookName">Book name <span class="required">*</span></label>
            <span id="bookNameValidationError" class="small-text"></span>
            <input id="bookName" type="text" name="bookName" placeholder="Enter book name"
                   value="<?= !empty($bookName) ? htmlspecialchars($bookName) : "" ?>"
                   pattern="[A-z\d\s\.'^£$%&*()}{@#~?><>,|=_+¬-]{1,50}" required>
            <label for="bookAuthor">Author <span class="required">*</span></label>
            <span id="bookAuthorValidationError" class="small-text"></span>
            <input id="bookAuthor" type="text" name="bookAuthor" placeholder="Enter book author"
                   value="<?= !empty($bookAuthor) ? htmlspecialchars($bookAuthor) : "" ?>"
                   pattern="[A-z\s]{6,50}" required>
            <label for="bookDescription">Book description</label>
            <span id="bookDescriptionValidationError" class="small-text"></span>
            <textarea id="bookDescription" rows="10" cols="30" name="bookDescription"
                      placeholder="Enter book description" maxlength="2000"
            ><?= !empty($bookDescription) ? htmlspecialchars($bookDescription) : "" ?></textarea>
            <label for="bookCover">Book cover</label>
            <input id="bookCover" type="file" name="bookCover">
        <?php else : ?>

            <label for="bookId">Book <span class="required">*</span></label>
            <div class="select-section">
                <select id="bookId" name="bookId" <?= !empty($reviewId) ? "disabled" : "required" ?>>
                    <option disabled value="">Select book</option>
                    <?php
                    foreach ($_SESSION['books'] as $book) {
                        echo "<option value=\"" . $book["bookId"] . "\"";
                        if (!empty($_SESSION['readBookId']) && $book["bookId"] == $_SESSION['readBookId']) {
                            echo "selected";
                        }
                        echo ">" . $book["name"] . " by " . $book["author"] . "</option>";
                    } ?>
                </select>
                <?php if (empty($_SESSION['readBookId'])): ?>
                    <a href="/showBooksSection" class="button">Other book</a>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <label for="dateRead">Date read <span class="required">*</span></label>
        <span id="dateReadValidationError" class="small-text"></span>
        <input id="dateRead" type="date" name="dateRead"
               value="<?= !empty($dateRead) ? htmlspecialchars($dateRead) : "" ?>" required>
        <label for="grade">Grade</label>
        <span id="gradeValidationError" class="small-text"></span>
        <input id="grade" type="number" name="grade" placeholder="Enter grade"
               value="<?= !empty($grade) ? $grade : "" ?>" min="1" max="10">
        <label for="comment">Comment</label>
        <span id="commentValidationError" class="small-text"></span>
        <textarea id="comment" rows="10" cols="30" name="comment"
                  placeholder="Enter public comment. It will be visible for everyone."
                  maxlength="2000"
        ><?= !empty($comment) ? htmlspecialchars($comment) : "" ?></textarea>
        <label for="note">Note</label>
        <span id="noteValidationError" class="small-text"></span>
        <textarea id="note" rows="10" cols="30" name="note"
                  placeholder="Enter private note. It will be visible only to you."
                  maxlength="2000"
        ><?= !empty($note) ? htmlspecialchars($note) : "" ?></textarea>
        <button type="submit">Save</button>
    </form>

<?php if (!empty($_SESSION['showBooksSection']) && $_SESSION['showBooksSection']): ?>
    <script src="/static/js/bookValidation.js"></script>
<?php endif; ?>
    <script src="/static/js/reviewValidation.js"></script>
    <script src="/static/js/commonFieldsValidation.js"></script>
<?php
unset($_SESSION['readBookId']);
unset($_SESSION['commonBooks']);
include ROOT . '/views/shared/footer.php';
?>