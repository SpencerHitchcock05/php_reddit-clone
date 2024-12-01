<?php
// Login functionality
include_once("db_connect.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(stripslashes(trim($_POST["username"])));
    $password = htmlspecialchars(stripslashes(trim($_POST["password"])));
    $confirm = htmlspecialchars(stripslashes(trim($_POST["confirm"])));

    if ($password != $confirm) {
        header("Location: ../index.php");
        die();
    }

    if ($password == "") {
        header("Location: ../index.php");
        die();
    }

    $sql = "SELECT * FROM users WHERE username = '{$username}'";

    $result = $db->query($sql);

    if ($result->num_rows != 0) {
        header("Location: ../index.php");
        die();
    }

    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?,?)");

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt->bind_param("ss", $username, $hash);

    $stmt->execute();
        

    $_SESSION["username"] = $username;
    header("Location: ../index.php");
    die();

}

?>