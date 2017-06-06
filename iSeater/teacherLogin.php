<?php
session_start();
require "includes/databaseConnection.php";

if (isset($_POST['submit']))
{
        $query = "SELECT * FROM IS_User WHERE UserID = '" .$_POST['user']. "'AND Password = '".$_POST['pass']."';";
        $result = $conn->query($query);
        if($result->num_rows == 1)
        {
            $fNameQuery = "SELECT FirstName FROM IS_User WHERE UserID = '" .$_POST['user']. "'AND Password = '".$_POST['pass']."';";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $_SESSION['is_valid_user'] = $_POST['user'];
            $_SESSION['signed_in_as'] = $row['FirstName'];
            header('Location: myDashboard.php');
        }
        else
        {
            $_SESSION['error'] = "Error! Invalid username or password.";
            header('Location: index.php');
        }
        $loginError .= "</div>";
        mysqli_close($conn);

}
?>
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
