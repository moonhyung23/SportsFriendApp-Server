<?php 

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();
// json형식으로 변환할 배열
$data = array();
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

// 내 아이디
$my_id = $_POST['my_id'];
settype($my_id, 'integer');

// 1.전체 친구 신청 목록을 조회한다.
$sql_select_all = "select * from all_send_receive";
$result_all = mysqli_query($conn, $sql_select_all);
// 1.receive_id == my_id인 행을 찾는다 (테이블에서)
while($row_all = mysqli_fetch_array($result_all)){

    if($row_all['receive_id'] == $my_id){
    // 2.찾은 행에서 send_id를 변수에 저장한다.
    $send_id = $row_all['send_id'];
    settype($send_id, 'integer');
    // 3.send_id를 저장한 변수를 이용해 user 테이블에서
    // send_id == id(컬럼)가 같은 행을 찾는다.
    $sql_send_user_infor = "select * from user where id = $send_id";
    $result_send_infor = mysqli_query($conn, $sql_send_user_infor);
    $row_send_infor = mysqli_fetch_array($result_send_infor);
    // 4.찾은 행(내가 받은 친구 신청 정보)을 배열에 저장한다.
    array_push($data, 
    array(
    'id'=>$row_send_infor['id'], //친구신청 보낸사람 사용자 id
    'nickname'=>$row_send_infor['nickname'],//닉네임
    'img_profile'=>$row_send_infor['img_profile'],//프로필 url
    ));
}
}
    // 5.저장한 배열을 json으로 변환
    header('Content-Type: application/json; charset=utf8');
    $json = json_encode(array("send_array"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    //6.안드로이드에 전송
    echo $json;

}
?>