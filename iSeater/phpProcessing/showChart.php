<!DOCTYPE html>
<html>
<head>
    <style>

    </style>
</head>
<body></body>
<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 11/22/2016
 * Time: 8:47 AM
 */
require "../includes/databaseConnection.php";

//global $conn;
$query = 'SELECT * FROM Class WHERE ClassID = "1B2016" ';//.$_POST["class"].';';
$result = $conn -> query($query);
//var_dump($result);
echo $result->num_rows . "<hr>";
//var_dump($result->fetch_row());

   $row = $result->fetch_assoc();
        echo "ClassID: " . $row['ClassID'] . ", Class Name: " . $row['ClassName'] . ", Term: " . $row['Term'] . '<br>';
        //echo "Layout: " . $row['Layout'];
        echo "<hr><br><br>";

    $serializedData = $result->fetch_assoc()['Layout'];

    //unserialized layout
    $layoutArr = unserialize($row['Layout']);
    //print_r($layoutArr);
echo "<hr>";
/*******************************************************/
/*foreach ($layoutArr as $firstIndex => $row){
    foreach ($row as $secondIndex => $column){

    }
}*/

//generate the class layout: empty 2-dimensional array
$classLayout = generateClassLayout();
    //print_r($classLayout);
echo "<br><br>";
//show result
echo "<table>";

for($i = 0; $i < sizeof($classLayout); $i++){
    echo "<tr>";
    for($j = 0; $j < sizeof($classLayout[0]); $j++){
        $curStudent = $classLayout[$i][$j];
        echo "<td>"; //for 2
        //1.no border for empty cell
        /*if(!empty($layoutArr[$i][$j]["FirstName"])) {
            echo "<td>"."<b>" . $curStudent = $layoutArr[$i][$j]["FirstName"] . " " . $layoutArr[$i][$j]["LastName"] . "</b>" . "<br>" . $layoutArr[$i][$j]["UserID"] . " (" . $layoutArr[$i][$j]["Gender"] . ") " . "</td>" ;
        }*/

        //2.border for empty cell
        if(!empty($layoutArr[$i][$j]["FirstName"])) {
            echo "<b>" . $curStudent = $layoutArr[$i][$j]["FirstName"] . " " . $layoutArr[$i][$j]["LastName"] . "</b>" . "<br>" . $layoutArr[$i][$j]["UserID"] . " (" . $layoutArr[$i][$j]["Gender"] . ")" ;
        }

        if(empty($layoutArr[$i][$j]["FirstName"])){
            echo $curStudent = "";
        }
            if($j == sizeof($classLayout[0]) - 1) {
                echo "<br>";
            }
        echo "</td>"; //for 2
    }
    echo "</tr>";
}
echo "</table>";
function generateClassLayout()
{
    $classroomLayout = [];

    global $conn;

    $getClassroom = "SELECT RoomID FROM Class WHERE ClassID = '1B2016'";// . $_POST['class'] . "'";

    $classroom = $conn->query($getClassroom);

    if ($classroom->num_rows) {
        while ($row = $classroom->fetch_assoc()) {
            $roomNumber = $row['RoomID'];
        }
    }

    $getRowsAndCols = "SELECT NumRows, NumCols FROM Room WHERE RoomID = '" . $roomNumber . "'";

    $rowsAndCols = $conn->query($getRowsAndCols);

    if ($rowsAndCols->num_rows) {
        while ($row = $rowsAndCols->fetch_assoc()) {
            $rows = $row["NumRows"];
            $cols = $row["NumCols"];
        }
    }

    //create a two dimensional array ($rows rows and $columns columns)
    for($i = 0; $i < $rows; $i ++){
        //all the elements of this array are empty strings ""
        $classroomLayout[] = array_fill(0, $cols, "");
    }

    return $classroomLayout;
}
?>
</body>
</html>

