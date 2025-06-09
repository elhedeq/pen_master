<?php
require '../config.php';
require $project_vars['root'].'/models/User.php';
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}
$user = new User();
$admin = $user->getUser($_SESSION["user_id"]);
if($admin["admin"] != 1) {
    header("Location: ../index.php");
    exit;
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
$update_user = $user->getUser($id);
if (!$update_user) {
    header('Location: list.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    $errors = [];
    $users = $user->getUsers();
    $registeredEmails = array();
    foreach ($users as $userEntry) {
        array_push($registeredEmails,$userEntry["email"]);
    }
    $userEntry = $user->getUser($id);    unset($registeredEmails[array_search($userEntry["email"],$registeredEmails)]);
    if (!(isset($_POST["name"]) && !empty($_POST["name"]))) {
        $errors["name"] = 1;
    } else if (!$user->is_valid_name($_POST['name'])) {
        $errors["name"] = 2;
    }
    if (!(isset($_POST["email"]) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
        $errors["email"] = 1;
    } else if(in_array($_POST["email"],$registeredEmails)) {
        $errors["email"] = 2;
    }
    if(!$errors){
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $admin = $_POST["admin"]==="1"?1:0;
        $userData = [
            'name' => $name,
            'email' => $email,
            'admin' => $admin
        ];
        if(isset($_POST["password"]) && !empty($_POST["password"])){
            $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT);
            $userData["password"] = $password;
        }
        if($user->updateUser($id,$userData)) {
            header("location: list.php");
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Pen Master | Admin | Edit User</title>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between p-2">
        <div class="d-flex">
        <a class="navbar-brand text-capitalize" href="../index.php">Pen Master</a>
        </div>
        <div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item text-capitalize"><a class="nav-link" href="../index.php">Home</a></li>
    <?php
    if(isset($_SESSION["user_name"])){
        $user = new User();
        $userEntry = $user->getUser($_SESSION["user_id"]);
    ?>
    <li class="nav-item text-capitalize"><a class="nav-link" href="../profile.php"><?="profile of ".$_SESSION["user_name"]?></a></li>
    <?php
        $user = new User();
        $userEntry = $user->getUser($_SESSION["user_id"]);
        if($userEntry["admin"] == 1){
    ?>
    <li class="nav-item text-capitalize"><a class="nav-link" href="list.php">admin dashboard</a></li>
    <?php
        }
    ?>
    <li class="nav-item text-capitalize"><a class="nav-link" href="../logout.php">Log Out</a></li>
    <?php
    }
        ?>
            </ul>
        </div>
        </div>
</nav>
    <main class="container p-5">
    <form class="border rounded-3 p-5" method="POST" action="#">
                <h2 class="text-capitalize">edit user</h2>
        <input type="hidden" name="id" value="<?= isset($update_user["id"])?$update_user["id"]:""?>">
        <div class="form-group pb-3">
            <label class="text-capitalize" for="name">name:</label>
            <input type="text" class="form-control" name="name" value="<?= isset($_POST["name"]) ? $_POST["name"] : (isset($update_user["name"])?$update_user["name"]:"") ?>">

                <p class="form-text text-danger"><?= isset($errors["name"]) && $errors['name'] === 1 ? "enter your name please" : "" ?></p>
                <p class="form-text text-danger"><?= isset($errors["name"]) && $errors['name'] === 2 ? "name can only include characters, numbers, spaces and dash and underscore" : "" ?></p>        </div>
        <div class="form-group pb-3">
            <label class="text-capitalize" for="email">email:</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= isset($_POST["email"]) ? $_POST["email"] : (isset($update_user["email"])?$update_user["email"]:"") ?>">
            <p class="form-text text-danger"><?= isset($errors["email"])&&$errors["email"]===1? "enter a valid email please": "" ?></p>
            <p class="form-text text-danger"><?= isset($errors["email"])&&$errors["email"]===2? 'email already registered': "" ?></p>
        </div>
        <div class="form-group pb-3">
            <label class="text-capitalize" for="password">password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>
        <div class="form-group">
            <p class="text-capitalize">admin?</p>
        <div class="form-check pb-3">
            <input type="radio" class="form-check-input" name="admin" value="1" <?= isset($_POST["admin"]) && $_POST["admin"]==="1"?'checked':(isset($update_user["admin"])&&$update_user["admin"]==1?"checked":"") ?> id="yes">
            <label class="form-check-label" for="yes">yes</label>
        </div>
            <div class="form-check pb-3">
            <input type="radio" class="form-check-input" name="admin" value="0" <?=  (isset($_POST["admin"]) && $_POST["admin"]==="1") || (isset($update_user["admin"])&&$update_user["admin"]==1)?'':'checked' ?> id="no">
            <label class="form-check-label" for="no">no</label>
        </div>
            </div>
        <input type="submit" class="btn btn-primary" id="submit" value="update">
    </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>