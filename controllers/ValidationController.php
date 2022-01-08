<?php

class ValidationController
{
    /**
     * Function for real time validation that is called from javascript.
     * Validates username. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateUsername() {
        $errors = User::validateUsername($_POST['username']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates email. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateEmail() {
        $errors = User::validateEmail($_POST['email']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates password. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidatePassword() {
        $errors = User::validatePassword($_POST['password']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates book name. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateBookName() {
        $errors = Book::validateBookName($_POST['bookName']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates book author. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateBookAuthor() {
        $errors = Book::validateBookAuthor($_POST['bookAuthor']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates book description. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateBookDescription() {
        $errors = Book::validateBookDescription($_POST['bookDescription']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates date read field of review. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateReviewDateRead() {
        $errors = Review::validateDateRead($_POST['dateRead']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates book comment. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateReviewComment() {
        $errors = Review::validateComment($_POST['comment']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates grade (rating) of book. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateReviewGrade() {
        $errors = Review::validateGrade($_POST['grade']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }

    /**
     * Function for real time validation that is called from javascript.
     * Validates review note. If any errors found returns them, else returns OK string.
     * @return bool - just for redirect
     */
    public function actionValidateReviewNote() {
        $errors = Review::validateNote($_POST['note']);
        echo count($errors) > 0 ? implode($errors) : 'OK';
        return True;
    }
}