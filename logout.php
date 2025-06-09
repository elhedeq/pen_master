<?php
# starting session to access its variables
session_start();
# empty session variables and end session
$_SESSION = [];
session_destroy();
header("Location: index.php");
?>