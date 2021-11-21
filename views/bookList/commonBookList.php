<?php include ROOT . '/views/shared/header.php'; ?>
    <link rel="stylesheet" href="/template/css/commonBookList.css" />
    <title>Reader's diary</title>
<?php include ROOT . '/views/shared/navigation.php'; ?>

    <section class="cards">
        <article class="card">
            <img class="img-book"
                 src="https://images.unsplash.com/photo-1526512340740-9217d0159da9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8dmVydGljYWx8ZW58MHx8MHx8&ixlib=rb-1.2.1&w=1000&q=80"
                 alt=""/>
            <div class="card-content">
                <p>
                    <strong class="book-name">Changes Are</strong>
                    <br />
                    <span>by Richard Russo</span>
                </p>
                <p>Rating: 1.2</p>
                <p class="book-sum">
                    Readers of all ages and walks of life have drawn inspiration and
                    empowerment from Elizabeth Gilbert’s books for years.
                </p>
                <a href="/book"><button>See more</button></a>
            </div>
        </article>
    </section>

<?php include ROOT . '/views/shared/footer.php'; ?>