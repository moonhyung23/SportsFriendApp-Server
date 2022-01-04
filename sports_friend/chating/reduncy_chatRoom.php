<?php 
// ! 채팅 참가자 중복체크
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";

      $user_idx = $_POST['user_idx'];  //채팅방 작성자 idx  
      $invite_idx_split = $_POST['invite_idx_split']; //채팅방 작성자 idx 번호 

}
?>