<?php
session_start();
$title = "iSeater";
require "includes".DIRECTORY_SEPARATOR."head.php";

$message = "";
?>
    <body>
    <div id = "signinHeader">
        <a href = "/"><img src="images/logo2.png" alt = "iSeater logo"></a>
    </div>
    <div id = "signinBackground">
        <div id = "signinWindow">
            <?php
            if(isset($_SESSION['error']))
            {
                $message .= "<div class=\"alert alert-danger\"><strong>".$_SESSION['error']."</strong></div>";
                echo $message;
                unset($_SESSION['error']);
            }
            else if(isset($_SESSION['notValid']))
            {
                $message .= "<div class=\"alert alert-danger\"><strong>".$_SESSION['notValid']."</strong></div>";
                echo $message;
                unset($_SESSION['notValid']);
            }
            else if(isset($_SESSION['logout']))
            {
                $message .= "<div class=\"alert alert-success\"><strong>".$_SESSION['logout']."</strong></div>";
                echo $message;
                session_destroy();
            }
            ?>
            <br>
            <p id = "signIn">Sign In</p>
            <form id = "loginForm" action = "teacherLogin.php" method = "POST">
                <label>Username: </label><input type="text" id="user" name="user" required><br/><br/>

                <label>Password: </label><input type="password" id="pass" name="pass" required><br/><br/>
                <input class="submitButton" type="submit" value="Sign In" name="submit">

            </form>
            <br>
        </div>
    </div>
    <a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
    </body>
<?php
require "includes".DIRECTORY_SEPARATOR."footer.php";
?>