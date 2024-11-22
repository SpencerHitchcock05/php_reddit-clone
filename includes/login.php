<?php
// Login functionality
include_once("db_connect.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(stripslashes(trim($_POST["username"])));
    $password = htmlspecialchars(stripslashes(trim($_POST["password"])));

    $sql = "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'";
    echo $sql;
    $result = $db->query($sql);

    echo print_r($result->fetch_assoc());
    if ($result->num_rows == 0) {
        header("Location: ../index.php?err");
        die();
    }
        

    $_SESSION["username"] = $username;
    header("Location: ../index.php");
    die();

}

?>