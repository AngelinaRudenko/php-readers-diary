<?php

class Book
{
    /**
     * Executes sql query to find book by its id.
     * Also finds average rating of book according to reviews with grades (estimates).
     * @param $id - book id
     * @return array - book entity (book idm name, author, description, book cover image, average rating)
     */
    public static function getBook($id)
    {
        $book = [];
        $connection = Db::createConnection();
        $sql = "SELECT `bookId`, `name`, `author`, `description`, `bookCoverImage`,
                    (SELECT AVG(`grade`) FROM `userBook` 
                     WHERE `userBook`.`bookId` = `book`.`bookId` 
                     AND `userBook`.`grade` != 0 
                     AND `userBook`.`grade` IS NOT NULL) AS `avgRating`
                FROM `book` WHERE `bookId` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $bookId, $name, $author, $description, $bookCoverImage, $avgRating);
                mysqli_stmt_fetch($stmt);
                if (isset($bookId)) {
                    $book = array(
                        "bookId" => $bookId,
                        "name" => $name,
                        "author" => $author,
                        "description" => $description,
                        "bookCoverImage" => $bookCoverImage,
                        "avgRating" => isset($avgRating) ? round($avgRating, 2) : "No rating yet"
                    );
                }
            }
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return $book;
    }

    /**
     * Finds pages count for pagination.
     * @param $limit - books per page
     * @param null $minRating - optional parameter, by default is empty. Used for filtrating.
     * @param null $maxRating - optional parameter, by default is empty. Used for filtrating.
     * @return false|float - pages count
     */
    public static function getPagesCount($limit, $minRating = null, $maxRating = null)
    {
        $bookCount = 0;
        $connection = Db::createConnection();
        $sql = "SELECT COUNT(`bookId`) AS count FROM `book` ";

        if (!empty($minRating) || !empty($maxRating)) {
            $sql .= self::getWhereClauseMinMaxRating($minRating, $maxRating);
        }

        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $bookCount = $row['count'];
        }

        mysqli_close($connection);
        return ceil($bookCount/$limit);
    }

    /**
     * Executes sql query to find list of books according to pagination, filter and sorting.
     * Also finds average rating of each book according to reviews with grades (estimates).
     * @param $limit - books per page
     * @param $offset - books to skip
     * @param $orderBy - sorting parameter
     * @param null $minRating - optional parameter, by default is empty. Used for filtrating.
     * @param null $maxRating - optional parameter, by default is empty. Used for filtrating.
     * @return array - array of books
     */
    public static function getBooks($limit, $offset, $orderBy, $minRating = null, $maxRating = null) {

        $sql = "SELECT `bookId`, `name`, `author`, `description`, `bookCoverImage`,
                    (SELECT AVG(`grade`) FROM `userBook` 
                     WHERE `userBook`.`bookId` = `book`.`bookId` 
                     AND `userBook`.`grade` != 0 
                     AND `userBook`.`grade` IS NOT NULL) AS `avgRating`
                FROM `book` ";

        if (!empty($minRating) || !empty($maxRating)) {
            $sql .= self::getWhereClauseMinMaxRating($minRating, $maxRating);
        }

        if ($orderBy == 'rating') {
            $sql .= " ORDER BY (SELECT AVG(`grade`) FROM `userBook` WHERE `userBook`.`bookId` = `book`.`bookId`) DESC";
        } else {
            $sql .= " ORDER BY ".$orderBy;
        }
        $sql .= " LIMIT ".$limit." OFFSET ".$offset;

        $books = [];
        $connection = Db::createConnection();


        $result = mysqli_query($connection, $sql);

        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['description'])) {
                $row['description'] =  strlen($row['description']) < 100 ?
                    $row['description'] :
                    mb_substr($row['description'], 0, 197).'...';
            }

            $books[$i] = array(
                "bookId" => $row['bookId'],
                "name" => $row['name'],
                "author" => $row['author'],
                "description" => $row['description'],
                "bookCoverImage" => $row['bookCoverImage'],
                "avgRating" => isset($row['avgRating']) ? round($row['avgRating'], 2) : "No rating yet");
            $i++;
        }

        mysqli_close($connection);
        return $books;
    }

    /**
     * Executes sql query to find list of books that user didn't review yet.
     * (User doesn't have review of no book from that list)
     * @param $userId - user id
     * @return array - array of books
     */
    public static function getBooksNotReviewedByUser($userId) {
        $books = [];
        $connection = Db::createConnection();
        $sql = "SELECT `book`.`bookId`, `book`.`name`, `book`.`author`
                FROM `book`
                WHERE `book`.`bookId` NOT IN
                    (SELECT `userBook`.`bookId` 
                    FROM `userBook`
                    WHERE `userBook`.`userId` = ?)";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $userId);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $bookId, $name, $author);

                $i = 0;
                while (mysqli_stmt_fetch($stmt)) {
                    $books[$i] = array(
                        "bookId" => $bookId,
                        "name" => $name,
                        "author" => $author);
                    $i++;
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($connection);
        return $books;
    }

    /**
     * Validates all fields of book entity and returns errors.
     * @param $bookName - name of book
     * @param $bookAuthor - book author
     * @param $bookDescription - book description
     * @return array - array of errors
     */
    public static function validateBook($bookName, $bookAuthor, $bookDescription)
    {
        $errors = [];

        // book name
        $errors = array_merge($errors, self::validateBookName($bookName));

        // author
        $errors = array_merge($errors, self::validateBookAuthor($bookAuthor));

        // description
        $errors = array_merge($errors, self::validateBookDescription($bookDescription));

        return $errors;
    }

    /**
     * Validates book name and returns errors.
     * @param $bookName  - name of book
     * @return array - array of errors
     */
    public static function validateBookName($bookName) {
        $errors = [];
        if (empty(trim($bookName))) {
            array_push($errors, "Book name is required");
        } else if (strlen($bookName) < 1) {
            array_push($errors, "Book name length must be at least 1 character");
        } else if (strlen($bookName) > 50) {
            array_push($errors, "Book name length must be no more than 50 characters");
        }
        return $errors;
    }

    /**
     * Validates book author and returns errors.
     * @param $bookAuthor - book author
     * @return array - array of errors
     */
    public static function validateBookAuthor($bookAuthor) {
        $errors = [];

        $containsNumber = false;
        for ($i = 0; $i < strlen($bookAuthor); $i++) {
            if ( ctype_digit($bookAuthor[$i]) ) {
                $containsNumber = true;
                break;
            }
        }

        if (empty(trim($bookAuthor))) {
            array_push($errors, "Author is required");
        } else if ($containsNumber) {
            array_push($errors, "Author name should not have numbers");
        } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $bookAuthor)) {
            array_push($errors, "Author name should not have special characters");
        } else if (strlen($bookAuthor) < 6) {
            array_push($errors, "Author name length must be at least 6 characters");
        } else if (strlen($bookAuthor) > 50) {
            array_push($errors, "Author name length must be no more than 50 characters");
        }
        return $errors;
    }

    /**
     * Validates book description and returns errors.
     * @param $bookDescription - book description
     * @return array - array of errors
     */
    public static function validateBookDescription($bookDescription) {
        $errors = [];
        if (strlen($bookDescription) > 2000) {
            array_push($errors, "Description length must be no more than 2000 characters");
        }
        return $errors;
    }

    /**
     * Generates WHERE clause string for sql query according to min and max rating parameters.
     * At least one of parameters (min rating or max rating) must be not null.
     * @param $minRating - optional parameter, by default is empty. Used for filtrating.
     * @param $maxRating - optional parameter, by default is empty. Used for filtrating.
     * @return string - WHERE clause for sql query
     */
    private static function getWhereClauseMinMaxRating($minRating, $maxRating) {
        $sql = "WHERE (SELECT AVG(`grade`) FROM `userBook` WHERE `userBook`.`bookId` = `book`.`bookId`) ";

        if (!empty($minRating) && !empty($maxRating)) {
            return  $sql."BETWEEN ".$minRating." AND ".$maxRating;
        } elseif (!empty($minRating)) {
            return $sql.">= ".$minRating;
        } else /* (!empty($maxRating)) */ {
            return $sql."<= ".$maxRating;
        }
    }
}