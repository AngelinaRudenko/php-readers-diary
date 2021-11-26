<?php

class ReviewController
{
    public function actionGetUserBookList()
    {
        $userId = $_SESSION['userAuthorized']["userId"];

        unset($_SESSION['userReviews']);

        $_SESSION['userReviews'] = Review::getUserBookReviews($userId);

        require_once(ROOT . '/views/review/userReviews.php');
        return true;
    }

    public function actionAddReview()
    {
        unset($_SESSION['commonBooks']);
        unset($_SESSION['showBooksSection']);

        $commonBooks = Book::getCommonBooks();
        if (!empty($commonBooks)) {
            $_SESSION['commonBooks'] = $commonBooks;
            $_SESSION['showBooksSection'] = FALSE;
        } else {
            $_SESSION['showBooksSection'] = TRUE;
        }

        require_once(ROOT . '/views/review/addBookReview.php');
        return true;
    }

    public function actionAddReviewForNewBook()
    {
        unset($_SESSION['commonBooks']);
        unset($_SESSION['showBooksSection']);

        $_SESSION['showBooksSection'] = TRUE;

        require_once(ROOT . '/views/review/addBookReview.php');
        return true;
    }

    public function actionSaveReview()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            require_once(ROOT . '/views/review/addBookReview.php');
            return true;
        }

        $dateRead = $_POST['dateRead'];
        $grade = $_POST['grade'];
        $comment = $_POST['comment'];
        $note = $_POST['note'];
        $userId = $_SESSION['userAuthorized']["userId"];

        if ($_SESSION['showBooksSection']) {
            $bookName = $_POST['bookName'];
            $bookAuthor = $_POST['bookAuthor'];
            $bookDescription = $_POST['bookDescription'];
            $bookCover = $_FILES['bookCover'];

            $errors = Book::validateBook($bookName, $bookAuthor, $bookDescription);
        } else {
            $bookId = $_POST['bookId'];
        }

        if (isset($errors) && count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/review/addBookReview.php');
            return true;
        }

        $errors = Review::validateReview($dateRead, $grade, $comment, $note);

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            require_once(ROOT . '/views/review/addBookReview.php');
            return true;
        }

        if ($_SESSION['showBooksSection']) {
            $path = null;

            if ($bookCover['error'] != 4) {
                try {
                    $path = 'bookCoverPictures/' . time() . '_' . $bookCover['name'];
                    move_uploaded_file($bookCover['tmp_name'], 'uploads/' . $path);
                } catch (Exception $e) {
                    $_SESSION['errors'] = ["Errors occurred while uploading the image"];
                    require_once(ROOT . '/views/review/addBookReview.php');
                    return true;
                } finally {
                    unset($_FILES['bookCover']);
                }
            }

            $success = Review::saveReviewForNewBook($userId, $dateRead, $grade, $comment, $note, $bookName, $bookAuthor,
                $bookDescription, $path);
        } else {
            $success = Review::saveReview($userId, $bookId, $dateRead, $grade, $comment, $note);
        }

        if ($success) {
            $_SESSION['success'] = 'Review saved successfully';
        } else {
            $_SESSION['errors'] = ['Errors occurred while saving. Try to contact the administrator.'];
            if (isset($path)) {
                unlink($path);
            }
        }

        require_once(ROOT . '/views/review/addBookReview.php');
        return true;
    }

    public static function actionDelete($reviewId)
    {
        Review::deleteReview($reviewId);

        $userId = $_SESSION['userAuthorized']["userId"];

        unset($_SESSION['userReviews']);

        $_SESSION['userReviews'] = Review::getUserBookReviews($userId);

        require_once(ROOT . '/views/review/userReviews.php');
        return true;
    }
}