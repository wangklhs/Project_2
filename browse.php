<?php
require_once ("config.php");
if (isset($_POST['title']))
    $title = $_POST['title'];
if (isset($_GET['title']))
    $title = $_GET['title'];
if (isset($_GET['content']))
    $content = $_GET['content'];
if (isset($_GET['country']))
    $country = $_GET['country'];
if (isset($_GET['cityName']))
    $cityName = $_GET['cityName'];
if (isset($_POST['content']))
    $content = $_POST['content'];
if (isset($_POST['country']))
    $country = $_POST['country'];
if (isset($_POST['cityName']))
    $cityName = $_POST['cityName'];
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT PATH FROM travelimage";
    if (isset($title) && $title != '') {
        $sql = "SELECT Title,PATH FROM travelimage";
    }
    if (isset($content) && $content != '') {
        if (strstr($sql,'WHERE'))
            $sql = $sql." AND Content=" . $content;
        else
            $sql = $sql." WHERE Content=" . $content;
    }
    if (isset($country) && $country != '') {
        if (strstr($sql,'WHERE'))
            $sql = $sql." AND Country_RegionCodeISO=" . $country;
        else
            $sql = $sql." WHERE Country_RegionCodeISO=" . $country;
    }
    if (isset($cityName) && $cityName != '') {
        $que = "SELECT GeoNameID FROM geocities WHERE AsciiName = ".$cityName;
        $result = $pdo->query($que);
        $city = $result->fetch()['GeoNameID'];
        if (strstr($sql,'WHERE'))
            $sql = $sql." AND CityCode=" . $city;
        else
            $sql = $sql." WHERE CityCode=" . $city;
    }
    $result = $pdo->query($sql);
    $paths = array();
    while ($row = $result->fetch()) {
        if (isset($title) && $title != '') {
              if (strstr($row['Title'], $title))
                  $paths[] = $row['PATH'];
        }
        else
            $paths[] = $row['PATH'];
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
    <title>Browse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
    <div id="nav-left">
        <img id="logo" src="images/sup/logo.png" width="27px">
        <a class="nav-a up" href="index.php">Home</a>
        <a id="now" name="now" class="nav-a up" href="browse.php">Browser</a>
        <a class="nav-a up" href="search.php">Search</a>
    </div>
    <div id="nav-right">
        <?php
        if (!isset($_COOKIE['Username']) || $_COOKIE['Username'] == '') {
            echo "<a class=\"nav-a\" href=\"log_in.php?location=browse.php\">Log in</a>";
        }
        else {
            echo "<ul id=\"list\">
                    <span class=\"nav-a\">My account ▼</span>
                    <li>
                        <ul>
                            <li id=\"first-child\"><a href=\"upload.php\">Upload</a></li>
                            <li id=\"second-child\"><a href=\"photo.php\">My photo</a></li>
                            <li id=\"third-child\"><a href=\"favorite.php\">My favorite</a></li>
                            <li id=\"fourth-child\"><a href=\"logout.php?location=browse.php\">Log out</a></li>
                        </ul>
                    </li>
            </ul>";
        } ?>
    </div>
</nav>
<div class="aside">
    <div class="con aside-con">
        <header class="head">
            Search by Title
        </header>
        <form method="post" action="browse.php">
            <input type="text" name="title" class="bro-input">
            <button class="search-but" type="submit"><img src="images/sup/search.png"></button>
        </form>
    </div>
    <div class="con aside-con">
        <header class="head">
            Hot Content
        </header>
        <ul class="hot">
            <li><a class="blue-a" href="browse.php?content='scenery'">Scenery</a></li>
            <li><a class="blue-a" href="browse.php?content='comic'">Comic</a></li>
            <li><a class="blue-a" href="browse.php?content='fantasy'">Fantasy</a></li>
        </ul>
    </div>
    <div class="con aside-con">
        <header class="head">
            Hot Countries
        </header>
        <ul class="hot">
            <li><a class="blue-a" href="browse.php?country='CN'">China</a></li>
            <li><a class="blue-a" href="browse.php?country='US'">United States</a></li>
            <li><a class="blue-a" href="browse.php?country='JP'">Japan</a></li>
            <li><a class="blue-a" href="browse.php?country='IT'">Italy</a></li>
        </ul>
    </div>
    <div class="con aside-con">
        <header class="head">
            Hot Cities
        </header>
        <ul class="hot">
            <li><a class="blue-a" href="browse.php?cityName='Beijing'">Beijing</a></li>
            <li><a class="blue-a" href="browse.php?cityName='Rome'">Rome</a></li>
            <li><a class="blue-a" href="browse.php?cityName='London'">London</a></li>
            <li><a class="blue-a" href="browse.php?cityName='Moscow'">Moscow</a></li>
        </ul>
    </div>
</div>
<?php
try {
    $sql = "SELECT ISO,Country_RegionName FROM geocountries_regions";
    $result = $pdo->query($sql);
    $countries = array();
    while ($row = $result->fetch()) {
        $countries[] = array($row['Country_RegionName'], $row['ISO']);
    }
} catch (PDOException $e) {
    die( $e->getMessage() );
}
function makeImg($row, $paths, $page) {
    $num = sizeof($paths) - 16 * ($page - 1) - $row * 4;
    if ($num > 4)
        $num = 4;
    for($i = 0; $i < $num; $i ++ ) {
        echo "<td><a href=\"details.php?path=".$paths[16 * ($page - 1) + 4 * $row + $i]."\"><img src='images/square-medium/".$paths[16 * ($page - 1) + 4 * $row + $i]."'></a></td>";
    }
}
?>
<div class="con bro-con">
    <header class="head">
        Filter
    </header>
    <div class="select">
        <form action="browse.php" method="post">
        <select name="content">
            <option value="" selected>Content</option>
            <option value="'scenery'">Scenery</option>
            <option value="'city'">City</option>
            <option value="'people'">People</option>
            <option value="'animal'">Animal</option>
            <option value="'building'">Building</option>
            <option value="'wonder'">Wonder</option>
            <option value="'other'">Other</option>
        </select>
        <select name="country" id="first" onChange="nextChange()">
            <?php
                if (!isset($_GET['index']))
                    echo "<option  value='' selected>Country</option>";
                else
                    echo "<option value=''>Country</option>";
                for ($i = 0; $i < sizeof($countries); $i ++) {
                    if (isset($_GET['index']) && $i == $_GET['index'] - 1)
                        echo "<option value=\"'".$countries[$i][1]."'\" selected>".$countries[$i][0]."</option>";
                    else
                        echo "<option value=\"'".$countries[$i][1]."'\">".$countries[$i][0]."</option>";
                }
            ?>
        </select>
        <select name="cityName">
            echo "<option  value='' selected>City</option>";
            <?php
            if(isset($_GET['countrySelected'])) {
                try {
                    $sql = "SELECT AsciiName FROM geocities WHERE Country_RegionCodeISO=" . $_GET['countrySelected'];
                    $result = $pdo->query($sql);
                    $cities = array();
                    while ($row = $result->fetch()) {
                        $cities[] = $row['AsciiName'];
                    }
                } catch (PDOException $e) {
                    die( $e->getMessage() );
                }
                for ($i = 0; $i < sizeof($cities); $i ++) {
                    echo "<option value=\"'".$cities[$i]."'\">".$cities[$i]."</option>";
                }
            }
            $pdo = null;
            ?>
        </select>
        <button class="submit">Filter</button>
        </form>
    </div>
    <div class="main-con">
        <?php
        if (sizeof($paths) == 0) {
            echo "<span style='color: rgb(112,128,144);font-size: 24px; position: relative; top: 50px; left: 370px;'>No search results</span>";
        }
        ?>
        <table>
            <tbody>
            <tr><?php makeImg(0, $paths, $page);?></tr>
            <tr><?php makeImg(1, $paths, $page);?></tr>
            <tr><?php makeImg(2, $paths, $page);?></tr>
            <tr><?php makeImg(3, $paths, $page);?></tr>
            </tbody>
        </table>
        <span>
            <?php
            $href = "browse.php?n=1";
            if (isset($title))
                $href .= "&title=" . $title;
            if (isset($content))
                $href .= "&content=" . $content;
            if (isset($country))
                $href .= "&country=" . $country;
            if (isset($cityName))
                $href .= "&cityName=" . $cityName;
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
            ?>
        </span>
    </div>
</div>
<footer class="root-fot">
    Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws
</footer>
<?php
echo "<a id='anchor' style='display: none' href=\"".$href."\"></a>";
?>
<script>
    function nextChange()
    {
        var first = document.getElementById("first");
        var anchor = document.getElementById("anchor");
        var option = first.children[first.selectedIndex];
        if (first.selectedIndex != 0)
            anchor.href += "&countrySelected=" + option.value + "&index=" + first.selectedIndex;
        anchor.click();
    }
</script>
</body>
</html>