<?php 

//db 정보
$host_ip="13.124.210.208";
$db_user="root";
$db_pw="ansgud12";
$db_name="Basketball_db";

/* json 배열 키 */
$key = "addr";  //사용자 주소 정보 json 배열 키
$item_num_json = "item_num_json"; //최근 본 상품의 번호 json 배열 키 (쿠키에 저장됨)

/////데이터베이스 연결.////
//ip, db이름, db비밀번호, db이름
$con =  mysqli_connect($host_ip, $db_user, $db_pw, $db_name);



  /*  배열 json으로 변환 */
  function json_encode_ar($array, $json_key) {
    // 배열 => json으로 변환
    $json_array = json_encode(array($json_key => $array), JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
    return $json_array;
}


/* 콘솔로그 찍기 */
function Console_log($data){
  echo "<script>console.log( 'PHP_Console: " . $data . "' );</script>";
}


/* 비밀번호 정규식 적용 함수 */
function passwordCheck($_str)
{
/* 정규식 종류
    -$num, $eng, $spe => 비밀번호, 영문숫자, 특수문자 검사
    -$strlen($pw) => 자릿수 검사
    -prrg_match("/\s\u", $pw) => 공백 검사
*/
    $pw = $_str;
   /* 비밀번호, 영문숫자, 특수문자 검사 */
    //1)번호
    $num = preg_match('/[0-9]/u', $pw);
    //2)영어
    $eng = preg_match('/[a-z]/u', $pw);
    //3)영문, 숫자, 특수문자 검사
    $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pw);
  
  
    //1)비밀번호 공백 검사
  if(preg_match("/\s/u", $pw) == true)
  {
      return array(false, "비밀번호는 공백없이 입력해주세요.");
      exit();
  }
    //2)자릿수 검사
    if(strlen($pw) < 5 || strlen($pw) > 20)
    {
        return array(false, "비밀번호는 영문, 숫자, 특수문자를 혼합하여 최소 5자리 ~ 최대 0자리 이내로 입력해주세요.");
        exit();
    }
    //3)영문, 숫자, 특수문자 검사
    if( $num == 0 || $eng == 0 || $spe == 0)
    {
        return array(false, "영문, 숫자, 특수문자를 혼합하여 입력해주세요.");
        exit();
    }
    //비밀번호 정규식 통과시 true 반환
    return array(true);
}



?>


