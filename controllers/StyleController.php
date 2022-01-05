<?php

class StyleController
{
    public function actionChangeColorTheme() {
        setcookie("colorTheme", "", time() - 3600); // delete cookie
        setcookie("colorTheme", $_POST['colorTheme'], time()+(60*60*24*30)); // cookie for a month
        return True;
    }
}