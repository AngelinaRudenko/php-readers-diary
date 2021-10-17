<?php
    $connect = mysqli_connect('localhost:3307', 'root', 'root', 'readersdiary');

    if (!$connect) {
        die("Error occurred while connecting to database: " . mysqli_connect_error());
    }