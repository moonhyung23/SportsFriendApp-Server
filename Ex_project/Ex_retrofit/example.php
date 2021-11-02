<?php
header("Content-type:application/json");
require_once 'example_con.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $nick = $_POST['nick'];
    $pwd = $_POST['pwd'];
    echo json_encode(array("id" => "$id" , "pwd" => "$pwd", "nick" => "$nick"));
}