<?php
require 'config.php';
require $project_vars['root'] . '/models/Post.php';
require $project_vars['root'] . '/models/User.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
$blogpost = new Post();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    if (!(isset($_POST["title"]) && !empty($_POST["title"]))) {
        $errors["title"] = 1;
    }
    if (!(isset($_POST["content"]) && !empty($_POST["content"]))) {
        $errors["content"] = 1;
    }
    if (!$errors) {
        $title = trim($_POST["title"]);
        $content = trim($_POST["content"]);

        $post = [
            'title' => $title,
            'content' => $content,
            'writer' => $_SESSION["user_id"]
        ];

        if ($blogpost->addPost($post)) {
            header("location: profile.php");
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
    <link rel="stylesheet" href="suneditor-2.47.5/dist/css/suneditor.min.css">
    <title>Pen Master | Create Post</title>
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
                    <?php
                    if (isset($_SESSION["user_name"])) {
                        $user = new User();
                        $userEntry = $user->getUser($_SESSION["user_id"]);
                    ?>
                        <li class="nav-item text-capitalize"><a class="nav-link" href="profile.php"><?= "profile of " . htmlspecialchars($_SESSION["user_name"]) ?></a></li>
                        <?php
                        $user = new User();
                        $userEntry = $user->getUser($_SESSION["user_id"]);
                        if ($userEntry["admin"] == 1) {
                        ?>
                            <li class="nav-item text-capitalize"><a class="nav-link" href="admin/list.php">admin dashboard</a></li>
                        <?php
                        }
                        ?>
                        <li class="nav-item text-capitalize"><a class="nav-link" href="logout.php">Log Out</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container pt-5">
        <form class="border rounded-3 p-5" action="#" method="post">
            <h2 class="text-capitalize">create post</h2>
            <div class="form-group pb-3">
                <label class="text-capitalize" for="title">title:</label>
                <input type="text" class="form-control" name="title" value="<?= isset($_POST["title"]) ? $_POST["title"] : "" ?>">
                <p class="form-text text-danger"><?= isset($errors["title"]) ? "enter title please" : "" ?></p>
            </div>
            <div class="form-group pb-3">
                <label class="text-capitalize" for="content">content:</label>
                <textarea name="content" class="form-control" id="content"><?= isset($_POST["content"]) ? $_POST["content"] : "" ?></textarea>
                <p class="form-text text-danger"><?= isset($errors["content"]) ? "enter post content please" : "" ?></p>
            </div>
            <input type="submit" class="btn btn-primary" id="submit" value="Post">
        </form>
    </main>
    <script src="suneditor-2.47.5/dist/suneditor.min.js"></script>
    <script>
        // Initialize SunEditor
        const editor = SUNEDITOR.create(document.getElementById('content'), {
            height: '300px',
            buttonList: [
                ['undo', 'redo'],
                ['bold', 'italic', 'underline', 'strike'],
                ['font', 'fontSize', 'fontColor', 'hiliteColor', 'align', 'list', 'table'],
                ['link'],
                ['fullScreen', 'preview']
            ]
        });
        document.getElementById("submit").addEventListener("mouseover", () => {
            editor.save()
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>