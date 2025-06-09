<?php
require '../config.php';
require $project_vars['root'] . '/models/User.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}
$user = new User();
$admin = $user->getUser($_SESSION["user_id"]);
if (!($admin["admin"] == 1)) {
    header("Location: ../index.php");
    exit;
}
$users = $user->getUsers();
if (isset($_GET["search"])) {
    $users = $user->searchUsers($_GET["search"]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <title>Pen Master | Admin | Dashboard</title>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between p-2">
        <div class="d-flex">
            <a class="navbar-brand text-capitalize" href="../index.php">Pen Master</a>
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
                    <li class="nav-item text-capitalize"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item text-capitalize"><a class="nav-link" href="../profile.php"><?= "profile of " . $_SESSION["user_name"] ?></a></li>
                    <li class="nav-item text-capitalize"><a class="nav-link" href="list.php">admin dashboard</a></li>
                    <li class="nav-item text-capitalize"><a class="nav-link" href="../logout.php">log out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <a class="btn btn-primary" style="position:fixed;bottom:30px;right:30px;z-index:100;" href="../add_post.php">create post</a>
    <main class="container p-5">
        <table class="table table-responsive table-hover">
            <thead>
                <tr>
                    <td colspan="5"><?= $user->countRows() ?> users</td>
                    <td><a class="btn btn-primary text-capitalize" href="add.php">add user</a></td>
                </tr>
                <tr class="text-capitalize">
                    <td class="bg-dark text-light" style="width:5%">Id</td>
                    <td class="bg-dark text-light" style="width:20%">avatar</td>
                    <td class="bg-dark text-light" style="width:20%">name</td>
                    <td class="bg-dark text-light" style="width:25%">Email</td>
                    <td class="bg-dark text-light" style="width:5%">Admin</td>
                    <td class="bg-dark text-light" style="width:25%">Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $userEntry) {
                    $avatar = "../uploads/default_avatar.jpg";
                    if (file_exists("../uploads/" . $userEntry["name"] . ".jpg")) {
                        $avatar = "../uploads/" . $userEntry["name"] . ".jpg";
                    }
                ?>
                    <tr>
                        <td style="width:5%"><?= $userEntry['id'] ?></td>
                        <td style="width:20%"><img width="50px" height="50px" src="<?= $avatar ?>" alt="avatar"></td>
                        <td style="width:20%"><?= $userEntry['name'] ?></td>
                        <td style="width:25%"><?= $userEntry['email'] ?></td>
                        <td style="width:5%"><?= ($userEntry['admin']) ? 'yes' : 'no' ?></td>
                        <td style="width:25%">
                            <?php
                            if ($_SESSION["user_id"] != $userEntry['id']) {
                            ?>
                                <a class="btn btn-warning text-capitalize" href="edit.php?id=<?= $userEntry['id'] ?>">edit</a>
                                <a class="btn btn-danger text-capitalize" href="delete.php?id=<?= $userEntry['id'] ?>">delete</a>
                                <a class="btn btn-primary text-capitalize" href="../user_profile.php?user=<?= $userEntry['id'] ?>">profile</a>
                            <?php
                            } else {
                            ?>
                                <span class="text-capitalize">that's you </span>
                                <a class="btn btn-warning text-capitalize" href="../edit_user_data.php">edit my data</a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

</html>