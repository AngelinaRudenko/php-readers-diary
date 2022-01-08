<div>
    <input id="currentPage" type="hidden" value="<?= empty($page) ? '1' : $page ?>">
    <?php
    if (empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/') {
        for ($i = 0; $i < $pagesCount; $i++) {
            if ($i == $page - 1) {
                echo "<span class=\"currentPage\"> " . ($i + 1) . " </span>";
            } else {
                echo " <a href=\"bookList/" . ($i + 1) . "\">" . ($i + 1) . "</a> ";
            }
        }
    } else {
        $arr = explode('/', $_SERVER['REQUEST_URI']);
        for ($i = 0; $i < $pagesCount; $i++) {
            if ($i == $page - 1) {
                echo "<span class=\"currentPage\"> " . ($i + 1) . " </span>";
            } else {
                $arr[2] = $i + 1;
                echo " <a href=\"" . implode('/', $arr) . "\">" . ($i + 1) . "</a> ";
            }
        }
    }
    ?>
</div>