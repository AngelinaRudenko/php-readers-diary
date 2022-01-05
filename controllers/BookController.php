<?php

class BookController
{
    public function actionGetBook($bookId) {
        $_SESSION['book'] = Book::getBook($bookId);

        if (empty($_SESSION['book'])) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        if (isset($_SESSION['userAuthorized']) && $_SESSION['userAuthorized']) {
            $userId = $_SESSION['userAuthorized']['userId'];
            $_SESSION['review'] = Review::getUserBookReview($userId, $bookId);
            $_SESSION['comments'] = Review::getComments($bookId, $userId);
        } else {
            $_SESSION['comments'] = Review::getComments($bookId);
        }

        require_once(ROOT . '/views/book/book.php');
        return true;
    }

    public function actionGetBookList($page = null) {
        $page = empty($page) ? '1' : $page;
        $booksPerPage = 10;
        $orderBy = empty($_POST['orderBy']) ? 'name' : $_POST['orderBy'];
        $minRating = empty($_POST['minRating']) ? null : $_POST['minRating'];
        $maxRating = empty($_POST['maxRating']) ? null : $_POST['maxRating'];

        if (!empty($minRating) && !empty($maxRating) && $minRating > $maxRating) {
            $_SESSION['error'] = "Max rating can't be less then min rating";
            $pagesCount = Book::getBooksCount($booksPerPage);
            $_SESSION['commonBooks'] = Book::getCommonBooks($booksPerPage, $booksPerPage * ($page - 1), $orderBy);
            require_once(ROOT . '/views/book/bookList.php');
            return true;
        }

        $pagesCount = Book::getBooksCount($booksPerPage, $minRating, $maxRating);
        $_SESSION['commonBooks'] = Book::getCommonBooks($booksPerPage, $booksPerPage * ($page - 1), $orderBy, $minRating, $maxRating);

        require_once(ROOT . '/views/book/bookList.php');
        return true;
    }
}