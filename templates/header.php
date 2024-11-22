<?php
// Header functionality

session_start();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="assets/styles.css" rel="stylesheet">
    <title>forum</title>

</head>
<body>
    <nav class="w-100 bg-white border-bottom border d-flex justify-content-between align-items-center">
        <a class="h-75 mx-3" href="index.php">
            <img id="logo" src="img/Logo.png">
        </a>
        <div class="d-flex">
            <?php if (!isset($_SESSION["user"])):?>
                <p class="mx-3">Hi, <?php /*echo $_SESSION["user"]*/ ?></p>
                <div class="d-flex">
                    <a class="mx-3" href="messages.php">Messages</a>
                    <a class="mx-3" href="new-post.php">Create New Post</a>
                    <a class="mx-3" href="includes/logout.php">Logout</a>
                </div>
            <?php endif;?>
        </div>
    </nav>
