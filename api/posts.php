<?php
// Posts functionality -- Required feature

include_once("../includes/db_connect.php");

session_start();

//return all posts in the database to the user
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM posts";

    $result = $db->query($sql);

    $resultArray = array();

    
    if ($result->num_rows == 0) {
        echo json_encode($resultArray);
        die();
    } 

    while ($row = $result->fetch_assoc()) {
        array_push($resultArray, $row);
    }


    for ($i = 0; $i < sizeof($resultArray); $i++) {
        $username = $db->query("SELECT username FROM users WHERE id = {$resultArray[$i]['user_id']}");
        //echo $username;
        $resultArray[$i]["username"] = $username->fetch_assoc()["username"];
    }

    echo json_encode($resultArray);
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
// post new post to the database
    $input = json_decode(file_get_contents("php://input"), true);

    $title = htmlspecialchars(stripslashes(trim($input["title"])));
    $content = htmlspecialchars(stripslashes(trim($input["content"])));

    if ($title == "" || $content == "") {
        http_response_code(400);
        die();
    }

    $username = $_SESSION["username"];

    $id = $db->query("SELECT id FROM users WHERE username = '$username'")->fetch_assoc()["id"];

    $stmt = $db->prepare("INSERT INTO posts (user_id, title, content) VALUE (?,?,?)");

    $stmt->bind_param("iss", $id, $title, $content);

    $stmt->execute();

    echo print_r($input);
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
//delete post from database
    $input = json_decode(file_get_contents("php://input"), true);

    $id = htmlspecialchars(trim(stripslashes($input["id"])));

    $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");

    $stmt->bind_param("i",$id);

    $stmt->execute();

    echo json_encode(["success" => true]);
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
// edit post inside the database
    $input = json_decode(file_get_contents("php://input"), true);

    $id = htmlspecialchars(stripslashes(string: trim($input["id"])));
    $title = htmlspecialchars(stripslashes(string: trim($input["title"])));
    $content = htmlspecialchars(stripslashes(string: trim($input["content"])));

    if ($id == "" || $title == "") {
        http_response_code(400);
        die();
    }

    $stmt = $db->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");

    $stmt->bind_param("ssi", $title, $content, $id);

    $stmt->execute();

}
?>