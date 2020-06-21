<?php?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form">
    <img src="images/sup/form_logo.png">
    <p>Sign up for Fisher</p>
    <div class="form-con">
        <?php echo "<form method=\"post\" action=\"registerAction.php?location=".$_GET['location']."\">"?>
            <p>Username:
                <?php
                if (isset($_GET["err"]) && ($_GET["err"] == 1))
                    echo "<span style='color: indianred;'>&nbsp &nbsp The username has existed!</span>";
                if (isset($_GET["err"]) && ($_GET["err"] == 2))
                    echo "<span style='color: indianred;'>&nbsp &nbsp Username can't contain spaces!</span>"
                ?>
            </p>
            <input name="username" type="text" required>
            <p>E-mail:
                <?php if (isset($_GET["err"]) && ($_GET["err"] == 2))
                    echo "<span style='color: indianred;'>&nbsp &nbsp The E-mail has existed!</span>"; ?>
            </p>
            <input name="email" type="email" required>
            <p>Password:
                <?php if (isset($_GET["err"]) && ($_GET["err"] == 3))
                    echo "<span style='color: indianred;'>&nbsp &nbsp Two different passwords!</span>"; ?>
            </p>
            <input name="pass" type="password" minlength="8" required>
            <p>Confirm Your Password:</p>
            <input name="rePass" type="password" minlength="8" required>
            <p></p>
            <button type="submit" class="submit">Sign Up</button>
        </form>
    </div>
    <?php echo "<p>Have an account? <a href=\"log_in.php?location=".$_GET['location']."\" class=\"blue-a\">Come back to log in.</a></p>" ?>
</div>
<footer class="root-fot form-fot">
    Copyright© 19fdubaldcoders' first project. No right reserved.  备案号: 19302010050 ws
</footer>
</body>
</html>

