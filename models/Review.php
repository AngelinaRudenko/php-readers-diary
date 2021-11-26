<?php

class Review
{
    public static function getUserBookReviews($userId)
    {
        $books = [];
        $connection = Db::createConnection();
        $sql = "SELECT `userBook`.`userBookId`, `userBook`.`bookId`, `userBook`.`dateRead`, `userBook`.`grade`, 
                `userBook`.`comment`, `userBook`.`note`, `book`.`name`, `book`.`author`, `book`.`description` 
                FROM `userBook` 
                INNER JOIN `book` 
                ON `userBook`.`bookId` = `book`.`bookId` 
                WHERE `userId` = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $userBookId, $bookId, $dateRead, $grade, $comment, $note,
                    $name, $author, $description);

                $i = 0;
                while ($row = mysqli_stmt_fetch($stmt)) {
                    $books[$i] = array(
                        "userBookId" => $userBookId,
                        "bookId" => $bookId,
                        "dateRead" => $dateRead,
                        "grade" => $grade,
                        "comment" => $comment,
                        "note" => $note,
                        "name" => $name,
                        "author" => $author,
                        "description" => $description);
                    $i++;
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($connection);
        return $books;
    }

    public static function saveReview($userId, $bookId, $dateRead, $grade, $comment, $note)
    {
        $success = FALSE;
        $connection = Db::createConnection();
        $sql = "INSERT INTO `userBook`(`userId`, `bookId`, `dateRead`, `grade`, `comment`, `note`) 
                VALUES (?,?,?,?,?,?)";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "iisiss", $userId, $bookId, $dateRead, $grade, $comment, $note);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return $success;
    }

    public static function saveReviewForNewBook($userId, $dateRead, $grade, $comment, $note, $bookName, $bookAuthor,
                                                $bookDescription, $bookCover)
    {
        $success = FALSE;
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

            mysqli_commit($connection);
            $success = TRUE;
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return $success;
    }

    public static function deleteReview($reviewId)
    {
        $connection = Db::createConnection();
        $sql = "DELETE FROM `userBook` WHERE `userBookId` = ?";
        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "i", $reviewId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($connection);
        } finally {
            mysqli_close($connection);
        }
        return TRUE;
    }

    public static function validateReview($dateRead, $grade, $comment, $note)
    {
        $errors = [];

        $now = new DateTime();
        $userEnteredDate = DateTime::createFromFormat('d/m/Y', $dateRead);

        // date read
        if (empty($dateRead)) {
            array_push($errors, "Date read is required");
        } else if ($userEnteredDate > $now) {
            array_push($errors, "Date read can't be in future");
        }

        // grade
        if (!empty($grade) && $grade < 1) {
            array_push($errors, "Grade can't be less then 1");
        } else if (!empty($grade) && $grade > 10) {
            array_push($errors, "Grade can't be more then 10");
        }

        // comment
        if (strlen($comment) > 2000) {
            array_push($errors, "Comment length must be no more than 2000 characters");
        }

        // note
        if (strlen($note) > 2000) {
            array_push($errors, "Note length must be no more than 2000 characters");
        }
        return $errors;
    }
}