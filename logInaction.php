<?php
    require_once ("config.php");
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM traveluser WHERE Username=:usr";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':usr',$_POST['username']);
        $statement->execute();
        if($statement->rowCount()>0) {
            $row = $statement->fetch();
            if ($row['Pass'] == $_POST['password']) {
                $expiryTime = time() + 60 *60*24;
                setcookie("Username", $row['UID'], $expiryTime);
                header("Location:".$_GET['location']);
            }
            else
                header("Location:log_in.php?err=2");
        }
        else
            header("Location:log_in.php?err=1");
        $pdo = null;
    } catch (PDOException $e) {
        die( $e->getMessage() );
    }

