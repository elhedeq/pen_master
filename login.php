<?php
require 'config.php';
require $project_vars['root'] . '/models/User.php';
session_start();
if (isset($_SESSION["user_name"])) {
    header("Location: index.php");
    exit;
}
$user = new User();

# if login fields submitted, authenticate
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    if (!(isset($_POST["email"]) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
        $errors["email"] = 1;
    }
    if (!(isset($_POST["password"]) && !empty($_POST["password"]))) {
        $errors["password"] = 1;
    }
    if (!$errors) {
        #get user of submitted emial
        $userEntry = $user->getUserByEmail($_POST["email"]);
        if ($userEntry) {
            # verify password
            if (!password_verify($_POST["password"], $userEntry["password"])) {
                $errors["password"] = 2;
            } else { # if user authenticated set session variables and redirect
                $_SESSION["user_id"] = $userEntry["id"];
                $_SESSION["user_name"] = $userEntry["name"];
                header("Location: index.php");
            }
        } else {
            $errors["email"] = 2;
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
    <title>Pen Master | Log In</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between p-2">
        <div class="d-flex">
            <a class="navbar-brand text-capitalize" href="index.php">Pen Master</a>
        </div>
        <div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item text-capitalize"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item text-capitalize"><a class="nav-link" href="#">Log In</a></li>
                    <li class="nav-item text-capitalize"><a class="nav-link" href="register.php">register</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container pt-5">
        <form class="border rounded-3 p-5" action="#" method="post">
            <h2 class="text-capitalize">log in</h2>
            <div class="form-group pb-3">
                <label class="text-capitalize" for="email">email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= (isset($_POST["email"])) ? $_POST["email"] : "" ?>">
                <p class="form-text text-danger"><?= (isset($errors["email"]) && $errors["email"] == 1) ? "enter email please" : "" ?></p>
                <p class="form-text text-danger"><?= (isset($errors["email"]) && $errors["email"] == 2) ? 'email not registered <a href="register.php">register</a>' : "" ?></p>
            </div>
            <div class="form-group pb-3">
                <label class="text-capitalize" for="password">password:</label>
                <input type="password" class="form-control" name="password" id="password">
                <p class="form-text text-danger"><?= (isset($errors["password"]) && $errors["password"] == 1) ? "enter a password please" : "" ?></p>
                <p class="form-text text-danger"><?= (isset($errors["password"]) && $errors["password"] == 2) ? "wrong password" : "" ?></p>
            </div>
            <input type="submit" class="btn btn-primary" value="Log In">
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>