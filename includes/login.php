<?php
// Login functionality
include_once("db_connect.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(stripslashes(trim($_POST["username"])));
    $password = htmlspecialchars(stripslashes(trim($_POST["password"])));

    $sql = "SELECT * FROM users WHERE username = '{$username}'";
    echo $sql;
    $result = $db->query($sql);

    if ($result->num_rows == 0) {
        header("Location: ../index.php?err", true);
        die();
    }

    $data = $result->fetch_assoc();


    if (password_verify($password, $data["password"])) {
        $_SESSION["username"] = $username;
    } else {
        header("Location: ../index.php?err", true);
        die();
    }
        

    $_SESSION["username"] = $username;
    header("Location: ../index.php", true);
    die();

}

?>