<?php
require_once ("config.php");
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['id'])) {
        $modify = 1;
        $id = $_GET['id'];
        $sql = "SELECT * FROM travelimage WHERE ImageID = '".$id."'";
        $result = $pdo->query($sql);
        $row = $result->fetch();
        $path = $row['PATH'];
        $title = $row['Title'];
        $des = $row['Description'];
        $content = $row['Content'];
        $countryISO = $row['Country_RegionCodeISO'];
        if ($countryISO == "'C")
            $countryISO = 'CA';
        $cityCode = $row['CityCode'];
        $sql = "SELECT AsciiName FROM geocities WHERE GeoNameID = '".$cityCode."'";
        $result = $pdo->query($sql);
        $city = $result->fetch()['AsciiName'];
    }
    $sql = "SELECT ISO,Country_RegionName FROM geocountries_regions";
    $result = $pdo->query($sql);
    $countries = array();
    while ($row = $result->fetch()) {
        $countries[] = array($row['Country_RegionName'], $row['ISO']);
    }
} catch (PDOException $e) {
    die( $e->getMessage() );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
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
            echo "<a class=\"nav-a\" href=\"log_in.php?\">Log in</a>";
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
        <?php
        if (isset($modify))
            echo "Modify";
        else
            echo "Upload";?>
    </header>
    <?php
        if (isset($modify))
            echo "<form class=\"search-form upload-form\" method=\"POST\" action=\"uploadAction.php?type=2&id=".$id."\">";
        else {
            if (isset($_GET['src']))
                echo  "<form id='form' class=\"search-form upload-form\" method=\"POST\" action=\"uploadAction.php?type=1&path=".$_GET['src']."\">";
             else
                 echo "<form id='form' class=\"search-form upload-form\" method=\"POST\" action=\"uploadAction.php?type=1\">";
        }
        if (!isset($path)) {
            if (isset($_GET['src']))
                echo "<img src=\"images/large/".$_GET['src']."\" id=\"img\">";
            else
                echo "<img src=\"images/sup/initial.png\" id=\"img\">";
            echo "<input type=\"file\" name=\"file\" id=\"file\">";
        }
        else
            echo "<img src=\"images/large/".$path."\" id=\"img\">";
        ?>
        <div class="select" style="border: none; padding: 40px 0 10px 0">
        <select name="content" required>
            <?php
            $index = 0;
            if (isset($content)) {
                switch ($content) {
                    case "scenery" : $index = 1; break;
                    case "city" : $index = 2; break;
                    case "people" : $index = 3; break;
                    case "animal" : $index = 4; break;
                    case "building" : $index = 5; break;
                    case "wonder" : $index = 6; break;
                    case "other" : $index = 7; break;
                }
            }
            if ($index == 0)
                echo "<option value=\"\" selected>Content</option>";
            else
                echo "<option value=\"\">Content</option>";
            if ($index == 1)
                echo "<option value=\"scenery\" selected>Scenery</option>";
            else
                echo "<option value=\"scenery\">Scenery</option>";
            if ($index == 2)
                echo "<option value=\"city\" selected>City</option>";
            else
                echo "<option value=\"city\">City</option>";
            if ($index == 3)
                echo "<option value=\"people\" selected>People</option>";
            else
                echo "<option value=\"people\">People</option>";
            if ($index == 4)
                echo "<option value=\"animal\" selected>Animal</option>";
            else
                echo "<option value=\"animal\">Animal</option>";
            if ($index == 5)
                echo "<option value=\"building\" selected>Building</option>";
            else
                echo "<option value=\"building\">Building</option>";
            if ($index == 6)
                echo "<option value=\"wonder\" selected>Wonder</option>";
            else
                echo "<option value=\"wonder\">Wonder</option>";
            if ($index == 7)
                echo "<option value=\"other\" selected>Other</option>";
            else
                echo "<option value=\"other\">Other</option>";
            ?>
        </select>
        <select name="country" id="first" onChange="nextChange()" required>
            <?php
            if (!isset($_GET['index']))
                echo "<option  value='' selected>Country</option>";
            else
                echo "<option value=''>Country</option>";
            for ($i = 0; $i < sizeof($countries); $i ++) {
                if (isset($_GET['index']) && $i == $_GET['index'] - 1)
                    echo "<option value=\"'".$countries[$i][1]."'\" selected>".$countries[$i][0]."</option>";
                else {
                    if (isset($countryISO)) {
                        if ($countryISO == $countries[$i][1])
                            echo "<option value=\"'".$countries[$i][1]."'\" selected>".$countries[$i][0]."</option>";
                        else
                            echo "<option value=\"'".$countries[$i][1]."'\">".$countries[$i][0]."</option>";
                    }
                    else
                        echo "<option value=\"'".$countries[$i][1]."'\">".$countries[$i][0]."</option>";
                }
            }
            ?>
        </select>
        <select name="cityName" required>
            echo "<option  value='' selected>City</option>";
            <?php
            if(isset($_GET['countrySelected']) || isset($countryISO)) {
                if (isset($countryISO))
                    $iso = "'" . $countryISO . "'";
                else
                    $iso = $_GET['countrySelected'];
                try {
                    $sql = "SELECT AsciiName FROM geocities WHERE Country_RegionCodeISO=" . $iso;
                    $result = $pdo->query($sql);
                    $cities = array();
                    while ($row = $result->fetch()) {
                        $cities[] = $row['AsciiName'];
                    }
                } catch (PDOException $e) {
                    die( $e->getMessage() );
                }
                for ($i = 0; $i < sizeof($cities); $i ++) {
                    if (isset($city)) {
                        if ($city == $cities[$i])
                            echo "<option value=\"'".$cities[$i]."'\" selected>".$cities[$i]."</option>";
                        else
                            echo "<option value=\"'".$cities[$i]."'\">".$cities[$i]."</option>";
                    }
                    else
                        echo "<option value=\"'".$cities[$i]."'\">".$cities[$i]."</option>";
                }
            }
            $pdo = null;
            ?>
        </select>
        </div>
        <p>图片标题:</p>
        <?php
        if (!isset($title))
            echo "<input type=\"text\" name=\"title\" class=\"title-input\" required>";
        else
            echo "<input type=\"text\" name=\"title\" class=\"title-input\" value='".$title."' required>";
            ?>
        <p>图片描述:</p>
        <?php
        if (!isset($des))
            echo "<textarea name=\"des\" class=\"des-input\" required></textarea>";
        else
            echo "<textarea name=\"des\" class=\"des-input\" required>".$des."</textarea>"
        ?>
        <p></p>

        <button type="submit" class="submit">
            <?php
            if (isset($modify))
                echo "Modify";
            else
                echo "Upload"; ?></button>
    </form>
</div>
<footer class="root-fot">
    Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws
</footer>
<?php
if (isset($path))
    echo "<a id='anchor' style='display: none' href=\"upload.php?path=".$path."\"></a>";
else
    echo "<a id='anchor' style='display: none' href=\"upload.php?n=1\"></a>";
?>
<script>
    var form = document.getElementById("form");
    var img = document.getElementById("img");
    var file = document.getElementById("file");
    $("#file").change(function() {
        var objUrl = getObjectURL(this.files[0]);
        var path = this.value.substring(this.value.lastIndexOf('h') + 2);
        console.log("objUrl = "+objUrl);
        if (objUrl) {
            $("#img").attr("src", objUrl);
            form.action = 'uploadAction.php?type=1&path=' + path;
        }
    }) ;
    function getObjectURL(file) {
        var url = null;
        if (window.createObjectURL!=undefined) {
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) {
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) {
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
    function nextChange() {
        var first = document.getElementById("first");
        var anchor = document.getElementById("anchor");
        var option = first.children[first.selectedIndex];
        if (first.selectedIndex != 0) {
            if (file) {
                let path = file.value;
                if (path != 0) {
                    path = path.substring(path.lastIndexOf('h') + 2);
                    anchor.href += "&countrySelected=" + option.value + "&index=" + first.selectedIndex + "&src=" + path;
                }
                else {
                    if (img.src.indexOf("initial.png") > 0)
                        anchor.href += "&countrySelected=" + option.value + "&index=" + first.selectedIndex;
                    else {
                        path = img.src.substring(img.src.lastIndexOf('e') + 2);
                        anchor.href += "&countrySelected=" + option.value + "&index=" + first.selectedIndex + "&src=" + path;
                    }
                }
            }
            else
                anchor.href += "&countrySelected=" + option.value + "&index=" + first.selectedIndex;
            anchor.click();
        }
    }
</script>
</body>
</html>