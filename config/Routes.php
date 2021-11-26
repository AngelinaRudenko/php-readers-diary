<?php

return array(

    'register' => 'user/register',
    'login' => 'user/login',
    'logout' => 'user/logout',
    'commonBookList' => 'book/getCommonBookList',
    'myBooks' => 'review/getUserBookList',
    'book/([0-9]+)' => 'book/getBook/$1',
    'addReview' => 'review/addReview',
    'addReviewForNewBook' => 'review/addReview',
    "saveReview" => 'review/saveReview',
    "deleteReview/([0-9]+)" => 'review/delete/$1',

    '' => 'book/getCommonBookList' // default
);