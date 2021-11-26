<?php

class Book
{
    public static function getBook($id)
    {
        $books = [];
        $connection = Db::createConnection();
        $sql = "SELECT `bookId`, `name`, `author`, `description`, `bookCoverImage` FROM `book` WHERE `bookId` = ?";

        mysqli_begin_transaction($connection);
        try {
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                unset($_SESSION['book']);
                mysqli_stmt_bind_result($stmt, $bookId, $name, $author, $description, $bookCoverImage);
                mysqli_stmt_fetch($stmt);
                if (isset($bookId)) {
                    $books = array(
                        "bookId" => $bookId,
                        "name" => $name,
                        "author" => $author,
                        "description" => $description,
                        "bookCoverImage" => $bookCoverImage
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
        return $books;
    }

    public static function getCommonBooks()
    {
        $books = [];
        $connection = Db::createConnection();
        $sql = "SELECT `bookId`, `name`, `author`, `description` , `bookCoverImage` FROM `book`";

        $result = mysqli_query($connection, $sql);

        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $books[$i] = array(
                "bookId" => $row['bookId'],
                "name" => $row['name'],
                "author" => $row['author'],
                "description" => $row['description'],
                "bookCoverImage" => $row['bookCoverImage']);
            $i++;
        }

        mysqli_close($connection);
        return $books;
    }

    public static function validateBook($bookName, $bookAuthor, $bookDescription)
    {
        $errors = [];

        // book name
        if (empty(trim($bookName))) {
            array_push($errors, "Book name is required");
        } else if (strlen($bookName) < 1) {
            array_push($errors, "Book name length must be at least 1 character");
        } else if (strlen($bookName) > 50) {
            array_push($errors, "Book name length must be no more than 50 characters");
        }

        // author
        if (empty(trim($bookAuthor))) {
            array_push($errors, "Author is required");
        } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $bookAuthor)) {
            array_push($errors, "Author name should not have special characters");
        } else if (strlen($bookAuthor) < 6) {
            array_push($errors, "Author name length must be at least 6 characters");
        } else if (strlen($bookAuthor) > 50) {
            array_push($errors, "Author name length must be no more than 50 characters");
        }

        // description
        if (strlen($bookDescription) > 2000) {
            array_push($errors, "Description length must be no more than 2000 characters");
        }

        return $errors;
    }
}