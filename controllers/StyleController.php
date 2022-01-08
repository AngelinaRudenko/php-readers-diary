<?php

class StyleController
{
    /**
     * Changes color theme by creating cookie.
     * @return bool - just for redirect
     */
    public function actionChangeColorTheme() {

        unset($_SESSION["colorTheme"]);
        $_SESSION["colorTheme"] = $_POST['colorTheme'];

        return True;
    }
}