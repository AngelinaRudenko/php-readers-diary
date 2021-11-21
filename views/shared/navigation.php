</head>
<body>
<div class="header-panel">
    <header>
        <a href="/commonBookList"><h1>Reader's diary</h1></a>
    </header>
    <nav>
        <?php if (isset($_SESSION['userAuthorized']) and $_SESSION['userAuthorized'] == TRUE) {
            echo '<a href="/myBooks">My books</a>';
            // TODO: account
//            echo '<a href="">Account</a>';
            echo '<a href="/logout">Log out</a>';
        }
        else {
            echo '<a href="/register">Register</a>';
            echo '<a href="/login">Log in</a>';
        }?>
    </nav>
</div>
<main>