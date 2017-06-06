<?php
session_start();
require "includes".DIRECTORY_SEPARATOR."functions.php";
hasAccess();

$title = "iSeater - Generate Chart";
$user = "Aline";
require "includes".DIRECTORY_SEPARATOR."head.php";
require "includes".DIRECTORY_SEPARATOR."databaseConnection.php";
?>
    <body>
    <?php require "includes".DIRECTORY_SEPARATOR."menu.php"; ?>
    <div  id = "generateChartForm">
    <form action = "output.php" method = "post">
        <span class = "formLabel">Select a class: </span>
        <select name="class" required>
            <option disabled selected value> - </option>
            <?php
            //populate this dropdown with the existing classes
            $selectClasses = "SELECT DISTINCT ClassID FROM Class;";
            $result = $conn->query($selectClasses);

            if ($result->num_rows){
                $optionsStr = "";
                while($row = $result->fetch_assoc()) {
                    $optionsStr.= "<option name = 'class' value = '".$row["ClassID"]."'>".$row["ClassID"]."</option>";
                }
                echo $optionsStr;
            }
            ?>
        </select>
<!--        <br><br>-->
<!--        <span class = "formLabel">Select a term: </span>-->
<!--        <select name="term">-->
<!--            --><?php
//            //populate this dropdown with the existing classes
//            $selectTerm = "SELECT DISTINCT Term FROM Class;";
//            $result = $conn->query($selectTerm);
//
//            if ($result->num_rows){
//                $optionsStr = "";
//                while($row = $result->fetch_assoc()) {
//                    $optionsStr.= "<option name = 'term' value = '".$row["Term"]."'>".$row["Term"]."</option>";
//                }
//                echo $optionsStr;
//            }
//            ?>
<!--        </select>-->
        <br><br><br>
        <span class = "formSection">Gender Pattern</span>
        <br><br><br>
        <div class = "genderGroup">
            <div class = "genderBox">
                <div class = "gender"><img alt="no gender" src="images/nogenderf.png"></div><br>
                <span class = "formLabel">No Restriction</span><br>
                <input type="radio" name="gender" value="nogender" checked>
            </div>
            <div class = "genderBox">
                <div class = "gender"><img alt="Girls - Boys" src="images/girlboyf.png"></div><br>
                <span class = "formLabel">Girls - Boys</span><br>
                <input type="radio" name="gender" value="girlsboys" checked>
            </div>
            <div class = "genderBox">
                <div class = "gender"><img alt="Boys - Girls" src="images/boygirlf.png"></div><br>
                <span class = "formLabel">Boys - Girls</span><br>
                <input type="radio" name="gender" value="boysgirls" checked>
            </div>
            <div class = "genderBox">
                <div class = "gender"><img alt="alternated" src="images/everyotherf.png"></div><br>
                <span class = "formLabel">Alternated</span><br>
                <input type="radio" name="gender" value="alternated" checked>
            </div>
        </div>
        <br><br><br>
        <span class = "formSection">Order</span>
        <br><br>
        <div class = "orderGroup">
            <div class="orderBox">
                <div class = "order"><img alt="alphabetical horizontal" src="images/alphabeticalhorizontalf.png"></div><br>
                <span class = "formLabel">Alphabetical Order<br>(Horizontal)</span><br>
                <input type="radio" name="order" value="alphabeticalHorizontal" checked>
            </div>
            <div class="orderBox">
                <div class = "order"><img alt="alphabetical vertical" src="images/alphabeticalverticalf.png"></div><br>
                <span class = "formLabel">Alphabetical Order<br>(Vertical)</span><br>
                <input type="radio" name="order" value="alphabeticalVertical" checked>
            </div>
            <div class="orderBox">
                <div class = "order"><img alt="id horizontal" src="images/idhorizontalf.png"></div><br>
                <span class = "formLabel">ID Order<br>(Horizontal)</span><br>
                <input type="radio" name="order" value="byidHorizontal" checked>
            </div>
            <div class="orderBox">
                <div class = "order"><img alt="alphabetical horizontal" src="images/idverticalf.png"></div><br>
                <span class = "formLabel">ID Order<br>(Vertical)</span><br>
                <input type="radio" name="order" value="byidVertical" checked>
            </div>
            <div class="orderBox">
                <div class = "order"><img alt="alphabetical horizontal" src="images/randomf.png"></div><br>
                <span class = "formLabel">Random Order<br></span><br>
                <input type="radio" name="order" value="random" checked>
            </div>

            <!--<br><br>
            <div class = "ordergroup">-->

        </div>   <!--<div class="orderBox">
                <div class = "order"><img alt="alphabetical horizontal" src="images/manualf.png"></div><br>
                <span class = "genderSelection">Manual Order<br></span><br>
                <input type="radio" name="order" value="manual" checked>
            </div>*/-->

        <br><br><br><br><br>

        <input name = "generateChart" class = "submitButton" type="submit" value="Generate Chart">
        <!--</div>-->
    </form>
    </div>
    <a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
    </body>
<?php
require "includes".DIRECTORY_SEPARATOR."footer.php";
?>