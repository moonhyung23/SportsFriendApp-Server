<?php
//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
    //내가 보낸 친구신청 정보를 담을 배열
    $data = array(); 

  //안드로이드에서 단어장 정보를 post방식으로 갖고온다.
  $my_id = $_POST['my_id'];
  settype($my_id, 'integer');


//   1.전체 행을 조회(친구 신청목록)
 $sql_select_all = "select * from all_send_receive";
 $result_all = mysqli_query($conn, $sql_select_all);
 //  2.전체 친구 신청목록에서 내 id와 같은 행을 찾는다.
while($all_row = mysqli_fetch_array($result_all)){
    if($all_row['send_id'] == $my_id){
        extract($all_row);
    //  3.찾은 행에서 receive_id(받은 사람id)를 찾는다.    
        $receive_id = $all_row['receive_id'];
        settype($receive_id, 'integer');
   
   //  4. receive_id(받은사람id) == id(사용자 id)가 같은 행을 사용자 정보 테이블에서 찾는다.
   $sql_receive_user = "select * from user where id = $receive_id";
   $result_receive_user = mysqli_query($conn, $sql_receive_user);
   $row_receive_user = mysqli_fetch_array($result_receive_user);

   // 5. 갖고온 행을 배열에 저장한다.
   array_push($data, 
   array(
   'id'=>$row_receive_user['id'], //친구신청 받은사람 사용자 id
   'nickname'=>$row_receive_user['nickname'],//닉네임
   'img_profile'=>$row_receive_user['img_profile'],//프로필 url
   ));
    }
}
// 7.저장한 배열을 json으로 변환
header('Content-Type: application/json; charset=utf8');
$json = json_encode(array("receive_array"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
// 8.안드로이드에 전송
echo $json;


}
 
  
 
?>