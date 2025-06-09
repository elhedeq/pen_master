<?php
require 'config.php';
require $project_vars['root'] . '/models/Post.php';
require $project_vars['root'] . '/models/User.php';
# starting session to access its variables
session_start();
$blogpost = new Post();
if (isset($_GET["post"])) {
    $post = $blogpost->getPost($_GET["post"]);
} else {
    $posts = $blogpost->getPosts();
    if (isset($_GET["search"]))
        $posts = $blogpost->searchPosts($_GET["search"]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Pen Master</title>
</head>

<body>
    <!-- nav start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between p-2">
        <div class="d-flex">
            <a class="navbar-brand text-capitalize" href="index.php">Pen Master</a>
            <form method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control rounded" placeholder="Search">
                    <input type="submit" class="btn btn-primary" value="search">
                </div>
            </form>
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
                    } else {
                    ?>
                        <li class="nav-item text-capitalize"><a class="nav-link" href="login.php">Log In</a></li>
                        <li class="nav-item text-capitalize"><a class="nav-link" href="register.php">register</a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    if (isset($_SESSION["user_name"])) {
    ?>
        <a class="btn btn-primary" style="position:fixed;bottom:30px;right:30px;z-index:100;" href="add_post.php">create post</a>
    <?php
    }
    ?>
    <main class="container pt-5">
        <?php
        if (isset($posts)) {
            foreach ($posts as $post) {
        ?>
                <section class="card rounded-6 w-100 m-2 float-start">
                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($post['title']) ?></h3>
                        <p class="card-text"><?= substr($post['content'], 0, 200) ?> </p>
                        <div class="d-flex">
                            <p class="text-muted w-50">at <?= $post['date_created'] ?></p>
                            <p class="text-muted w-50">by <a href="user_profile.php?user=<?=$post['writerid']?>"><?= htmlspecialchars($post['name']) ?></a></p>
                        </div>

                        <a class="btn btn-primary" href="?post=<?= $post['id'] ?>">continue reading</a>
                    </div>
                </section>
            <?php
            }
        } else {
            ?>
            <section class="card rounded-6 w-100 m-2">
                <div class="card-body">
                    <h2 class="card-title"><?= htmlspecialchars($post['title']) ?></h2>
                    <p class="card-text"><?= $post['content'] ?></p>
                    <div class="d-flex">
                        <p class="text-muted w-50">at <?= $post['date_created'] ?></p>
                        <p class="text-muted w-50">by <a href="user_profile.php?user=<?=$post['writerid']?>"><?= htmlspecialchars($post['name']) ?></a></p>
                    </div>
                </div>
            </section>
        <?php
        }
        ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>