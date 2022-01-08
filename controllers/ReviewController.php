<?php

class ReviewController
{
    /**
     * Gets reviews according to pagination, then shows reviews page.
     * @param null $page - page, if not set then null
     * @return bool - just for redirect
     */
    public function actionGetReviews($page = null)
    {
        if (empty($_SESSION['userAuthorized']) || !$_SESSION['userAuthorized']["isAuthorized"]) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        // if page is not set, then page is first by default
        $page = empty($page) ? '1' : $page;

        // default reviews per page count
        $reviewsPerPage = 10;

        // if parameter is not set, then sort by date read by default
        $orderBy = empty($_POST['orderBy']) ? 'dateRead' : $_POST['orderBy'];

        $userId = $_SESSION['userAuthorized']["userId"];
        $pagesCount = Review::getPagesCount($userId, $reviewsPerPage);
        $_SESSION['userReviews'] = Review::getUserReviews($userId, $reviewsPerPage, $reviewsPerPage * ($page - 1), $orderBy);

        require_once(ROOT . '/views/review/reviewList.php');
        return true;
    }

    /**
     * Prepares and shows page for adding review for existing book.
     * @param null $bookId - book id. If not set then null and will be shown Not Found message page
     * @return bool - just for redirect
     */
    public function actionAddReview($bookId = null)
    {
        if (empty($_SESSION['userAuthorized']) || !$_SESSION['userAuthorized']["isAuthorized"]) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        if (!empty($bookId)) {
            $_SESSION['readBookId'] = $bookId;
        }

        $userId = $_SESSION['userAuthorized']["userId"];
        $books = Book::getBooksNotReviewedByUser($userId);
        $_SESSION['books'] = $books;

        $_SESSION['showBooksSection'] = empty($books);

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }

    /**
     * Prepare and shows page for adding review for new book.
     * Section for creating book will be shown.
     * @return bool - just for redirect
     */
    public function actionAddReviewForNewBook()
    {
        if (empty($_SESSION['userAuthorized']) || !$_SESSION['userAuthorized']["isAuthorized"]) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        // shows book creation section
        $_SESSION['showBooksSection'] = TRUE;

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }

    /**
     * Saves new or edited review. If review was done for new book, that is not in the system, saves book too.
     * @return bool - just for redirect
     */
    public function actionSaveReview()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            require_once(ROOT . '/views/review/addEditReview.php');
            return true;
        }
        if (empty($_SESSION['userAuthorized']) || !$_SESSION['userAuthorized']["isAuthorized"]) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        $reviewId = $_POST['reviewId'];
        $dateRead = $_POST['dateRead'];
        $grade = empty($_POST['grade']) ? null : $_POST['grade'];
        $comment = empty($_POST['comment']) ? null : trim($_POST['comment']);
        $note = empty($_POST['note']) ? null : trim($_POST['note']);
        $userId = $_SESSION['userAuthorized']["userId"];

        $errors = Review::validateReview($dateRead, $grade, $comment, $note);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/review/addEditReview.php');
            return true;
        }

        // if new book was created
        if (isset($_SESSION['showBooksSection']) && $_SESSION['showBooksSection']) {

            $bookName = trim($_POST['bookName']);
            $bookAuthor = trim($_POST['bookAuthor']);
            $bookDescription = empty($_POST['bookDescription']) ? null : trim($_POST['bookDescription']);
            $bookCover = $_FILES['bookCover'];

            $errors = Book::validateBook($bookName, $bookAuthor, $bookDescription);

            if (isset($errors) && count($errors) > 0) {
                $_SESSION['errors'] = $errors;
                require_once(ROOT . '/views/review/addEditReview.php');
                return true;
            }

            $path = null;

            if ($bookCover['error'] != 4) {
                try {
                    $path = 'bookCoverPictures/' . time() . '_' . $bookCover['name'];
                    move_uploaded_file($bookCover['tmp_name'], 'uploads/' . $path);
                } catch (Exception $e) {
                    $_SESSION['errors'] = ["Errors occurred while uploading the image"];
                    require_once(ROOT . '/views/review/addEditReview.php');
                    return true;
                } finally {
                    unset($_FILES['bookCover']);
                }
            }

            $result = Review::saveReviewForNewBook($userId,
                $dateRead, $grade, $comment, $note, $bookName, $bookAuthor, $bookDescription, $path);

            if (isset($result) && !empty($result)) {
                $bookId = $result['bookId'];
                $reviewId = $result['reviewId'];
                if (!empty($path)) {
                    Image::resizeAndCache($path);
                }
                $_SESSION['success'] = 'Review saved successfully';
                self::actionEdit($reviewId);
                return True;
            }

        } else if (empty($reviewId)) {

            $bookId = $_POST['bookId'];
            $reviewId = Review::saveReview($userId, $bookId, $dateRead, $grade, $comment, $note);

        } else { // update existing review

            $reviewId = Review::updateReview($reviewId, $dateRead, $grade, $comment, $note);

        }

        if ($reviewId != -1) {
            $_SESSION['success'] = 'Review saved successfully';
            unset($_SESSION['readBookId']);
        } else {
            $_SESSION['errors'] = ['Errors occurred while saving. Try to contact the administrator.'];
        }

        if (isset($path)) {
            unlink('uploads/' . $path);
        }

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }

    /**
     * Deletes review by its id. If review is the last for its book, that deletes the book too.
     * @param $reviewId - review id for deleting
     * @return bool - just for redirect
     */
    public static function actionDelete($reviewId)
    {
        if (empty($_SESSION['userAuthorized']) || !$_SESSION['userAuthorized']["isAuthorized"]) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        Review::deleteReview($reviewId);
        header("Location: /reviews");
        return true;
    }

    /**
     * Only prepares and shows editing review page. The saving of edited review is in actionSave() function.
     * @param $reviewId
     * @return bool - just for redirect
     */
    public static function actionEdit($reviewId)
    {
        if (empty($_SESSION['userAuthorized']) || !$_SESSION['userAuthorized']["isAuthorized"]) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        unset($_SESSION['showBooksSection']);

        $review = Review::getUserBookReviewById($reviewId);

        if (empty($review)) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        $bookId = $review['bookId'];

        $_SESSION['books'] = [Book::getBook($bookId)];
        $_SESSION['readBookId'] = $bookId;
        $dateRead = $review['dateRead'];
        $grade = $review['grade'];
        $comment = $review['comment'];
        $note = $review['note'];

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }
}