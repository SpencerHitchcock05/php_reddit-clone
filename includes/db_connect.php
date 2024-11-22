<?php
// Db_connect functionality

include_once("config.php");

$db = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

if ($db->connect_error) {
    die($db->connect_error);
}


?>