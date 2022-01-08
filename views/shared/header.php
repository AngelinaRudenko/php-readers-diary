<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="/static/books_icon.ico" type="image/x-icon"/>
    <?php if (!isset($_COOKIE['colorTheme']) || empty($_COOKIE['colorTheme']) || $_COOKIE['colorTheme'] == 'lightTheme'): ?>
        <link rel="stylesheet" href="/static/css/common.css"/>
    <?php else: ?>
        <link rel="stylesheet" href="/static/css/commonDark.css"/>
    <?php endif; ?>
