<nav>
    <img alt="iSeater logo" src="images/logo2.png">
    <span class = "signedin greyFont">Signed in as <?php echo $_SESSION['signed_in_as'] ?></span>
    <ul class = "menu">
        <li><a class = "greyFont menuItem" href="myDashboard.php">Dashboard</a></li>|
        <li><a class = "greyFont menuItem" href="studentslist.php">Students</a></li>|
        <li><a class = "greyFont menuItem" href="generatechart.php">Generate Chart</a></li>
        <li class = "signout"><a class = "whiteFont" href="logout.php">Sign Out</a></li>
    </ul>
</nav>
