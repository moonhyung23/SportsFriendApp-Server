<?php 
// ! 회원정보 조회 PHP파일
if($_SERVER['REQUEST_METHOD'] == 'POST'){

include_once "../dbcon.php";

//나의 친구인지 아닌지 구분하는 번호
//1번 : 친구 X
//2번 : 친구 O
$friend_flag = 1;


//* 회원 인덱스 번호
$user_idx = $_POST['user_idx'];
//integer 형변환
settype($user_idx, 'integer');


if(!empty($_POST['my_idx']) ){
  $my_idx = $_POST['my_idx'];

  // * 조회하려는 회원이 나의 친구인지 조회 
//조회하려는 회원의 idx번호를 통해 FRIEND테이블에서  
$sql_select_friend = "SELECT * FROM FRIEND 
WHERE my_idx = $my_idx
AND friend_idx = $user_idx"; 
$result_select_friend = mysqli_query($con, $sql_select_friend);
$row_cnt_friend = mysqli_num_rows($result_select_friend);

//나의 친구인 경우  
if($row_cnt_friend != 0){
  $friend_flag = 2;
}
}







//* 회원 정보 로우 조회하기
$sql_select = "SELECT * FROM USERS WHERE user_idx = $user_idx "; 
$result_select = mysqli_query($con, $sql_select);

//* 회원정보를 담을 배열생성
$ar_userData = array();

// * 배열에 회워정보를 담는다.
    while($row = mysqli_fetch_assoc($result_select)){

    //*  주소정보Json -> 배열로 변환 후 
    //* 배열의 요소 String변수에 저장
    // * 1번: 현재거주지역 2번: 관심지역
    $user_addr = json_decode($row['user_addr']);
        $live_addr = $user_addr[0]; //1번: 현재거주지역
        $live_addr = $user_addr[1]; //2번: 관심지역

  
  //*  관심운동JSON -> 배열로 변환
  $ar_exer = json_decode($row['user_interest_exer']);
  //공백체크 
  if(!empty($ar_exer)){
    //배열의 요소를 String으로 저장할 변수 
  $user_interest_exer = ""; 

  //배열의 마지막 요소의 Key 반환 
  end($ar_exer); 
  $last_value = key($ar_exer);
  
  //배열의 요소만큼 반복
  foreach($ar_exer as $key => $value) {
    // * 마지막 인덱스인 경우  구분자("/") 추가하지 않기
    if($last_value == $key){
    $user_interest_exer =  $user_interest_exer.$value;
    } 
    // * 마지막 인덱스가 아닌 경우 구분자("/") 추가
    else {
    // 배열의 요소 String변수에 저장 (관심운동)
    $user_interest_exer =  $user_interest_exer.$value."@";
    }
  }  
  }
    array_push($ar_userData, array(
    'user_nickname' => $row['user_nickname'], 
    'user_birth_date'=> $row['user_birth_date'],
    'user_img_url' => $row['user_img_url'],
    'live_addr' => $user_addr[0],
    'interest_addr' => $user_addr[1],
    'user_content' => $row['user_content'],
    'user_interest_exer' => $user_interest_exer,
    'friend_flag' => $friend_flag
)); 
}

// * JSONArray로 변환
$json_array = json_encode(array("USERDATA"=>$ar_userData), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

// * 출력
echo $json_array;
}

?>