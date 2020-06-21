<?php
$path = $_GET['path'];
require_once ("config.php");
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM travelimage WHERE PATH = '".$path."'";
    $result = $pdo->query($sql);
    $row = $result->fetch();
    $id = $row['ImageID'];
    if (isset($_GET['collect'])) {
        $sql = "INSERT INTO travelimagefavor (UID,ImageID) VALUES (".$_COOKIE['Username'].",".$id.")";
        $pdo->query($sql);
    }
    $title = $row['Title'];
    $des = $row['Description'];
    $content = $row['Content'];
    $countryISO = $row['Country_RegionCodeISO'];
    if ($countryISO == "'C")
        $countryISO = 'CA';
    $cityCode = $row['CityCode'];
    $sql = "SELECT Count(FavorID) AS NumImg FROM travelimagefavor WHERE ImageID = '".$id."'";
    $result = $pdo->query($sql);
    $likeNum = $result->fetch()['NumImg'];
    $sql = "SELECT AsciiName FROM geocities WHERE GeoNameID = '".$cityCode."'";
    $result = $pdo->query($sql);
    $city = $result->fetch()['AsciiName'];
    $sql = "SELECT Country_RegionName FROM geocountries_regions WHERE ISO ='".$countryISO."'";
    $result = $pdo->query($sql);
    $row = $result->fetch();
    $country = $row['Country_RegionName'];
    if (isset($_COOKIE['Username']) && $_COOKIE['Username'] != '') {
        $sql = "SELECT ImageID FROM travelimagefavor WHERE UID = '".$_COOKIE["Username"]."' AND ImageID = '".$id."'";
        $result = $pdo->query($sql);
        if ($result->rowCount()>0)
            $collected = true;
        else
            $collected = false;
    }
} catch (PDOException $e) {
    die( $e->getMessage());
}
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
        Details
    </header>
    <div class="details">
        <h2><?php echo $title?></h2>
        <?php echo"<img src=\"images/medium/".$path."\">" ?>
        <ul class="float-right">
            <li>
                <table>
                    <tr>
                        <td class="head">Like number</td>
                    </tr>
                    <tr>
                        <td class="like"><?php echo $likeNum ?></td>
                    </tr>
                </table>
            </li>
            <li>
                <table>
                    <tr>
                        <td class="head">Image details</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px"><?php echo "Content: " . $content ?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px">Country: <?php
                            if (isset($country))
                                echo $country?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px"><?php echo "City: " . $city?></td>
                    </tr>
                </table>
            </li>
            <li>
                <table class="red">
                    <tr>
                        <td>
                            <?php
                            if (!isset($_COOKIE['Username']) || $_COOKIE['Username'] == '')
                                echo "<a href='log_in.php'>
                                        <img src=\"images/sup/like.png\">
                                        <span>Collect</span>
                                      </a>";
                            else {
                                if ($collected) {
                                    echo "<img src=\"images/sup/like.png\">
                                            <span>Collected</span>";
                                }
                                else
                                    echo "<a href='details.php?collect=1&path=".$path."'>
                                            <img src=\"images/sup/like.png\">
                                            <span>Collect</span>
                                        </a>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
        <p>
            <?php echo "Description: " . $des;?>
            <br>
            Don’t worry about encountering bugs. After all, everyone is new on the way of coding. If the code you write has no bugs, go
            Apply for exemption. Learning to debug in your messy code will be a very important feature, so you can improve your debug.
            Ping, try to solve errors faster when you can't avoid them.
        </p>
    </div>
</div>
<footer class="root-fot">
    Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws
</footer>
</body>
</html>
