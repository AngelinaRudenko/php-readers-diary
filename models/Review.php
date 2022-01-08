<?php

class Review
{
    /**
     * Finds pages count for pagination.
     * @param $userId - user id
     * @param $limit - reviews per page
     * @return false|float - pages count
     */
    public static function getPagesCount($userId, $limit)
    {
        $reviewCount = 0;
        $connection = Db::createConnection();
        $sql = "SELECT COUNT(`userBook`.`bookId`) AS count
                FROM `userBook` 
                INNER JOIN `book` 
                ON `userBook`.`bookId` = `book`.`bookId` 
                WHERE `userId` = " . $userId;

        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $reviewCount = $row['count'];
        }

        mysqli_close($connection);
        return ceil($reviewCount / $limit);
    }

    /**
     * Executes sql query to find user reviews according to pagination and sorting.
     * @param $userId - user id
     * @param $limit - reviews per page
     * @param $offset - reviews to skip
     * @param $orderBy - sorting parameter
     * @return array - array of reviews
     */
    public static function getUserReviews($userId, $limit, $offset, $orderBy)
    {
        $sql = "SELECT `userBook`.`userBookId`, `userBook`.`bookId`, `userBook`.`dateRead`, `userBook`.`grade`, 
                `userBook`.`comment`, `userBook`.`note`, `book`.`name`, `book`.`author` 
                FROM `userBook` 
                INNER JOIN `book` 
                ON `userBook`.`bookId` = `book`.`bookId` 
                WHERE `userId` = ? ";
        if ($orderBy == 'name' || $orderBy == 'author') {
            $sql .= "ORDER BY book." . $orderBy;
        } elseif ($orderBy == 'rating') {
            $sql .= "ORDER BY (SELECT AVG(`grade`) FROM `userBook` WHERE `userBook`.`bookId` = `book`.`bookId`) DESC";
        } elseif ($orderBy == 'grade') {
            $sql .= "ORDER BY userBook.grade DESC";
        } else {
            $sql .= "ORDER BY userBook.dateRead DESC";
        }
        $sql .= " LIMIT " . $limit . " OFFSET " . $offset;

        $books = [];
        $connection = Db::createConnection();


        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $userBookId, $bookId, $dateRead, $grade, $comment, $note,
                    $name, $author);

                $i = 0;
                while ($row = mysqli_stmt_fetch($stmt)) {
                    $books[$i] = array(
                        "userBookId" => $userBookId,
                        "bookId" => $bookId,
                        "dateRead" => $dateRead,
                        "grade" => empty($grade) || $grade == 0 ? "-" : $grade,
                        "comment" => empty($comment) ? "-" : $comment,
                        "note" => empty($note) ? "-" : $note,
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
     * Executes sql query to find user review by book id.
     * @param $userId - user id
     * @param $bookId - book id
     * @return array - review entity
     */
    public static function getUserReviewByBookId($userId, $bookId)
    {
        $connection = Db::createConnection();
        $sql = "SELECT `userBookId`, `dateRead`, `grade`, `comment`, `note` 
                FROM `userBook` WHERE `userId` = ? AND `bookId` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $userId, $bookId);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $userBookId, $dateRead, $grade, $comment, $note);
                mysqli_stmt_fetch($stmt);
                if (isset($userBookId) && !empty($userBookId)) {
                    $review = array(
                        "userBookId" => $userBookId,
                        "dateRead" => $dateRead,
                        "grade" => empty($grade) || $grade == 0 ? "-" : $grade,
                        "comment" => empty($comment) ? "-" : $comment,
                        "note" => empty($note) ? "-" : $note
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
        return isset($review) ? $review : [];
    }

    /**
     * Executes sql query to find user review by id.
     * @param $reviewId - review id
     * @return array - review entity
     */
    public static function getUserBookReviewById($reviewId)
    {
        $review = [];
        $connection = Db::createConnection();
        $sql = "SELECT `bookId`, `dateRead`, `grade`, `comment`, `note` 
                FROM `userBook` WHERE `userBookId` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "i", $reviewId);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $bookId, $dateRead, $grade, $comment, $note);
                mysqli_stmt_fetch($stmt);
                if (isset($dateRead)) {
                    $review = array(
                        "bookId" => $bookId,
                        "dateRead" => $dateRead,
                        "grade" => $grade,
                        "comment" => $comment,
                        "note" => $note
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
        return $review;
    }

    /**
     * Executes sql query to find book comments.
     * If user is authorized, his comment will be shown in other page section, that's why sql query excludes user comment.
     * @param $bookId - book id
     * @param null $userId - user id. Optional parameter.
     * @return array - array of comments
     */
    public static function getComments($bookId, $userId = null)
    {
        $comments = [];
        $connection = Db::createConnection();

        $sql = $userId != null ? "SELECT `username`, `comment` FROM `userBook`
                INNER JOIN `user`
                ON `user`.`userId` = `userBook`.`userId`
                WHERE `bookId` = ?
                AND `userBook`.`userId` != ?
                AND `comment` IS NOT NULL
                AND `comment` != \"\"" :
            "SELECT `username`, `comment` FROM `userBook`
                INNER JOIN `user`
                ON `user`.`userId` = `userBook`.`userId`
                WHERE `bookId` = ?
                AND `comment` IS NOT NULL
                AND `comment` != \"\"";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            if ($userId != null) {
                mysqli_stmt_bind_param($stmt, "ii", $bookId, $userId);
            } else {
                mysqli_stmt_bind_param($stmt, "i", $bookId);
            }

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $username, $comment);

                $i = 0;
                while (mysqli_stmt_fetch($stmt)) {
                    $comments[$i] = array(
                        "username" => $username,
                        "comment" => $comment);
                    $i++;
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($connection);
        return $comments;
    }

    /**
     * Executes sql query that saves the review of existing book.
     * @param $userId - user id
     * @param $bookId - book id
     * @param $dateRead - date read
     * @param $grade - grade (rating/estimation)
     * @param $comment - comment
     * @param $note - note
     * @return int|string - review id or -1 value if something went wrong
     */
    public static function saveReview($userId, $bookId, $dateRead, $grade, $comment, $note)
    {
        $connection = Db::createConnection();
        $sql = "INSERT INTO `userBook`(`userId`, `bookId`, `dateRead`, `grade`, `comment`, `note`) 
                VALUES (?,?,?,?,?,?)";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "iisiss", $userId, $bookId, $dateRead, $grade, $comment, $note);
            mysqli_stmt_execute($stmt);
            $reviewId = mysqli_insert_id($connection);
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return isset($reviewId) ? $reviewId : -1;
    }

    /**
     * Executes sql query that updates review.
     * @param $reviewId - review id
     * @param $dateRead - date read
     * @param $grade - grade (rating/estimation)
     * @param $comment - comment
     * @param $note - note
     * @return mixed - original review id
     */
    public static function updateReview($reviewId, $dateRead, $grade, $comment, $note)
    {
        $connection = Db::createConnection();
        $sql = "UPDATE `userBook` SET `dateRead` = ?,`grade` = ?,`comment` = ?,`note` = ?
                WHERE `userBookId` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "sissi", $dateRead, $grade, $comment, $note, $reviewId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return $reviewId;
    }

    /**
     * Executes sql query that saves new book and its review.
     * @param $userId - user id
     * @param $dateRead - date read
     * @param $grade - grade (rating/estimation)
     * @param $comment - comment
     * @param $note - note
     * @param $bookName - book name
     * @param $bookAuthor - book author
     * @param $bookDescription - book description
     * @param $bookCover - book cover image
     * @return array - book id and review id
     */
    public static function saveReviewForNewBook($userId, $dateRead, $grade, $comment, $note, $bookName, $bookAuthor,
                                                $bookDescription, $bookCover)
    {
        $connection = Db::createConnection();

        $sqlInsertBook = "INSERT INTO `book`(`name`, `author`, `description`, `bookCoverImage`) 
                    VALUES (?,?,?,?)";
        $sqlInsertReview = "INSERT INTO `userBook`(`userId`, `bookId`, `dateRead`, `grade`, `comment`, `note`) 
                VALUES (?,?,?,?,?,?)";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sqlInsertBook);
            mysqli_stmt_bind_param($stmt, "ssss", $bookName, $bookAuthor, $bookDescription, $bookCover);
            mysqli_stmt_execute($stmt);

            $bookId = mysqli_insert_id($connection);

            $stmt = mysqli_prepare($connection, $sqlInsertReview);
            mysqli_stmt_bind_param($stmt, "ississ", $userId, $bookId, $dateRead, $grade, $comment, $note);
            mysqli_stmt_execute($stmt);

            $reviewId = mysqli_insert_id($connection);
            mysqli_commit($connection);

            $result = array(
                "bookId" => $bookId,
                "reviewId" => $reviewId
            );
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }

        return isset($result) ? $result : [];
    }

    /**
     * Deletes review.
     * If review is the last one for book, deletes book too.
     * Also deletes all associated images with book deleted.
     * @param $reviewId - review id
     * @return bool
     */
    public static function deleteReview($reviewId)
    {
        $connection = Db::createConnection();

        mysqli_begin_transaction($connection);
        try {
            // find bookId
            $review = self::getUserBookReviewById($reviewId);
            $bookId = $review['bookId'];

            // find if there is any more reviews for this book
            $sql = "SELECT COUNT(`userBookId`) AS `count` FROM `userBook` WHERE `bookId` = " . $bookId;
            $result = mysqli_query($connection, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $count = $row['count'];
            }

            // delete review
            $sql = "DELETE FROM `userBook` WHERE `userBookId` = " . $reviewId;
            mysqli_query($connection, $sql);


            // delete book if no more review exists
            if (isset($count) && $count == 1) {
                $book = Book::getBook($bookId);

                $sql = "DELETE FROM `book` WHERE `bookId` = " . $bookId;
                mysqli_query($connection, $sql);

                $bookCoverImage = $book["bookCoverImage"];

                if (!empty($book["bookCoverImage"])) {
                    $fileName = basename($bookCoverImage);
                    unlink('uploads/' . $bookCoverImage);
                    unlink('uploads/cachedBookCoverPictures/bookPage_' . $fileName);
                    unlink('uploads/cachedBookCoverPictures/bookListPage_' . $fileName);
                }
            }
            mysqli_commit($connection);

        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return TRUE;
    }

    /**
     * Validates all fields of review entity and returns errors.
     * @param $dateRead - date read
     * @param $grade - grade (rating/estimation)
     * @param $comment - comment
     * @param $note - note
     * @return array - array of errors
     */
    public static function validateReview($dateRead, $grade, $comment, $note)
    {
        $errors = [];

        // date read
        $errors = array_merge($errors, self::validateDateRead($dateRead));

        // grade
        $errors = array_merge($errors, self::validateGrade($grade));

        // comment
        $errors = array_merge($errors, self::validateComment($comment));

        // note
        $errors = array_merge($errors, self::validateNote($note));

        return $errors;
    }

    /**
     * Validates date read and returns errors.
     * @param $dateRead - date read
     * @return array - array of errors
     */
    public static function validateDateRead($dateRead)
    {
        $errors = [];
        $now = new DateTime();
        $userEnteredDate = DateTime::createFromFormat('d/m/Y', $dateRead);

        if (empty($dateRead)) {
            array_push($errors, "Date read is required");
        } else if ($userEnteredDate > $now) {
            array_push($errors, "Date read can't be in future");
        }
        return $errors;
    }

    /**
     * Validates grade (rating/estimation) and returns errors.
     * @param $grade - grade (rating/estimation)
     * @return array - array of errors
     */
    public static function validateGrade($grade)
    {
        $errors = [];
        if (!empty($grade) && $grade < 1) {
            array_push($errors, "Grade can't be less then 1");
        } else if (!empty($grade) && $grade > 10) {
            array_push($errors, "Grade can't be more then 10");
        }
        return $errors;
    }

    /**
     * Validates comment and returns errors.
     * @param $comment - comment
     * @return array - array of errors
     */
    public static function validateComment($comment)
    {
        $errors = [];
        if (!empty($comment) && strlen($comment) > 2000) {
            array_push($errors, "Comment length must be no more than 2000 characters");
        }
        return $errors;
    }

    /**
     * Validates note and returns errors.
     * @param $note - note
     * @return array - array of errors
     */
    public static function validateNote($note)
    {
        $errors = [];
        if (!empty($note) && strlen($note) > 2000) {
            array_push($errors, "Note length must be no more than 2000 characters");
        }
        return $errors;
    }
}