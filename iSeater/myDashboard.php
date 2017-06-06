<?php
session_start();
require "includes".DIRECTORY_SEPARATOR."functions.php";
hasAccess();

$title = "iSeater - My Dashboard";
require "includes".DIRECTORY_SEPARATOR."head.php";
require "includes".DIRECTORY_SEPARATOR."databaseConnection.php";

$title = "iSeater - Dashboard";
$username = "Aline";
?>
<body>
<?php require "includes".DIRECTORY_SEPARATOR."menu.php"; ?>
<script>
    function showThisChart(obj, className){
        $("table").hide();
        $(".titleBar").hide();
        $("." + className + "").show();
    }
</script>
<div class = "container">
        <div class = ".col-md-5" id="left"><br>
            <h3 id = "classesDashboard"> Classes: </h3>
            <br>
            <?php
            $query = 'SELECT ClassID FROM Class';
            $result = $conn -> query($query);
            $buttonsStr = "";
            if($result->num_rows){
                while($row  = $result->fetch_assoc())
                    $buttonsStr .= "<button onclick = \"showThisChart(this, '".$row["ClassID"]."')\" type='button' class='btn btn-info ".$row["ClassID"]."'>".$row["ClassID"]."</button><br><br>";
            }
            echo $buttonsStr;
            ?>
        </div>

        <div class = ".col-md-5" id="right">
            <?php
            $query = 'SELECT * FROM Class;';
            $result = $conn -> query($query);
            $chartResult = "";
            if($result->num_rows) {
                while($row = $result->fetch_assoc()){
                    $chartResult .= "<div class = 'titleBar ".$row['ClassID']."' style = 'display:none;'><hr>";
                    $chartResult .= "<span>Class ID: " . $row['ClassID'] . " | Class Name: " . $row['ClassName'] .'</span><br>';
                    $chartResult .= "<hr></div>";
                    $serializedData = $row['Layout'];
                    $layoutArr = unserialize($row['Layout']);
                    //$chartResult .= "<div>";
                    $chartResult .= "<table id = 'dashboardTable' class=\"table table-bordered ".$row["ClassID"]."\" style = 'display: none;'>";
                    $numOfColumns = sizeof($layoutArr[0]);
                    $numOfRows = sizeof($layoutArr);
                    for ($row = 0; $row < $numOfRows; $row++){
                        $chartResult .= "<tr>";
                        for($col = 0; $col < $numOfColumns; $col++){
                            if(!empty($layoutArr[$row][$col]["Gender"])) {
                                $chartResult .= "<td><span class = 'studentNameBold'>".$layoutArr[$row][$col]["FirstName"] . " " . $layoutArr[$row][$col]["LastName"] . "</span><br>" . $layoutArr[$row][$col]["UserID"] . " (" . $layoutArr[$row][$col]["Gender"] . ") </td>";
                            }
                            else{
                                $chartResult .= "<td><pre>             </pre></td>";
                            }
                        }
                        $chartResult .= "</tr>";
                    }
                    $chartResult .= "<tr><td id = 'blackboard' colspan = '".$numOfColumns."'><strong>BLACKBOARD</strong></td></tr>";
                    $chartResult .= "</table>";
                    //$chartResult .= "</div>";
                }
                echo $chartResult;
            }
            ?>
        </div>
</div>
<div id="bottom"></div>
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</body>
<?php require "includes".DIRECTORY_SEPARATOR."footer.php"?>
</html>