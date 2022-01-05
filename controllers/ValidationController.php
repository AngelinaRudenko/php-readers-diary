<?php

class ValidationController
{
    public function actionValidateUsername() {
        $errors = User::validateUsername($_POST['username']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateEmail() {
        $errors = User::validateEmail($_POST['email']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidatePassword() {
        $errors = User::validatePassword($_POST['password']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateBookName() {
        $errors = Book::validateBookName($_POST['bookName']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateBookAuthor() {
        $errors = Book::validateBookAuthor($_POST['bookAuthor']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateBookDescription() {
        $errors = Book::validateBookDescription($_POST['bookDescription']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateReviewDateRead() {
        $errors = Review::validateDateRead($_POST['dateRead']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateReviewComment() {
        $errors = Review::validateComment($_POST['comment']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateReviewGrade() {
        $errors = Review::validateGrade($_POST['grade']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    public function actionValidateReviewNote() {
        $errors = Review::validateNote($_POST['note']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }
}