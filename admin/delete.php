<?php
require '../config.php';
require $project_vars['root'].'/models/User.php';
session_start();
if(isset($_SESSION["user_id"]) && $_SESSION["user_id"] != $_GET["id"]) {
    $user = new User();
    $admin = $user->getUser($_SESSION["user_id"]);
    if($admin["admin"] == 1) {
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $user->deleteUser($id);
        header('Location:list.php');
        exit;
    }
}

header('Location:../index.php');

?>