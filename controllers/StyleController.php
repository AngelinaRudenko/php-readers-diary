<?php

class StyleController
{
    /**
     * Changes color theme by creating cookie.
     * @return bool - just for redirect
     */
    public function actionChangeColorTheme() {
        // delete cookie
        setcookie("colorTheme", "", time() - 3600);
        // set cookie for a month
        setcookie("colorTheme", $_POST['colorTheme'], time()+(60*60*24*30));
        return True;
    }
}