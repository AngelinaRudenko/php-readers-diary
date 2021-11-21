<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/book.css" />
    <title>My books</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <div class="content">
        <section class="book">
            <img
                class="img-book"
                src="https://images.unsplash.com/photo-1526512340740-9217d0159da9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8dmVydGljYWx8ZW58MHx8MHx8&ixlib=rb-1.2.1&w=1000&q=80"
                alt=""
            />
            <div class="card-content">
                <p>
                    <strong class="book-name">Changes Are</strong>
                    <span>by Richard Russo</span>
                </p>
                <p>Rating: 1.2</p>
                <p class="book-sum">
                    Readers of all ages and walks of life have drawn inspiration and
                    empowerment from Elizabeth Gilbert’s books for years.
                </p>
                <?php if (isset($_SESSION['userAuthorized']) and $_SESSION['userAuthorized'] == TRUE) {
                    echo '<button>Mark as read</button>';
                }?>
            </div>
        </section>
        <?php if (isset($_SESSION['userAuthorized']) and $_SESSION['userAuthorized'] == TRUE) {
            echo '<section class="feedback">';
            echo '<h2>My feedback</h2>';
            // TODO: feedback
            echo '</section>';
        }?>
        <section class="comments">
            <h2>Comments</h2>
            <div class="comment">
                <p class="name">Name</p>
                <p class="text">Comment</p>
            </div>
            <div class="comment">
                <p class="name">Name</p>
                <p class="text">Comment</p>
            </div>
            <div class="comment">
                <p class="name">Name</p>
                <p class="text">Comment</p>
            </div>
        </section>
    </div>

<?php include ROOT . '/views/shared/footer.php'; ?>