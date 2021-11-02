<!-- 이메일 인증 -->
<?php
header("Content-type:application/json");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_email = $_POST['user_email'];
    //1.JSON 변환
    echo  json_encode(array("user_email" => "$user_email"));

}        

