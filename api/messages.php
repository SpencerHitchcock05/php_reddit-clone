<?php
// Messages functionality -- Required feature

    include_once("../includes/db_connect.php");

    session_start();
// get messages from database and return to user
    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        $username = $_SESSION["username"];

        $sql = "SELECT id FROM users WHERE username = '$username'";

        $id = $db->query($sql)->fetch_assoc()["id"];

        $data = array();

        $sql = "SELECT * FROM messages WHERE sender_id = '$id'";

        $sentMessages = array();
        
        $result = $db->query($sql);

    

        while ($row = $result->fetch_assoc()) {

            $receiver_id = $row["receiver_id"];
            
            $sql = "SELECT username FROM users WHERE id = '$receiver_id'";

            $receiverUsername = $db->query($sql)->fetch_assoc()["username"];
            
            $row["receiver_name"] = $receiverUsername;

            array_push($sentMessages, $row);
        }

        $data["sent"] = $sentMessages;




        $sql = "SELECT * FROM messages WHERE receiver_id = '$id'";

        $receivedMessages = array();
        
        $result = $db->query($sql);

        

        while ($row = $result->fetch_assoc()) {

            $sender_id = $row["sender_id"];
            
            $sql = "SELECT username FROM users WHERE id = '$sender_id'";

            $senderUsername = $db->query($sql);

            if ($senderUsername->num_rows == 0) {
                break;
            }
             
            $senderUsername = $senderUsername->fetch_assoc()["username"];
            
            $row["sender_name"] = $senderUsername;

            array_push($receivedMessages, $row);
        }


        $data["received"] = $receivedMessages;



        echo json_encode($data);



// post a message to the database
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $input = json_decode(file_get_contents("php://input"), true);

        $content = htmlspecialchars(stripslashes(trim($input["content"])));

        $receiver = $input["receiver"];


        if ($content == "" || $receiver == "") {
            die();
        }



        $username = $_SESSION["username"];

        $result = $db->query("SELECT id FROM users WHERE username = '$username'");

        $id = $result->fetch_assoc()["id"];

        

        $result = $db->query("SELECT id FROM users WHERE username = '$receiver'");

        if ($result->num_rows <= 0) {
            die();
        }

        $data = $result->fetch_assoc();

        $receiver_id = $data["id"];

        $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?,?,?)");

        $stmt->bind_param("iis", $id, $receiver_id, $content);

        $stmt->execute();
        
    }
?>