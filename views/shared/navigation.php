</head>
<body>
<div class="header-panel">
    <header>
        <a href="/"><h1>Reader's diary</h1></a>
    </header>
    <nav>
        <select id="colorTheme" class="nav-select">
            <option value="lightTheme"
                <?php
                    if (!isset($_COOKIE['colorTheme']) || empty($_COOKIE['colorTheme']) || $_COOKIE['colorTheme'] == 'lightTheme') {
                        echo 'selected';
                    }
                ?>
            >Light theme</option>
            <option value="darkTheme"
                <?php
                    if (!empty($_COOKIE['colorTheme']) && $_COOKIE['colorTheme'] == 'darkTheme') {
                        echo 'selected';
                    }
                ?>
            >Dark theme</option>
        </select>
        <?php if (!empty($_SESSION['userAuthorized']) && $_SESSION['userAuthorized']["isAuthorized"]) : ?>
            <a href="/reviews">My reviews</a>
            <a href="/logout">Log out</a>
        <?php else : ?>
            <a href="/register">Register</a>
            <a href="/login">Log in</a>
        <?php endif; ?>
    </nav>
</div>
<main>