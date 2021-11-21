<?php

class BookController
{
    public function actionGetBook()
    {
        require_once(ROOT . '/views/book/book.php');
        return true;
    }
}