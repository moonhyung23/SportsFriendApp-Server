<?php 

session_start(); // 세션
//세션 제거
if($_SESSION['id']!=null){
   session_destroy();
}
echo "<script>location.href='../page/Main.php';</script>";

?>