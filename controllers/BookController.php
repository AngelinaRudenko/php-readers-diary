<?php

class BookController
{
    public function actionGetBook($bookId)
    {
        unset($_SESSION['book']);
        $_SESSION['book'] = Book::getBook($bookId);

        require_once(ROOT . '/views/book/book.php');
        return true;
    }

    public function actionGetCommonBookList()
    {
        unset( $_SESSION['commonBooks']);
        $_SESSION['commonBooks'] = Book::getCommonBooks();

        require_once(ROOT . '/views/book/commonBookList.php');
        return true;
    }
}