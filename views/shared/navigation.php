</head>
<body>
<div class="header-panel">
    <header>
        <a href="/commonBookList"><h1>Reader's diary</h1></a>
    </header>
    <nav>
        <?php if (isset($_SESSION['userAuthorized']) and $_SESSION['userAuthorized']["isAuthorized"] == TRUE) : ?>
            <a href="/myBooks">My books</a>
            <a href="/logout">Log out</a>
        <?php else : ?>
            <a href="/register">Register</a>
            <a href="/login">Log in</a>
        <?php endif; ?>
    </nav>
</div>
<main>