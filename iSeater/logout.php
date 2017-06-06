<?php
session_start();
$_SESSION['logout'] = "You have successfully signed out.";
header("Location: index.php");
?>
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
