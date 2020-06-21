<?php
require_once ("config.php");
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['del'])) {
        $sql = "DELETE FROM travelimagefavor WHERE UID = '".$_COOKIE['Username']."' AND ImageID = '".$_GET['del']."'";
        $pdo->query($sql);
    }
    $sql = "SELECT ImageID FROM travelimagefavor WHERE UID = '".$_COOKIE["Username"]."'";
    $result = $pdo->query($sql);
    $titles = array();
    $description = array();
    $paths = array();
    $ids = array();
    while ($row = $result->fetch()) {
        $sql = "SELECT Title,Description,PATH FROM travelimage WHERE ImageID = '".$row['ImageID']."'";
        $res = $pdo->query($sql);
        if ($rec = $res->fetch()) {
            $ids[] = $row['ImageID'];
            $titles[] = $rec['Title'];
            $description[] = $rec['Description'];
            $paths[] = $rec['PATH'];
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
    <title>My favorite</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <div id="nav-left">
        <img id="logo" src="images/sup/logo.png" width="27px">
        <a class="nav-a up" href="index.php">Home</a>
        <a class="nav-a up" href="browse.php">Browser</a>
        <a class="nav-a up" href="search.php">Search</a>
    </div>
    <div id="nav-right">
        <?php
        if (!isset($_COOKIE['Username']) || $_COOKIE['Username'] == '') {
            echo "<a class=\"nav-a\" href=\"log_in.php\">Log in</a>";
        }
        else {
            echo "<ul id=\"list\">
                    <span class=\"nav-a\">My account ▼</span>
                    <li>
                        <ul>
                            <li id=\"first-child\"><a href=\"upload.php\">Upload</a></li>
                            <li id=\"second-child\"><a href=\"photo.php\">My photo</a></li>
                            <li id=\"third-child\"><a href=\"favorite.php\">My favorite</a></li>
                            <li id=\"fourth-child\"><a href=\"logout.php?location=index.php\">Log out</a></li>
                        </ul>
                    </li>
            </ul>";
        } ?>
    </div>
</nav>
<div class="con">
    <header class="head">
        My favorite
    </header>
    <?php
    function makeImg($path, $des, $title, $id) {
        echo "<a href=\"details.php?path=".$path."\"><img src=\"images/square-medium/".$path."\"></a>
                  <div class=\"abs-right\">
                  <h3>".$title."</h3>";
            if (mb_strlen($des) == 0) {
                $des = "No description";
            }
        if (mb_strlen($des) > 130) {
            $des = substr($des,0, 130)."...";
        }
            echo "<p>".$des."</p>";
            echo "<button class='red'>
                    <img src=\"images/sup/delete.png\">
                    <a href=\"favorite.php?del=".$id."\"><span>Delete</span></a>
                </button></div>";
    }
    if (sizeof($paths) == 0) {
        echo "<div style='padding: 80px 0 80px 500px'>
                <span style='color: rgb(112,128,144); font-size: 24px;'>You haven't collected any photo</span>
              </div>";
    }
    else {
        $num = sizeof($paths) - 16 * ($page - 1);
        if ($num > 16)
            $num = 16;
        for ($i = 0; $i < $num - 1; $i ++) {
            echo "<div class=\"details\">";
            makeImg($paths[16 * ($page - 1) + $i], $description[16 * ($page - 1) + $i], $titles[16 * ($page - 1) + $i], $ids[16 * ($page - 1) + $i]);
            echo "</div>";
        }
        echo "<div class=\"details last\">";
        makeImg($paths[16 * ($page - 1) + $num - 1], $description[16 * ($page - 1) + $num - 1], $titles[16 * ($page - 1) + $num - 1], $ids[16 * ($page - 1) + $num - 1]);
        echo "<span>";
        $href = "favorite.php?n=1";
        if (sizeof($paths) >= 1){
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
