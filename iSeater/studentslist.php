<?php
session_start();
require "includes".DIRECTORY_SEPARATOR."functions.php";
hasAccess();

$title = "iSeater - Students";
require "includes".DIRECTORY_SEPARATOR."databaseConnection.php";
require "includes".DIRECTORY_SEPARATOR."head.php";
?>
    <body>
    <?php
    require "includes".DIRECTORY_SEPARATOR."menu.php";

    /* ADD A STUDENT */
    if(isset($_POST['addStudentSubmit']))
    {
        $validEntry = true;
        //query to get all student ids
        $allStudentIds = "SELECT UserId FROM IS_User";
        $result = $conn->query($allStudentIds);

        $studentIds = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                //add all the students in the selected class into the $classroom array
                $studentIds[] = $row;
            }
        }
        //test if the studentId submitted already exists
        for ($i = 0; $i < sizeof($studentIds); $i++){
            if ($_POST['studentid'] == $studentIds[$i]['UserId']){
                echo "<div class=\"alert alert-danger\" style = \"width: 60%; margin: 0 auto;\">
                        <strong>Warning! </strong> This student ID is already registered.
                      </div>";
                $validEntry = false;
                break;
            }
        }

        if($validEntry) {
            //query to add values into the Student table
            $addStudent = "INSERT INTO IS_User (UserID, FirstName, LastName, Gender, Role) VALUES (".$_POST['studentid'].",'".$_POST['firstName']."','".$_POST['lastName']."','".$_POST['gender']."','Student');";
            $addStudentClass = "INSERT INTO IS_User_Class(UserID, ClassID) VALUES (".$_POST['studentid'].", '".$_POST['class']."');";
            //run query
            $conn->query($addStudent);
            $conn->query($addStudentClass);
        }
    }

    /* DELETE A STUDENT */

    if(isset($_POST['checkbox'])){
        $studentsToDelete = $_POST['checkbox'];
        for($i = 0; $i < sizeof($studentsToDelete); $i++){
            $deleteFromUser_Class = "DELETE FROM IS_User_Class WHERE UserID = ".$studentsToDelete[$i].";";
            $result = $conn->query($deleteFromUser_Class);

            $deleteFromIs_User = "DELETE FROM IS_User WHERE UserID = ".$studentsToDelete[$i].";";
            $result = $conn->query($deleteFromIs_User);

        }
        if(sizeof($studentsToDelete) > 1){
            echo "<div class=\"alert alert-success\" style = \"width: 60%; margin: 0 auto;\">
                        <strong>Success! </strong> These students were successfully deleted.
                  </div>";
        }
        else {
            echo "<div class=\"alert alert-success\" style = \"width: 60%; margin: 0 auto;\">
                        <strong>Success! </strong> This student was successfully deleted.
                  </div>";
        }

    }

    ?>
        <script>
            function showForm() {
                $(".alert").hide();
                $(".addRemoveForm").toggle();
            };

            function removeStudent() {
                //show and hide the column to remove students
                //when the user clicks on the remove student button, the column appears
                //when they click it again, the column is hidden
                $(".checkboxColumn").toggle();
            };

            $(function () {
                $('[data-toggle="popover"]').popover()
            });

            $(document).ready(function () {
                $('.ddlFilterTableRow').bind('change', function () {
                    $('.ddlFilterTableRow').attr('disabled', 'disabled');
                    $('#studentsTable').find('.studentRow').hide();

                    var criteriaAttribute = '';

                    $('.ddlFilterTableRow').each(function () {
                        if ($(this).val() != '0') {
                            criteriaAttribute += '[data-classId="' + $(this).val() + '"]';
                        }
                    });

                    $('#studentsTable').find('.studentRow' + criteriaAttribute).show();

                    $('.ddlFilterTableRow').removeAttr('disabled');

                });
            });
        </script>
        <br>
        <div class="alert alert-success" id = "studentAddedAlert" style = "display: none; width: 60%; margin: 0 auto;">
            <strong>Success! </strong> This student was successfully registered.
        </div>
        <div class="alert alert-danger" id = "invalidstudentId" style = "display: none; width: 60%; margin: 0 auto;">
            <strong>Warning! </strong>Please enter a valid student ID. A student ID must be composed of 6 numeric characters.
        </div>
        <div class="alert alert-danger" id = "invalidfName" style = "display: none; width: 60%; margin: 0 auto;">
            <strong>Warning! </strong>Please enter a valid first name.
        </div>
        <div class="alert alert-danger" id = "invalidlName" style = "display: none; width: 60%; margin: 0 auto;">
            <strong>Warning! </strong>Please enter a valid last name.
        </div>
        <div class="alert alert-danger" id = "invalidGender" style = "display: none; width: 60%; margin: 0 auto;">
            <strong>Warning! </strong>Please choose a gender.
        </div>
        <div class="alert alert-danger" id = "invalidClass" style = "display: none; width: 60%; margin: 0 auto;">
            <strong>Warning! </strong>Please choose a class.
        </div>
        <br>
        <div class = "studentsListButtons" style="text-align: center;">
            <button onclick = "showForm('addStudent')" type="button" class="btn btn-success">Add Student</button>
            <button onclick = "removeStudent()" type="button" class="btn btn-danger">Delete Student</button>
        </div>
        <br>

        <div class = "addRemoveForm center" style = "display: none;">
            <form method = "post" name = "addStudent" id = "formToAddStudent">
                <table>
                    <tr>
                        <td>Student ID:&nbsp;&nbsp;</td>
                        <td><input type = "text" name = "studentid" required></td>
                    </tr>
                    <tr>
                        <td>First Name:&nbsp;&nbsp;</td>
                        <td><input type = "text" name = "firstName" required></td>
                    </tr>
                    <tr>
                        <td>Last Name:&nbsp;&nbsp;</td>
                        <td><input type = "text" name = "lastName" required></td>
                    </tr>
                    <tr>
                        <td>Gender:&nbsp;&nbsp;</td>
                        <td>
                            <input type = "radio" name = "gender" value = "M" required> M
                            <input type = "radio" name = "gender" value = "F"> F
                        </td>
                    </tr>
                    <tr>
                        <td>Class: </td>
                        <td>
                            <select name = "class" required>
                                <option disabled selected value> - </option>
                                <?php
                                //populate this dropdown with the existing classes
                                $selectClasses = "SELECT ClassID FROM Class;";
                                $result = $conn->query($selectClasses);

                                if ($result->num_rows){
                                    $optionsStr = "";
                                    while($row = $result->fetch_assoc()) {
                                        $optionsStr.= "<option value = '".$row["ClassID"]."'>".$row["ClassID"]."</option>";
                                    }
                                    echo $optionsStr;
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
                <input class = ".btn btn-info" type = "button" value = "Add Student" onclick = "validateForm()">
                <input name = "addStudentSubmit" id = "submitBtn" type = "submit" style = "display:none;">
            </form>
        </div>
        <br><br>
    <div class = "container">
        <select name = "class" class= "ddlFilterTableRow" id = "filterClass" required data-attribute = "classId">
            <option selected value = "0"> Filter By Class </option>
            <?php
            //populate this drop down with the existing classes
            $selectClasses = "SELECT ClassID FROM Class;";
            $result = $conn->query($selectClasses);

            if ($result->num_rows){
                $optionsStr = "";
                while($row = $result->fetch_assoc()) {
                    $optionsStr.= "<option value = '".$row["ClassID"]."'>".$row["ClassID"]."</option>";
                }
                echo $optionsStr;
            }
            ?>
        </select>
    </div>
<?php
$selectData = "SELECT IS_User.UserID, IS_User.FirstName, IS_User.LastName, IS_User.Gender, IS_User_Class.ClassID FROM IS_User_Class INNER JOIN IS_User ON IS_User_Class.UserID = IS_User.UserID";
$result = $conn->query($selectData);

$studentsTable = "";
if ($result->num_rows) {
    //output data of each row
    $studentsTable .= "<form id = 'deleteStudent' action = '' method = 'post'><table class = 'table table-striped studentsList sortable' id = 'studentsTable'>";
    $studentsTable .= "<tr>
                <th style = 'display: none;' class = 'checkboxColumn'>
                    <span style = 'color: red;' onclick = \"document.getElementById('deleteStudent').submit();\" class = 'glyphicon glyphicon-trash'></span>
                </th>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Class</th> 
                </tr>";
    while($row = $result->fetch_assoc()) {
        $studentsTable .= "<tr class = 'studentRow' data-classId = '".$row["ClassID"]."'>";
        $studentsTable .= "<td style = 'display: none;' class = 'checkboxColumn'><input type = 'checkbox' name = 'checkbox[]' value = '".$row["UserID"]."'</td>";
        $studentsTable .= "<td>".$row["UserID"]."</td>";
        $studentsTable .= "<td>".$row["FirstName"]."</td>";
        $studentsTable .= "<td>".$row["LastName"]."</td>";
        $studentsTable .= "<td>".$row["Gender"]."</td>";
        $studentsTable .= "<td>".$row["ClassID"]."</td>";
        $studentsTable .= "</tr>";
    }
    $studentsTable .= "</table></form>";

    echo $studentsTable;
}
else {
    echo "0 results";
}
?>
    <script>
        function validateForm(){
            console.log("validating");
            $(".alert").hide();

            var studentId = document.forms["addStudent"]["studentid"].value;
            var firstName = document.forms["addStudent"]["firstName"].value;
            var lastName = document.forms["addStudent"]["lastName"].value;
            var gender = document.forms["addStudent"]["gender"].value;
            var classId = document.forms["addStudent"]["class"].selectedIndex;
            var validNameRegEx = /^[a-zA-Z ]{2,30}$/;
            var validEntry = true;

            if (firstName === "" || validNameRegEx.test(firstName) === false){
                $("#invalidfName").show();
                validEntry = false;
            }
            if (lastName === "" || validNameRegEx.test(lastName) === false){
                $("#invalidlName").show();
                validEntry = false;
            }
            if (studentId === "" || studentId.length !== 6 || isNaN(studentId)){
                $("#invalidstudentId").show();
                validEntry = false;
            }
            if(classId === 0){
                $("#invalidClass").show();
                validEntry = false;
            }
            if(gender === "" || gender === undefined || gender === null){
                $("#invalidGender").show();
                validEntry = false;
            }

            if(validEntry){
                $(" #submitBtn").click();
                setTimeout($("#studentAddedAlert").show(), 20000);
                return true;
            } else {
                return false;
            }
        }
    </script>
    </body>
<?php
require "includes".DIRECTORY_SEPARATOR."footer.php";
//close connection
$conn->close();
?>
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
