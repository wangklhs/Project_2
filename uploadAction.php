<?php

require_once("config.php");
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM geocities WHERE AsciiName=" . $_POST['cityName'];
    $result = $pdo->query($sql);
    $row = $result->fetch();
    $city = $row['GeoNameID'];
    $_POST['country'] = substr($_POST['country'],1,2);
    $sql = "SELECT Count(ImageID) AS NumImg FROM travelimage";
    $result = $pdo->query($sql);
    $num = $result->fetch()['NumImg'];
    if ($num < 82)
        $num = 82;
    if ($_GET['type'] == 1) {
        $sql = "INSERT INTO travelimage (ImageID,Title,Description,CityCode,Country_RegionCodeISO,UID,PATH,Content) VALUES (:id,:title,:des,:city,:iso,:uid,:path,:content)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id',$num + 1);
        $statement->bindValue(':title',$_POST['title']);
        $statement->bindValue(':des',$_POST['des']);
        $statement->bindValue(':city',$city);
        $statement->bindValue(':iso',$_POST['country']);
        $statement->bindValue(':uid',$_COOKIE['Username']);
        $statement->bindValue(':path',$_GET['path']);
        $statement->bindValue(':content',$_POST['content']);
        $statement->execute();
    }
    else {
        $sql = "UPDATE travelimage SET Title=:title,Description=:des,CityCode=:city,Country_RegionCodeISO=:iso,Content=:content WHERE ImageID=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':title',$_POST['title']);
        $statement->bindValue(':des',$_POST['des']);
        $statement->bindValue(':city',$city);
        $statement->bindValue(':iso',$_POST['country']);
        $statement->bindValue(':content',$_POST['content']);
        $statement->bindValue(':id',$_GET['id']);
        $statement->execute();
    }
    $sql = "UPDATE traveluser SET DateLastModified=:dat";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':dat',date("Y-m-d",time()));
    $statement->execute();
} catch (PDOException $e) {
    die( $e->getMessage() );
}
header("Location:photo.php");
