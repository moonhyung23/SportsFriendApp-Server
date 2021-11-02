<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
include_once "../dbcon.php";
   /* 회원가입에 필요한 데이터를 받아온다. */
   //1)선택한 운동
    $user_interest_exer = $_POST['user_interest_exer'];
    //2)선택한 주소(거주지역 + 관심지역)
    $user_addr = $_POST['user_addr'];
    //3)이메일
    $user_email = $_POST['user_email'];
    //4)비밀번호
    $user_pw = $_POST['user_pw'];
    //5)생년월일
    $user_birth_date = $_POST['user_birth_date'];
    //6)닉네임
    $user_nickname = $_POST['user_nickname'];
   
    //입력한 비밀번호 해시암호화 처리
    $new_pw_hash = password_hash($user_pw, PASSWORD_DEFAULT);
    

    /* 1.선택한 운동 */
    //1)문자열을 배열로 변환  
    $ar_exer = explode('@', $user_interest_exer);
    //마지막 인덱스 삭제
    $delete_last_idx = array_pop($ar_exer);
    //Json으로 변환 
    $json_exer = json_encode($ar_exer, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
     
    /* 2.선택한 주소 */
     //0번 : 거주지역
     //1번 : 관심지역
    //1)문자열을 배열로 변환  
    $ar_addr = explode('/', $user_addr);
    //Json으로 변환 
    $json_addr =  json_encode($ar_addr, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

    //db에 데이터 저장 요청
    $sql_insert = "INSERT INTO USERS (user_email, user_pw, user_birth_date,  user_nickname, user_interest_exer, user_addr, created_date) VALUES (
    '$user_email', 
    '$new_pw_hash', 
    '$user_birth_date',
    '$user_nickname',
    '$json_exer', 
    '$json_addr',
     NOW()
    )";
    $result_insert = mysqli_query($con, $sql_insert);
    
    if($result_insert === true){ 
        print_r("가입성공") ;
        //결과가 성공일때 실행되는 코드
    
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
    }
      


    
}        

?>