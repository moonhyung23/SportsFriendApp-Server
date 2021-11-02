<?php 

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

$my_id = $_POST['my_id'];//보내는 사람의 id (send_id)
$receive_id = $_POST['receive_id']; //받는 사람 id (receive_id)
settype($my_id, 'integer');
settype($receive_id, 'integer');

/* 에러 검사 
 * 이미 등록된 친구인 경우 
*/
$sql_select = "select * from friend where my_id = $my_id and friend_id = $receive_id";
$result = mysqli_query($conn, $sql_select);
$num = mysqli_num_rows($result);
// 이미 등록된 친구 
if($num != 0 ){
 echo '이미등록된친구';
 return;
}

// 내 id와  받은 사람 id가 같은 행을 삭제
$sql_delete = "DELETE FROM all_send_receive 
WHERE send_id = $my_id and receive_id = $receive_id";
$result_delete = mysqli_query($conn, $sql_delete);

//삭제 성공
if($result_delete == true){
echo '삭제성공';
}else{//삭제 실패
echo '삭제실패';
}

}
?>