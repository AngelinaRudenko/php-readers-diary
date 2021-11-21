<?php

class BookListController
{
    public function actionGetCommonBookList()
    {
        require_once(ROOT . '/views/bookList/commonBookList.php');
        return true;
    }

    public function actionGetUserBookList()
    {
        require_once(ROOT . '/views/bookList/userBookList.php');
        return true;
    }
}