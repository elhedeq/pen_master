<?php
require 'config.php';
require $project_vars['root'] . '/models/Post.php';
session_start();
if (isset($_SESSION["user_id"])) {

    $blogpost = new Post();

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    echo $blogpost->deletePost($id);
    header('Location:profile.php');
} else {
    header("Location: index.php");
}
?>