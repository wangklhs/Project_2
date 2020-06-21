<?php
require_once ("config.php");
if (isset($_POST['title'])) {
    if ($_POST['way'] == 1)
        $title = $_POST['title'];
}
if (isset($_GET['title']))
    $title = $_GET['title'];
if (isset($_POST['des'])) {
    if ($_POST['way'] == 2)
        $des = $_POST['des'];
}
if (isset($_GET['des']))
    $des = $_GET['des'];
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT Title,Description,PATH FROM travelimage";
    $result = $pdo->query($sql);
    $paths = array();
    $description = array();
    $titles = array();
    while ($row = $result->fetch()) {
        if (isset($title) && $title != '') {
            if (strstr($row['Title'], $title)) {
                $titles[] = $row['Title'];
                $description[] = $row['Description'];
                $paths[] = $row['PATH'];
            }
        }
        else {
            if (isset($des) && $des != '') {
                if (strstr($row['Description'], $des)) {
                    $titles[] = $row['Title'];
                    $description[] = $row['Description'];
                    $paths[] = $row['PATH'];
                }
            }
            else {
                $titles[] = $row['Title'];
                $description[] = $row['Description'];
                $paths[] = $row['PATH'];
            }
        }
    }
} catch (PDOException $e) {
    die( $e->getMessage() );
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
else $page = 1;
$pages = intval(sizeof($paths) / 16) + 1;
if ($pages == 6)
    $pages --;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <div id="nav-left">
        <img id="logo" src="images/sup/logo.png" width="27px">
        <a class="nav-a up" href="index.php">Home</a>
        <a class="nav-a up" href="browse.php">Browser</a>
        <a id="now" name="now" class="nav-a up" href="search.php">Search</a>
    </div>
    <div id="nav-right">
        <?php
        if (!isset($_COOKIE['Username']) || $_COOKIE['Username'] == '') {
            echo "<a class=\"nav-a\" href=\"log_in.php?location=search.php\">Log in</a>";
        }
        else {
            echo "<ul id=\"list\">
                    <span class=\"nav-a\">My account ▼</span>
                    <li>
                        <ul>
                            <li id=\"first-child\"><a href=\"upload.php\">Upload</a></li>
                            <li id=\"second-child\"><a href=\"photo.php\">My photo</a></li>
                            <li id=\"third-child\"><a href=\"favorite.php\">My favorite</a></li>
                            <li id=\"fourth-child\"><a href=\"logout.php?location=search.php\">Log out</a></li>
                        </ul>
                    </li>
            </ul>";
        } ?>
    </div>
</nav>
<div class="con search">
    <header class="head">
        Search
    </header>
    <form class="search-form" action="search.php" method="post">
        <input type="radio" name="way" value="1" checked>Filter by Title:
        <br>
        <input type="text" name="title" class="title-input">
        <p></p>
        <input type="radio" name="way" value="2">Filter by Description:
        <br>
        <textarea name="des" class="des-input"></textarea>
        <p></p>
        <button type="submit" class="submit">Filter</button>
    </form>
</div>
<div class="con">
    <header class="head">
        <?php
        if ((isset($title) && $title != '' )|| (isset($des) && $des != ''))
            echo "Search result";
        else
            echo "Browse";
        function makeImg($path, $des, $title) {
            echo "<a href=\"details.php?path=".$path."\"><img src=\"images/square-medium/".$path."\"></a>
                  <div class=\"abs-right\">
                  <h3>".$title."</h3>";
            if (mb_strlen($des) == 0) {
                $des = "No description";
            }
            if (mb_strlen($des > 130)) {
                $des = substr($des,0, 130)."...";
            }
            echo "<p>".$des."</p></div>";
        }
        ?>
    </header>
    <?php
    if (sizeof($paths) == 0) {
        echo "<div style='padding: 80px 0 80px 550px'>
                <span style='color: rgb(112,128,144); font-size: 24px;'>No search results</span>
              </div>";
    }
    else {
        $num = sizeof($paths) - 16 * ($page - 1);
        if ($num > 16)
            $num = 16;
        for ($i = 0; $i < $num - 1; $i ++) {
            echo "<div class=\"details\">";
            makeImg($paths[16 * ($page - 1) + $i], $description[16 * ($page - 1) + $i], $titles[16 * ($page - 1) + $i]);
            echo "</div>";
        }
        echo "<div class=\"details last\">";
        makeImg($paths[16 * ($page - 1) + $num - 1], $description[16 * ($page - 1) + $num - 1], $titles[16 * ($page - 1) + $num - 1]);
        echo "<span>";
        $href = "search.php?n=1";
        if (isset($title))
            $href .= "&title=" . $title;
        if (isset($des))
            $href .= "&des=" . $des;
        if (sizeof($paths) > 0){
            if ($page == 1)
                echo "<a class='page' href=\"".$href."&page=1\"><<</a> &nbsp &nbsp";
            else
                echo "<a class='page' href=\"".$href."&page=".($page - 1)."\"><<</a> &nbsp &nbsp";
            for ($i = 1; $i <= $pages; $i ++) {
                if ($i == $page)
                    echo "<a class='page' id='current-page' href=\"".$href."&page=".$i."\">".$i."</a>";
                else
                    echo "<a class='page' href=\"".$href."&page=".$i."\">".$i."</a>";
                echo "&nbsp &nbsp";
            }
            if ($page == $pages)
                echo "<a class='page' href=\"".$href."&page=".$page."\">>></a> &nbsp &nbsp";
            else
                echo "<a class='page' href=\"".$href."&page=".($page + 1)."\">>></a>";
        }
        echo "</span></div>";
    }
    ?>
</div>
<footer class="root-fot">
    Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws
</footer>
</body>
</html>
