<?php

class BookController
{
    /**
     * Gets book by id, reviews and comments, then shows book page.
     * If book not found then Not Found message page will be shown
     * @param $bookId - book id
     * @return bool - just for redirect
     */
    public function actionGetBook($bookId = null) {
        $_SESSION['book'] = Book::getBook($bookId);

        // if book is not found, then redirect to Not Found message page
        if (empty($_SESSION['book'])) {
            require_once(ROOT . '/views/shared/notFount.php');
            return true;
        }

        // if user is authorized, gets his review and comments without his comment
        if (isset($_SESSION['userAuthorized']) && $_SESSION['userAuthorized']) {
            $userId = $_SESSION['userAuthorized']['userId'];
            $_SESSION['review'] = Review::getUserReviewByBookId($userId, $bookId);
            $_SESSION['comments'] = Review::getComments($bookId, $userId);
        } else { // gets all comments
            $_SESSION['comments'] = Review::getComments($bookId);
        }

        require_once(ROOT . '/views/book/book.php');
        return true;
    }

    /**
     * Gets books according to pagination, then shows book list page
     * @param null $page - page, if not set then null
     * @return bool - just for redirect
     */
    public function actionGetBookList($page = null) {
        // if page is not set, then page is first by default
        $page = empty($page) ? '1' : $page;

        // default books per page count
        $booksPerPage = 10;

        // if parameter is not set, then sort by name by default
        $orderBy = empty($_POST['orderBy']) ? 'name' : $_POST['orderBy'];

        $minRating = empty($_POST['minRating']) ? null : $_POST['minRating'];
        $maxRating = empty($_POST['maxRating']) ? null : $_POST['maxRating'];

        // if both min or max rating are not empty and min rating is bigger,
        // then it is not valid, then show error and sort by default
        if (!empty($minRating) && !empty($maxRating) && $minRating > $maxRating) {
            $_SESSION['error'] = "Max rating can't be less then min rating";
            $pagesCount = Book::getPagesCount($booksPerPage);
            $_SESSION['commonBooks'] = Book::getBooks($booksPerPage, $booksPerPage * ($page - 1), $orderBy);
            require_once(ROOT . '/views/book/bookList.php');
            return true;
        }

        $pagesCount = Book::getPagesCount($booksPerPage, $minRating, $maxRating);
        $_SESSION['commonBooks'] = Book::getBooks($booksPerPage, $booksPerPage * ($page - 1), $orderBy, $minRating, $maxRating);

        require_once(ROOT . '/views/book/bookList.php');
        return true;
    }
}