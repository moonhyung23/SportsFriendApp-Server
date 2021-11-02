<?php


//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
  //1.안드로이드에서 단어장 정보를 post방식으로 갖고온다.
  $notename = $_POST['Note_name'];
  $uid = $_POST['uid'];
   // uid  int로 변환   
  settype($uid, 'integer');

  //2.작성자의 닉네임을 갖고온다.
  $sql = "select * from user where id = $uid";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $nickname = $row['nickname'];





    // db에 단어장 정보 저장
  $sql = "INSERT INTO wordNote 
    (uid, name, nickname)
    VALUES(
         $uid,
        '$notename',
        '$nickname'
        )
    ";
   $result = mysqli_query($conn, $sql);

  if($result == true){
    $check = '단어장 저장성공';
   }else{
    $check = '단어장 저장실패';
   }
  // 저장 성공 여부 안드로이드에 전송
   echo $check;

  }
?>