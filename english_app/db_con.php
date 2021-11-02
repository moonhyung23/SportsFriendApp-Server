<?php

function dbconn()
{
  $host_ip="127.0.0.1";
  $db_user="moon3";
  $db_pw="102589!";
  $db_name="english";
  $con = mysqli_connect($host_ip,$db_user,$db_pw, $db_name);//mysql연결

  if($con->connect_errno){
    die('connect error : '.$con->connect_error);
  }
  return $con; //호출한 페이지 종료 후 호출한 페이지로 넘어감
}

?>