<?php 

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    //table 데이터를 담을 배열 생성
      $data = array(); 
  
    //  모든 단어장 정보를 불러온다
  $sql_all_select = "select * from wordNote";
  $result_row = mysqli_query($conn, $sql_all_select);

//   모든 단어장의 갯수만큼 반복한다.
  while($row = mysqli_fetch_array($result_row)){
      extract($row);
      // 단어장 정보 배열에 저장
     array_push($data, 
     array(
     'uid'=>$row['uid'],
     'name'=>$row['name'],
     'allid'=>$row['allid'],
     'nickname'=>$row['nickname']
      ));
  }
  
  
  header('Content-Type: application/json; charset=utf8');
  // 내 uid로된 단어장 정보를 json형식으로 변환 
  $json = json_encode(array("all_wordNote"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  // 안드로이드에 전송
  echo $json;
  }

?>