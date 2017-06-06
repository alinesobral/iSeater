<?php
require "includes".DIRECTORY_SEPARATOR."databaseConnection.php";


if(isset($_POST["saveButton"])){
    $chart = $_POST["saveChart"];
    $selectedClass = $_POST['classId'];
    saveLayout($chart, $selectedClass);

    header("Location: myDashboard.php");
}

function saveLayout($arr, $classid){
    global $conn;
    $layoutQuery = "UPDATE Class SET Layout = '" . mysqli_real_escape_string($conn, $arr) . "' WHERE ClassID = '" . $classid . "';";
    $conn->query($layoutQuery);
}
?>

<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
