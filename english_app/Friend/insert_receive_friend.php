<?php 
/* 친구 추가 해주는 php 파일 */

//1.db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();


if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
  
//2.안드로이드에서 추가할 친구 id, 내 id 갖고오기(POST)
$my_id = $_POST['my_id'];
$send_id = $_POST['send_id']; //친구 신청을 보낸 사람의 id 
//integer 형변환
settype($my_id, 'integer');
settype($send_id, 'integer');


/* 에러검사 
 * 1) 친구 등록 시 보낸사람이 취소를 이미 한 경우 
*/
$sql_select = "select * from all_send_receive 
where send_id = $send_id and receive_id = $my_id";
$result = mysqli_query($con, $sql_select);
$num = mysqli_num_rows($result);
//이미 신청을 취소한 경우 (num == 0)
if($num == 0){
 echo '신청취소';
 return;
}


/* 친구 등록시 테이블에  2번 추가해야한다.
  * my_id => 내 아이디
  * my_id => 상대의 아이디 
*/

//3.DB에 친구정보 테이블(friend)에 추가
// 3-1)내 아이디 추가 
$sql_insert_me = "INSERT INTO friend (my_id, friend_id, created) VALUES (
$my_id, 
$send_id, 
NOW()
)";
$result_insert = mysqli_query($con, $sql_insert_me);

//3-2) 상대의 아이디 추가
$sql_insert_you = "INSERT INTO friend (my_id, friend_id, created) VALUES (
  $send_id, 
  $my_id, 
  NOW()
  )";
  $result_insert = mysqli_query($con, $sql_insert_you);


//3. 내 ID와 보낸 사람 id가 같은 행을 테이블에서 삭제
// $send_id => (send_id) $my_id => (receive_id) 
$sql_delete="DELETE from all_send_receive 
WHERE send_id = $send_id and receive_id = $my_id";

$result_delete = mysqli_query($con,$sql_delete);

//4.안드로이드에 응답
if($result_insert === true){ 
    echo '추가성공';
    //결과가 성공일때 실행되는 코드

}else {
    echo '추가실패';
  //결과가 실패일때 실행되는 코드
}
//db 종료
 $con->close();
}
?>