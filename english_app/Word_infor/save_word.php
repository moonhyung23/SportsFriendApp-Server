<?php 
// 나의 영어단어장에 영어단어 정보를 추가하는 php 파일

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

    /* 안드로이드에서 json형식으로 변환한 영어단어 정보를 
        배열에 저장한다. */
  
  // 나의 단어장 번호 
  $all_id = $_POST['allid'];
  //int로 변환
  settype($all_id, 'integer');
 

    /* 이전에 저장되어 있던 내가 클릭한 영어단어장의
        -영어 단어 정보를 삭제한다.
        (중복 추가를 막기 위해서)
    */

    //전체 영어단어정보의 행의 갯수를 갖고온다.
    $sql_allrow = "select * from word";
    $result_row = mysqli_query($conn, $sql_allrow);
    //전체 영어 단어정보 테이블의 행 갯수
    $num_allrow = mysqli_num_rows($result_row);


    // /* 내 단어 정보만 삭제 한다. */
    //전체 단어 갯수만큼 반복
  for($j = 0; $j < $num_allrow; $j++){
    // 나의 id와 같은 행만 삭제 
    $sql_delete = "delete from word where id = $all_id";
    $result_delete = mysqli_query($conn, $sql_delete);
  }

  /* json 형식의 데이터를 배열에 담는다. */

   //json 데이터를 담을 배열
   $arr_word = array(); 
   //나의 단어장의 단어정보 (JSON)
   $jsondata = $_POST['json_word'];
   // json 데이터를 배열로 변환
   $arr_word = json_decode($jsondata); 

   // 나의 단어정보 배열에 저장
   // json 배열의 갯수 만큼 
   $num = count($arr_word);
   for($i = 0; $i < count($arr_word); $i++){
    $all_id = ($arr_word[$i] -> all_id);
    $wordname = ($arr_word[$i] -> my_word);
    $wordmean = ($arr_word[$i] -> my_mean);
    // 전체 단어장 번호 int 형변환
    settype($all_id, 'integer');


  // db에 영어단어 정보 저장
  $sql = "INSERT INTO word 
  (id, name, mean)
  VALUES(
       $all_id,
      '$wordname',
      '$wordmean'
      )
  ";
 $result = mysqli_query($conn, $sql);
}

// 저장 성공 여부 안드로이드에 전송
if($result == true){
  $check = '단어저장성공';
 }else{
  $check = '단어저장실패';
 }
}
?>