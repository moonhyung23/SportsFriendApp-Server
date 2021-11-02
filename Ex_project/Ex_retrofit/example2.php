<?php
header("Content-type:application/json");
require_once 'dbcon.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_nick = $_POST['user_nick'];
    echo json_encode(array("user_nick" => "$user_nick"));
}