<?php
// Posts functionality -- Required feature

include_once("../includes/db_connect.php");

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

    $data = array();

    for ($i = 0; $i < sizeof($resultArray); $i++) {
        $username = $db->query("SELECT username FROM users WHERE id = {$resultArray[$i]['user_id']}");
        //echo $username;
        $resultArray[$i]["username"] = $username->fetch_assoc();
    }

    echo json_encode($resultArray);
}
?>