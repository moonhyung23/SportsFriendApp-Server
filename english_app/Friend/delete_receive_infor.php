<?php
//1.db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();


if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    
// 2.안드로이드에서 보낸 사람 id와 내 id를 받는다.
$my_id = $_POST['my_id'];
$send_id = $_POST['send_id'];
//integer 형변환
settype($my_id, 'integer');
settype($send_id, 'integer');

//3. 내 ID와 보낸 사람 id가 같은 행을 테이블에서 삭제
// $send_id => (send_id) $my_id => (receive_id) 
$sql_delete="DELETE from all_send_receive 
WHERE send_id = $send_id and receive_id = $my_id";

$result_delete = mysqli_query($con,$sql_delete);

// 4.안드로이드에 응답
if($result_delete === true){ // 결과 확인 코드
  //결과가 성공일때 실행되는 코드
   echo "거절성공";                        
}else {
  //결과가 실패일때 실행되는 코드
  echo "거절실패";
}

}




?>