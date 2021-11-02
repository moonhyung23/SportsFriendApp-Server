<?php 
/* 친구 신청 요청을 해주는 php 파일 */

//1.db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){


//2.등록할 친구의 닉네임, 나의 id  얻기
$f_nickname = $_POST['f_nickname'];
$my_id = $_POST['my_id'];
// id  int로 변환 
settype($my_id, 'integer');

//3.닉네임을 통해서 추가할 친구의 id를 찾는다.
$sql_select = "select * from user where nickname = '$f_nickname'";
$result_select = mysqli_query($conn, $sql_select);
$row_id = mysqli_fetch_array($result_select);


if($row_id != null){

// 4.테이블에 추가할 친구의 id를 구한다.
$receive_id = $row_id['id'];
settype($receive_id, 'integer');

/* 5.에러검사 */

//5-1)이미 등록된 친구인 경우 (중복1)
$sql_select = "select * from friend 
where my_id = $my_id and friend_id = $receive_id";
$result = mysqli_query($conn, $sql_select);
$num_friend = mysqli_num_rows($result);
if($num_friend != 0){
    echo '등록된친구';
    return;
}


// 5-2)이미 보낸 사람한테 친구 신청을 받은 경우(중복2)
$sql_select = "select * from all_send_receive 
where send_id = $receive_id and receive_id = $my_id";
$result = mysqli_query($conn, $sql_select);
$num = mysqli_num_rows($result);
if($num != 0){
    echo '이미받음';
    return;
}

// 5-3)본인 닉네임 중복검사(중복3)
$sql_select_nickname = "select * from user where id = $my_id";
$result_select_nickname = mysqli_query($conn, $sql_select_nickname);
$row_nickname = mysqli_fetch_array($result_select_nickname);

// 입력한 닉네임과 나의 닉네임이 같은 지 검사한다(중복 검사)
if($f_nickname == $row_nickname['nickname']){
    echo '본인닉네임';
    exit();
}

// 5-4) 친구 신청 중복 검사 (중복 4)
$sql_redundancy = "select * from all_send_receive where send_id = $my_id and receive_id = $receive_id";
$result_redundancy = mysqli_query($conn, $sql_redundancy);
$row_redudancy = mysqli_fetch_array($result_redundancy);
// 두 번 신청하지 않은 id인 경우에만
if($row_redudancy['receive_id'] != $receive_id){

// 6.테이블에 신청한 친구 정보 추가
$sql_insert = "insert into all_send_receive(send_id, receive_id, date) 
values(
    $my_id,
    $receive_id,
    now()
)";
$result_insert = mysqli_query($conn, $sql_insert);

if($result_insert == true){
 echo '친구신청성공';
}else{ 
 echo '친구신청실패';
}
}else{ 
 echo '두번신청';
}
}

}


?>