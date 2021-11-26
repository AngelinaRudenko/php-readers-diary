<?php
if (isset($_SESSION['success'])) {
    echo '<p class="alert-block alert-success">' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);
} else if (isset($_SESSION['errors']) and is_array($_SESSION['errors'])) {
    echo '<ul class="alert-block alert-error">';
    foreach ($_SESSION['errors'] as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    unset($_SESSION['errors']);
}