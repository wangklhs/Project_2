<?php
require_once ("config.php");
$err = false;
if ($_POST['pass'] != $_POST['rePass']) {
    header("Location:register.php?err=3&location=".$_GET['location']);
    $err = true;
}
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM traveluser WHERE Email =:email";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':email',$_POST['email']);
    $statement->execute();
    if ($statement->rowCount()>0) {
        header("Location:register.php?err=2&location=".$_GET['location']);
        $err = true;
    }
    $sql = "SELECT * FROM traveluser WHERE Username=:usr";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':usr',$_POST['username']);
    $statement->execute();
    if ($statement->rowCount()>0) {
        header("Location:register.php?err=1&location=".$_GET['location']);
        $err = true;
    }
} catch (PDOException $e) {
    die( $e->getMessage() );
}
if (strstr(($_POST['username']), ' ')) {
    header("Location:register.php?err=0&location=".$_GET['location']);
    $err = true;
}
if (!$err) {
    $sql = "INSERT INTO traveluser (Email,UserName,Pass,State,DateJoined) VALUES (:email,:usr,:pass,1,:dat)";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':email',$_POST['email']);
    $statement->bindValue(':usr',$_POST['username']);
    $statement->bindValue(':pass',$_POST['pass']);
    $statement->bindValue(':dat',date("Y-m-d",time()));
    $statement->execute();
    header("Location:log_in.php?location=".$_GET['location']);
}
$pdo = null;


