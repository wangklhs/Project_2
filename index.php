<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet " href="style.css">
</head>
<body>
<nav>
    <div id="nav-left">
        <img id="logo" src="images/sup/logo.png" width="27px">
        <a id="now" name="now" class="nav-a up" href="index.php">Home</a>
        <a class="nav-a up" href="browse.php">Browser</a>
        <a class="nav-a up" href="search.php">Search</a>
    </div>
    <div id="nav-right">
    <?php
        if (!isset($_COOKIE['Username']) || $_COOKIE['Username'] == '') {
            echo "<a class=\"nav-a\" href=\"log_in.php?location=index.php\">Log in</a>";
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
<?php
require_once ("config.php");function randArr($min, $max, $num) {
    if ($num > 1) {
        $arr = array();
        $inc = intval(($max - $min) / $num);
        for ($i = 1; $i <= $num; $i ++) {
            $arr[] = rand($min + $inc * ($i - 1), $min + $inc * $i - 1);
        }
    }
    else
        $arr = rand($min, $max);
    return $arr;
}
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET['refresh']))
        $arr = randArr(1, 79, 6);
    else {
        $sql = "SELECT ImageID,Count(FavorID) AS NumImg FROM travelimagefavor GROUP BY ImageID ORDER BY NumImg DESC";
        $result = $pdo->query($sql);
        $arr = array();
        for ($i = 0; $i < 6; $i ++) {
            if ($row = $result->fetch())
                $arr[$i] = $row['ImageID'];
            else
                $arr[$i] = randArr(13, 79, 1);
        }
    }
} catch (PDOException $e) {
    die( $e->getMessage() );
}
function makeImg($id,$pdo) {
   try {
       $sql = "SELECT Title,Description,PATH FROM travelimage WHERE ImageID=:id";
       $statement = $pdo->prepare($sql);
       $statement->bindValue(':id',$id);
       $statement->execute();
       $row = $statement->fetch();
       if (mb_strlen($row['Title']) > 14) {
           $row['Title'] = substr($row['Title'],0, 14)."...";
       }
       if (mb_strlen($row['Description']) > 18) {
           $row['Description'] = substr($row['Description'],0, 18)."...";
       }
       if (mb_strlen($row['Description']) == 0) {
           $row['Description'] = "No description";
       }
} catch (PDOException $e) {
       die( $e->getMessage());
   }
   echo "<a href=\"details.php?path=".$row['PATH']."\" imgID='".$id."'>
            <img src='images/square-medium/".$row['PATH']."'>
        </a>
        <figcaption>
            <h3>".$row['Title']."</h3>"
            .$row['Description'].
        "</figcaption>";
}
?>
<div class="content">
    <img id="head" src="images/sup/head_picture.jpg" width="99%">
    <div class="con-down">
        <table id="tab-one">
            <tr>
                <td>
                    <figure>
                        <?php makeImg($arr[0], $pdo);?>
                    </figure>
                </td>
                <td>
                    <figure>
                        <?php makeImg($arr[1], $pdo);?>
                    </figure>
                </td>
                <td>
                    <figure>
                        <?php makeImg($arr[2], $pdo);?>
                    </figure>
                </td>
            </tr>
            <tr>
                <td>
                    <figure>
                        <?php makeImg($arr[3], $pdo);?>
                    </figure>
                </td>
                <td>
                    <figure>
                        <?php makeImg($arr[4], $pdo);?>
                    </figure>
                </td>
                <td>
                    <figure>
                        <?php makeImg($arr[5], $pdo);
                        $pdo = null ?>
                    </figure>
                </td>
            </tr>
        </table>
    </div>
</div>
<footer id="home_footer">
    <table id="fot-tab-one">
        <tr>
            <td>开发者:</td>
            <td>合作商:</td>
        </tr>
        <tr>
            <td>wang shuai</td>
            <td>反积分联盟</td>
        </tr>
        <tr>
            <td>W.S.</td>
            <td>死亡细胞学系</td>
        </tr>
        <tr>
            <td>HD-WS</td>
            <td>fdu资深hz</td>
        </tr>
    </table>
    <table id="fot-tab-two">
        <tr>
            <td><img src="images/sup/coin.png"></td>
            <td><img src="images/sup/share.png"></td>
        </tr>
        <tr>
            <td><img src="images/sup/setting.png"></td>
            <td><img src="images/sup/wechat.png"></td>
        </tr>
    </table>
    <span>Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws</span>
</footer>
<a href="index.php?refresh=1"><img class="refresh" src="images/sup/refresh.png"></a>
<a href="#now"><img class="toTop" src="images/sup/up.png"></a>
</body>
</html>

