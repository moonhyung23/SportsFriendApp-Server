<?php
//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
  //table 데이터를 담을 배열 생성
    $data = array(); 
 //안드로이드에서 보낸 사용자 uid


/* 나의 단어장과 친구의 단어장을 구분
 * check 1번 => 나의단어장
 * check 2번 => 친구의 단어장
 */
 $check = $_POST['check']; 
 //1.나의 단어장
 if($check == "1"){
  $uid = $_POST['my_id']; //나의 아이디
 }
 //2.친구의 단어장
 else if($check == "2"){
  $uid = $_POST['f_id']; //친구의 아이디
 }
 settype($uid, 'integer');


  //  모든 단어장 정보를 불러온다
$sql_all_select = "select * from wordNote";
$result_row = mysqli_query($conn, $sql_all_select);
//단어장 정보의 행의 갯수만큼 반복한다.
while($row = mysqli_fetch_array($result_row)){
  // uid가 내 아이디인 행만 배열에 저장한다.
  if($uid == $row['uid']){
    extract($row);
    
    // 배열에 저장
    // 전체 단어장의 번호 : all_id
   array_push($data, 
   array('uid'=>$row['uid'],
   'name'=>$row['name'],
   'all_id'=>$row['allid']
    ));
  }
}


header('Content-Type: application/json; charset=utf8');
// 내 uid로된 단어장 정보를 json형식으로 변환 
$json = json_encode(array("wordNote"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
// 안드로이드에 전송
echo $json;
}







?>