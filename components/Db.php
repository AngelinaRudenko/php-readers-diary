<?php

class Db
{
    /**
     * Creates connection to database
     * @return mysqli|void - connection to database
     */
    public static function createConnection()
    {
        $paramsPath = ROOT . '/config/DatabaseParameters.php';
        $params = include($paramsPath);

        // Shows all sql errors. Commented for production deploy.
         mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $connect = mysqli_connect($params['hostname'], $params['username'], $params['password'], $params['database']);

        if (!$connect) {
            die("Error occurred while connecting to database: " . mysqli_connect_error());
        }

        return $connect;
    }
}