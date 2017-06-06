<?php
session_start();
$title = "iSeater - Output";
require "includes".DIRECTORY_SEPARATOR."head.php";
require "includes".DIRECTORY_SEPARATOR."databaseConnection.php";

if(isset($_POST['generateChart']))
{
    //generate the class layout: empty 2-dimensional array
    $classLayout = generateClassLayout();
    //1-dimensional array with all the students in the selected class
    $classroomNoGender = getStudents();
    //check if number of students and number of seats are compatible
    if(sizeof($classroomNoGender) > (sizeof($classLayout) * sizeof($classLayout[0]))){
        echo "Class ".$classroomNoGender[0]['Class']." has ".sizeof($classroomNoGender)." students and the chosen
        classroom only has ".(sizeof($classLayout)*sizeof($classLayout[0]))." seats. Please choose another layout.";

        exit; //stops execution of the program
    }

    if(isset($_POST['gender'])){
        $genderPattern = $_POST['gender'];

        /* NO GENDER PATTERN */
        if($genderPattern == "nogender"){
            if(isset($_POST['order'])) {
                $order = $_POST['order'];
                /* ARRANGE ALPHABETICALLY AND HORIZONTALLY*/
                if ($order == "alphabeticalHorizontal") {
                    $classroomByLastName = sortAlphabetically($classroomNoGender);

                    $classroomByLastNameIndex = 0;
                    //Populate the empty $classLayout array with the students
                    for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            $classLayout[$row][$col] = $classroomByLastName[$classroomByLastNameIndex];
                            //echo $classroomByLastName[$classroomByLastNameIndex]['LastName']."<br><hr>";
                            $classroomByLastNameIndex++;

                        }
                    }
                }

                /* ARRANGE ALPHABETICALLY AND VERTICALLY*/
                else if ($order == "alphabeticalVertical") {
                    $classroomByLastName = sortAlphabetically($classroomNoGender);
                    $classroomByLastNameIndex = 0;
                    //Populate the empty $classLayout array with the students
                    for($col = 0; $col < sizeof($classLayout[0]); $col++){
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            $classLayout[$row][$col] = $classroomByLastName[$classroomByLastNameIndex];
                            //echo $classroomByLastName[$classroomByLastNameIndex]['LastName']."<br><hr>";
                            $classroomByLastNameIndex++;

                        }
                    }
                }

                /* ARRANGE BY ASCENDING ORDER OF THE STUDENT ID AND HORIZONTALLY*/
                else if ($order == "byidHorizontal") {
                    //sort students in a 1-dimensional array by ascending order of student ids
                    $classroomById = sortByStudentId($classroomNoGender);

                    $classroomByIdIndex = 0;
                    //Populate the empty $classLayout array with the students
                    for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            $classLayout[$row][$col] = $classroomById[$classroomByIdIndex];
                            $classroomByIdIndex++;
                        }
                    }

                }

                /* ARRANGE BY ASCENDING ORDER OF THE STUDENT ID AND VERTICALLY*/
                else if ($order == "byidVertical") {
                    //sort students in a 1-dimensional array by ascending order of student ids
                    $classroomById = sortByStudentId($classroomNoGender);

                    $classroomByIdIndex = 0;
                    //Populate the empty $classLayout array with the students
                    for($col = 0; $col < sizeof($classLayout[0]); $col++){
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            $classLayout[$row][$col] = $classroomById[$classroomByIdIndex];
                            $classroomByIdIndex++;
                        }
                    }

                }

                /* ARRANGE RANDOMLY */
                else if ($order == "random") {
                    //shuffle all the students
                    shuffle($classroomNoGender);

                    $classroomNoGenderIndex = 0;
                    //add the students to the $classLayout array
                    for($rows = sizeof($classLayout) - 1; $rows >= 0 ; $rows--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                $classLayout[$rows][$col] = $classroomNoGender[$classroomNoGenderIndex];
                                $classroomNoGenderIndex++;
                        }
                    }
                }
            }
        }

        /* ARRANGE VERTICALLY WITH GIRLS ON THE LEFTMOST COLUMN */
        else if($genderPattern == "girlsboys"){
            //$genderSorted holds 2 arrays: one with girls only and another one with boys only
            $genderSorted = separateByGender($classroomNoGender);

            if(isset($_POST['order'])){
                $order = $_POST['order'];
                /* ALPHABETICALLY AND HORIZONTALLY ARRANGED */
                if($order == "alphabeticalHorizontal"){
                    $boysArray = sortAlphabetically($genderSorted[0]);
                    $girlsArray = sortAlphabetically($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($row = sizeof($classLayout)-1; $row >= 0; $row--) {
                        for ($col = 0; $col < sizeof($classLayout[0]); $col++) {
                            if ($col % 2 == 0) {
                                $classLayout[$row][$col] = $girlsArray[$girlsArrayIndex];
                                unset($girlsArraySorted[$girlsArrayIndex]);
                                $girlsArrayIndex++;
                            } else {
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                unset($boysArraySorted[$boysArrayIndex]);
                                $boysArrayIndex++;
                            }
                        }
                    }


                }
                else if($order == "alphabeticalVertical"){
                    $boysArraySorted = sortAlphabetically($genderSorted[0]);
                    $girlsArraySorted = sortAlphabetically($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($col = 0; $col < sizeof($classLayout[0]); $col++){
                        for($row = sizeof($classLayout) - 1; $row >= 0; $row--){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                unset($girlsArraySorted[$girlsArrayIndex]);
                                $girlsArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                unset($boysArraySorted[$boysArrayIndex]);
                                $boysArrayIndex++;
                            }
                        }
                    }

                }
                else if($order == "byidHorizontal"){
                    $boysArraySorted = sortByStudentId($genderSorted[0]);
                    $girlsArraySorted = sortByStudentId($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                $girlsArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                $boysArrayIndex++;
                            }
                        }
                    }

                }
                else if($order == "byidVertical"){
                    $boysArraySorted = sortByStudentId($genderSorted[0]);
                    $girlsArraySorted = sortByStudentId($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($col = 0; $col < sizeof($classLayout[0]); $col++){
                        for($row = sizeof($classLayout) - 1; $row >= 0; $row--){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                $girlsArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                $boysArrayIndex++;
                            }
                        }
                    }


                }
                else if($order == "random"){
                    //shuffle the students around the array to make sure the result is going to be truly random
                    shuffleArray($genderSorted);
                    //girls are going to be on the even columns and boys on the odd ones
                    $boysArrayIndex = 0;
                    $girlsArrayIndex = 0;
                    for($rows = sizeof($classLayout) - 1; $rows >= 0; $rows--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            if($col % 2 == 0){
                                $classLayout[$rows][$col] = $genderSorted[1][$girlsArrayIndex];
                                $girlsArrayIndex++;
                            }
                            else {
                                $classLayout[$rows][$col] = $genderSorted[0][$boysArrayIndex];
                                $boysArrayIndex++;
                            }
                        }
                    }


                }
            }
        }

        /* ARRANGE VERTICALLY WITH BOYS ON THE LEFTMOST COLUMN */
        else if($genderPattern == "boysgirls"){
            //$genderSorted holds 2 arrays: one with girls only and another one with boys only
            $genderSorted = separateByGender($classroomNoGender);

            if(isset($_POST['order'])){
                $order = $_POST['order'];
                if($order == "alphabeticalHorizontal"){
                    $boysArraySorted = sortAlphabetically($genderSorted[0]);
                    $girlsArraySorted = sortAlphabetically($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                unset($boysArraySorted[$boysArrayIndex]);
                                $boysArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                unset($girlsArraySorted[$girlsArrayIndex]);
                                $girlsArrayIndex++;
                            }
                        }
                    }

                }
                else if($order == "alphabeticalVertical"){
                    $boysArraySorted = sortAlphabetically($genderSorted[0]);
                    $girlsArraySorted = sortAlphabetically($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($col = 0; $col < sizeof($classLayout[0]); $col++){
                        for($row = sizeof($classLayout) - 1; $row >= 0; $row--){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                unset($boysArraySorted[$boysArrayIndex]);
                                $boysArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                unset($girlsArraySorted[$girlsArrayIndex]);
                                $girlsArrayIndex++;
                            }
                        }
                    }


                }
                else if($order == "byidHorizontal"){
                    $boysArraySorted = sortByStudentId($genderSorted[0]);
                    $girlsArraySorted = sortByStudentId($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                $boysArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                $girlsArrayIndex++;
                            }
                        }
                    }



                }
                else if($order == "byidVertical"){
                    $classroomById = sortByStudentId($classroomNoGender);

                    $boysArraySorted = sortByStudentId($genderSorted[0]);
                    $girlsArraySorted = sortByStudentId($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //Populate the empty $classLayout array with the students
                    for($col = 0; $col < sizeof($classLayout[0]); $col++){
                        for($row = sizeof($classLayout) - 1; $row >= 0; $row--){
                            if($col % 2 == 0){
                                $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                $boysArrayIndex++;
                            }
                            else {
                                $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                $girlsArrayIndex++;
                            }
                        }
                    }

                }
                else if($order == "random"){
                    //shuffle the students around the array to make sure the result is going to be truly random
                    shuffleArray($genderSorted);
                    //girls are going to be on the odd columns and boys on the even ones
                    $boysArrayIndex = 0;
                    $girlsArrayIndex = 0;
                    for($rows = sizeof($classLayout) - 1; $rows >= 0; $rows--){
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            if($col % 2 == 0){
                                $classLayout[$rows][$col] = $genderSorted[0][$boysArrayIndex];
                                $boysArrayIndex++;
                            }
                            else {
                                $classLayout[$rows][$col] = $genderSorted[1][$girlsArrayIndex];
                                $girlsArrayIndex++;
                            }
                        }
                    }

                }
            }
        }

        /* ARRANGE WITH BOYS AND GIRLS IN ALTERNATED ORDER */
        else if($genderPattern == "alternated"){
            $genderSorted = separateByGender($classroomNoGender);

            if(isset($_POST['order'])){
                $order = $_POST['order'];
                if($order == "alphabeticalHorizontal"){
                    $boysArraySorted = sortAlphabetically($genderSorted[0]);
                    $girlsArraySorted = sortAlphabetically($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    //logic for classrooms with an odd number of columns
                    if (sizeof($classLayout[0]) % 2 != 0)
                    {
                        $counter = 0;
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                if($counter % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                            }
                        }
                    }
                    else {
                        $evenRow = 0;
                        $counter = 0;
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                if($counter % 2 == 0 && $evenRow % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 != 0 && $evenRow % 2 == 0) {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 == 0 && $evenRow % 2 != 0){
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                            }
                            $evenRow++;
                        }

                    }
                }
                else if($order == "alphabeticalVertical"){
                    $boysArraySorted = sortAlphabetically($genderSorted[0]);
                    $girlsArraySorted = sortAlphabetically($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    if (sizeof($classLayout[0]) % 2 != 0)
                    {
                        $counter = 0;
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                                if($counter % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                            }
                        }
                    }
                    else {
                        $evenRow = 0;
                        $counter = 0;
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                                if($counter % 2 == 0 && $evenRow % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 != 0 && $evenRow % 2 == 0) {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 == 0 && $evenRow % 2 != 0){
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                            }
                            $evenRow++;
                        }
                    }
                }
                else if($order == "byidHorizontal"){
                    $boysArraySorted = sortByStudentId($genderSorted[0]);
                    $girlsArraySorted = sortByStudentId($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    if (sizeof($classLayout[0]) % 2 != 0)
                    {
                        $counter = 0;
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                if($counter % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                            }
                        }
                    }
                    else {
                        $evenRow = 0;
                        $counter = 0;
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                if($counter % 2 == 0 && $evenRow % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 != 0 && $evenRow % 2 == 0) {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 == 0 && $evenRow % 2 != 0){
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                            }
                            $evenRow++;
                        }
                    }
                }
                else if($order == "byidVertical"){
                    $boysArraySorted = sortByStudentId($genderSorted[0]);
                    $girlsArraySorted = sortByStudentId($genderSorted[1]);

                    $girlsArrayIndex = 0;
                    $boysArrayIndex = 0;

                    if (sizeof($classLayout[0]) % 2 != 0)
                    {
                        $counter = 0;
                        for($col = 0; $col < sizeof($classLayout); $col++){
                            for($row = sizeof($classLayout[0]) - 1; $row >=0 ; $row--){
                                if($counter % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                            }
                        }
                    }
                    else {
                        $evenRow = 0;
                        $counter = 0;
                        for($col = 0; $col < sizeof($classLayout[0]); $col++){
                            for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                                if($counter % 2 == 0 && $evenRow % 2 == 0){
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 != 0 && $evenRow % 2 == 0) {
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 == 0 && $evenRow % 2 != 0){
                                    $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                            }
                            $evenRow++;
                        }

                    }

                }
                else if($order == "random"){
                    //shuffle the students around the array to make sure the result is going to be truly random
                    shuffleArray($genderSorted);

                    $boysArrayIndex = 0;
                    $girlsArrayIndex = 0;
                    //$counter controls if we're getting a girl or a boy (odd for boys, even for girls)

                    if (sizeof($classLayout[0]) % 2 != 0)
                    {
                        $counter = 0;
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                if($counter % 2 == 0){
                                    $classLayout[$row][$col] = $genderSorted[1][$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $genderSorted[0][$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                            }
                        }
                    }
                    else {
                        $evenRow = 0;
                        $counter = 0;
                        for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                            for($col = 0; $col < sizeof($classLayout[0]); $col++){
                                if($counter % 2 == 0 && $evenRow % 2 == 0){
                                    $classLayout[$row][$col] = $genderSorted[1][$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 != 0 && $evenRow % 2 == 0) {
                                    $classLayout[$row][$col] = $genderSorted[0][$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else if($counter % 2 == 0 && $evenRow % 2 != 0){
                                    $classLayout[$row][$col] = $genderSorted[0][$girlsArrayIndex];
                                    $girlsArrayIndex++;
                                    $counter++;
                                }
                                else {
                                    $classLayout[$row][$col] = $genderSorted[1][$boysArrayIndex];
                                    $boysArrayIndex++;
                                    $counter++;
                                }
                            }
                            $evenRow++;
                        }
                    }
                }
            }
        }
        //if there's still students who don't have a seat, seat them on the next available seat
        //in this way, the pattern will not be followed 100%, but the optimal chart will be reached
        if(sizeof($girlsArraySorted) > 0){
            for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                for($col = 0; $col < sizeof($classLayout[0]); $col++){
                    if($classLayout[$row][$col] == ""){
                        $classLayout[$row][$col] = $girlsArraySorted[$girlsArrayIndex];
                        $girlsArrayIndex++;
                    }
                }
            }
        }

        if(sizeof($boysArraySorted) > 0){
            for($row = sizeof($classLayout)-1; $row >= 0; $row--){
                for($col = 0; $col < sizeof($classLayout[0]); $col++){
                    if($classLayout[$row][$col] == ""){
                        $classLayout[$row][$col] = $boysArraySorted[$boysArrayIndex];
                        $boysArrayIndex++;
                    }
                }
            }
        }

    }
}

//function to shuffle 2-dimensional arrays
function shuffleArray($inputArray)
{
    //add all the elements in the 2-dim array into a 1-dim array (a list)
    $studentsList = [];
    for ($row = 0; $row < sizeof($inputArray); $row++) {
        for ($column = 0; $column < sizeof($inputArray[0]); $column++) {
            $studentsList[] = $inputArray[$row][$column];
        }
    }

    //shuffle the list using the built-in shuffle() function
    shuffle($studentsList);

    //add the elements on the list back into a 2-dimensional array format
    $i = 0;
    for ($row = 0; $row < sizeof($inputArray); $row++) {
        for ($column = 0; $column < sizeof($inputArray[0]); $column++) {
            $inputArray[$row][$column] = $studentsList[$i];
            $i++;
        }
    }
}

function separateByGender($inputArray){
    $girls = [];
    $boys = [];

    for($i = 0; $i < sizeof($inputArray); $i++){
        //access the gender column of each object (each row in the student table)
        if(strtoupper($inputArray[$i]['Gender']) == "M"){
            $boys[] = $inputArray[$i];
        }
        else {
            $girls[] = $inputArray[$i];
        }
    }

    $genderSortedClass = [$boys, $girls];

    return $genderSortedClass;
}

//add the students from a class into the layout of the classroom
function generateClassLayout(){
    $classroomLayout = [];

    global $conn;

    $getClassroom = "SELECT RoomID FROM Class WHERE ClassID = '".$_POST['class']."'";

    $classroom = $conn->query($getClassroom);

    if($classroom->num_rows){
        while($row = $classroom->fetch_assoc()){
            $roomNumber = $row['RoomID'];
        }
    }

    $getRowsAndCols = "SELECT NumRows, NumCols FROM Room WHERE RoomID = '".$roomNumber."'";

    $rowsAndCols = $conn->query($getRowsAndCols);

    if($rowsAndCols->num_rows){
        while($row = $rowsAndCols->fetch_assoc()){
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

function getStudents(){
    global $conn; //access outer variable
    $selectedClass = $_POST['class'];
    //query to get all rows of the selectedClass
    $classQuery = "SELECT IS_User.UserID, IS_User.FirstName, IS_User.LastName, IS_User.Gender, IS_User.Role, IS_User_Class.ClassID 
            FROM IS_User_Class 
            INNER JOIN IS_User ON IS_User_Class.UserID = IS_User.UserID WHERE ClassID = '".$selectedClass."' AND Role = 'Student';";
    //run query

    //echo $classQuery;
    $class = $conn->query($classQuery);

    $classroom = [];

    if($class->num_rows > 0){
        while($row = $class->fetch_assoc()){
            //add all the students in the selected class into the $classroom array
            $classroom[] = $row;
        }
    }
    return $classroom;
}

//functions to sort an array alphabetically by the Last Name
function compareLastName($a, $b)
{
    return strcmp($a[LastName], $b[LastName]);
}

function compareStudentId($a, $b)
{
    return strcmp($a[UserID], $b[UserID]);
}

function sortAlphabetically($inputArray){
    usort($inputArray, "compareLastName");
    return $inputArray;
}

//function to sort an array by the studentid
function sortByStudentId($inputArray){
    usort($inputArray, "compareStudentID");
    return $inputArray;
}

?>
<body>
<form action = 'savechart.php' method = 'post'>
<?php require "includes".DIRECTORY_SEPARATOR."menu.php"; ?>

    <div>
        <script>
            function saveLayout() {
                //redirect user to dashboard
                document.location.href = 'myDashboard.php';
            }
            function backToForm() {
                document.location.href = "generatechart.php";
            }
        </script>

        <?php
        //build string with the output in a table
        $chartResult = "<table class=\"table table-bordered ".$row["ClassID"]."\">";
        $numOfColumns = sizeof($classLayout[0]);
        $numOfRows = sizeof($classLayout);
        for ($row = 0; $row < $numOfRows; $row++){
            $chartResult .= "<tr>";
            for($col = 0; $col < $numOfColumns; $col++){
                if(!empty($classLayout[$row][$col]["Gender"]))
                    $chartResult .= "<td><strong>".$classLayout[$row][$col]["FirstName"] . " " . $classLayout[$row][$col]["LastName"] . "</strong><br>" . $classLayout[$row][$col]["UserID"] . " (" . $classLayout[$row][$col]["Gender"] . ") </td>";
                else
                    $chartResult .= "<td><pre>        </pre></td>";
            }
            $chartResult .= "</tr>";
        }
        $chartResult .= "<tr><td id = 'blackboard' colspan = '".$numOfColumns."'><strong>BLACKBOARD</strong></td></tr>";
        $chartResult .= "</table>";

        echo $chartResult;

        echo "<br><br>";

        //serialize result array to send it to savechart
        $serializedChart = serialize($classLayout);

        ?>
            <input type = "hidden" name = "classId" value = "<?php echo $_POST["class"] ?>"/>
            <input type = "hidden" name = "saveChart" value = "<?php echo htmlentities($serializedChart); ?>"/>
            <div id="chartButton">
            <input id = 'backButton' class="btn btn-info" onclick = "backToForm();" type="button" name="backButton" value="Back"/>
            <input id = 'saveButton' class="btn btn-info" onclick = "saveLayout();" type="submit" name = "saveButton" value = 'Save'/>
            </div>
        </form>
    </div>
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
</body>
<?php
require "includes".DIRECTORY_SEPARATOR."footer.php";
?>

