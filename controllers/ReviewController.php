<?php

class ReviewController
{
    public function actionGetReviews($page = null) {
        $page = empty($page) ? '1' : $page;
        $reviewsPerPage = 10;
        $orderBy = empty($_POST['orderBy']) ? 'dateRead' : $_POST['orderBy'];

        $userId = $_SESSION['userAuthorized']["userId"];
        $pagesCount = Review::getReviewsCount($userId, $reviewsPerPage);
        $_SESSION['userReviews'] = Review::getUserBookReviews($userId, $reviewsPerPage, $reviewsPerPage * ($page - 1), $orderBy);

        require_once(ROOT . '/views/review/reviewList.php');
        return true;
    }

    public function actionAddReview($bookId = null) {
        if (isset($bookId)) {
            $_SESSION['readBookId'] = $bookId;
        }

        $userId = $_SESSION['userAuthorized']["userId"];
        $books = Book::getBooksNotReviewedByUser($userId);
        $_SESSION['books'] = $books;

        $_SESSION['showBooksSection'] = empty($books);

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }

    public function actionAddReviewForNewBook() {
        $_SESSION['showBooksSection'] = TRUE;

//        $dateRead = $_POST['dateRead'];
//        $grade = empty($_POST['grade']) ? null : $_POST['grade'];
//        $comment = empty($_POST['comment']) ? null : trim($_POST['comment']);
//        $note = empty($_POST['note']) ? null : trim($_POST['note']);

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }

    public function actionSaveReview() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            require_once(ROOT . '/views/review/addEditReview.php');
            return true;
        }

        $reviewId = $_POST['reviewId'];
        $dateRead = $_POST['dateRead'];
        $grade = empty($_POST['grade']) ? null : $_POST['grade'];
        $comment = empty($_POST['comment']) ? null : trim($_POST['comment']);
        $note = empty($_POST['note']) ? null : trim($_POST['note']);
        $userId = $_SESSION['userAuthorized']["userId"];

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
        } else if (empty($reviewId)) {
            $bookId = $_POST['bookId'];
        }

        $errors = Review::validateReview($dateRead, $grade, $comment, $note);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/review/addEditReview.php');
            return true;
        }

        if (isset($_SESSION['showBooksSection']) && $_SESSION['showBooksSection']) {
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

            $result = Review::saveReviewForNewBook($userId, $dateRead, $grade, $comment, $note, $bookName, $bookAuthor,
                $bookDescription, $path);
            if (isset($result) && !empty($result)) {
                $bookId = $result['bookId'];
                $reviewId = $result['reviewId'];
                $_SESSION['success'] = 'Review saved successfully';
                self::actionEdit($reviewId);
                return True;
            }
        } else if (!empty($reviewId)) {
            $reviewId = Review::updateReview($reviewId, $dateRead, $grade, $comment, $note);
        } else {
            $reviewId = Review::saveReview($userId, $bookId, $dateRead, $grade, $comment, $note);
        }

        if ($reviewId != -1) {
            $_SESSION['success'] = 'Review saved successfully';
            unset($_SESSION['readBookId']);
        } else {
            $_SESSION['errors'] = ['Errors occurred while saving. Try to contact the administrator.'];
            if (isset($path)) {
                unlink($path);
            }
        }

        require_once(ROOT . '/views/review/addEditReview.php');
        return true;
    }

    public static function actionDelete($reviewId) {
        Review::deleteReview($reviewId);
        header("Location: /reviews");
        return true;
    }

    public static function actionEdit($reviewId) {
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