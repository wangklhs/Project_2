<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form">
    <img src="images/sup/form_logo.png">
    <p>Sign in for Fisher</p>
    <div class="form-con">
        <?php echo "<form method=\"post\" action=\"logInaction.php?location=".$_GET['location']."\">"?>
            <p>Username:
                <?php if (isset($_GET["err"]) && ($_GET["err"] == 1))
                    echo "<span style='color: indianred;'>&nbsp &nbsp The username doesn't exist!</span>"; ?>
            </p>
            <input name="username" type="text" required>
            <p>Password:
                <?php if (isset($_GET["err"]) && ($_GET["err"] == 2))
                    echo "<span style='color: indianred;'>&nbsp &nbsp Wrong password!</span>" ?>
            </p>
            <input name="password" type="password" required>
            <p></p>
            <button type="submit" class="submit">Sign in</button>
        </form>
    </div>
    <?php echo"<p>New to Fisher? <a href=\"register.php?location=".$_GET['location']."\" class=\"blue-a\">Creat a new account.</a></p>" ?>
</div>
<footer class="root-fot form-fot">
    Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws
</footer>
</body>
</html>
<?php
