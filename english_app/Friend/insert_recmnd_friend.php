<?php 
/* 친구 추천 엑티비티에서 친구 신청 */

//1.db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();


if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

$my_id = $_POST['my_id']; //내 아이디(친구 신청 보내는 사람 id)
$receive_id = $_POST['receive_id']; //친구 신청 받는 사람 id
settype($my_id, 'integer');
settype($receive_id, 'integer');


//db에 데이터 저장 요청
$sql_insert = "INSERT INTO all_send_receive(send_id, receive_id, date) VALUES (
$my_id, 
$receive_id, 
NOW()
)";

$result_insert = mysqli_query($con, $sql_insert);

if($result_insert === true){ 
    //결과가 성공일때 실행되는 코드
    echo '추가성공';
}else {
  //결과가 실패일때 실행되는 코드
}
//db 종료
 $con->close();
  





}


?>