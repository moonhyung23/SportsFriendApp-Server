<?php 
// 영어 단어장 삭제 php파일

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();
if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
    $all_id = $_POST['all_id']; //단어장의 전체 번호
    settype($all_id, 'integer');

/* 1. 영어단어장 삭제 */
//  단어장 삭제 
//  안드로이드에서 가져온 단어장 번호와 같은 것을 삭제
$sql_Notedelete = "DELETE from wordNote where allid = $all_id";
$result = mysqli_query($conn, $sql_Notedelete);

if($result == true){
 echo '단어장 삭제성공';
}else{
 echo '단어장 삭제실패';   
}

/* 2. 영어단어정보 삭제 */
// 전체 영어단어 정보 조회
$sql_allword = "select * from word";
$result_allword = mysqli_query($conn, $sql_allword);
//전체 영어단어 정보 갯수 변수에 저장
$num_allrow = mysqli_num_rows($result_allword);


// 단어장의 영어단어 삭제
for($i = 0; $i < $num_allrow; $i++ ){
    $sql_word_delete = "DELETE from word where id = $all_id";
    $result = mysqli_query($conn, $sql_word_delete);
}



}



?>