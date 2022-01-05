<?php

return array(

    'register' => 'user/register',
    'login' => 'user/login',
    'logout' => 'user/logout',
    'bookList' => 'book/getBookList',
    'bookList/([0-9]+)' => 'book/getBookList/$1',
    'reviews' => 'review/getReviews',
    'reviews/([0-9]+)' => 'review/getReviews/$1',
    'book/([0-9]+)' => 'book/getBook/$1',
    'addReview' => 'review/addReview',
    'addReview/([0-9]+)' => 'review/addReview/$1',
    'showBooksSection' => 'review/addReviewForNewBook',
    'saveReview' => 'review/saveReview',
    'deleteReview/([0-9]+)' => 'review/delete/$1',
    'editReview/([0-9]+)' => 'review/edit/$1',
    'validateUsername' => 'validation/validateUsername',
    'validateEmail' => 'validation/validateEmail',
    'validatePassword' => 'validation/validatePassword',
    'validateBookName' => 'validation/validateBookName',
    'validateBookAuthor' => 'validation/validateBookAuthor',
    'validateBookDescription' => 'validation/validateBookDescription',
    'validateReviewDateRead' => 'validation/validateReviewDateRead',
    'validateReviewComment' => 'validation/validateReviewComment',
    'validateReviewGrade' => 'validation/validateReviewGrade',
    'validateReviewNote' => 'validation/validateReviewNote',
    'changeColorTheme' => 'style/changeColorTheme',

    '' => 'book/getBookList' // default
);