<?php 
// 단어장 수정 php 파일



//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();
if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{

 //1.안드로이드에서 단어장 정보를 post방식으로 갖고온다.
 $notename = $_POST['edit_name']; //단어장의 이름
 $all_id = $_POST['all_id']; //단어장의 전체 번호

   // uid  int로 변환 
  settype($all_id, 'integer');

//   단어장 이름 수정 
//  안드로이드에서 가져온 단어장 번호와 같은 것을 수정
 $sql = "UPDATE wordNote
 SET
 name = '$notename'
 WHERE 
 allid = $all_id
";

//db에 저장
$result = mysqli_query($conn, $sql);

//처리 결과 안드로이드 에게 보내기
if($result == true){
echo '단어장 수정성공';
}else{
    echo '단어장 수정실패';
}

}

?>