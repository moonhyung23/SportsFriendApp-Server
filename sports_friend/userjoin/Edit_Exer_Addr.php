<?php 
// ! 회원정보 변경 (거주지역, 관심지역, 관심운동)

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    include_once "../dbcon.php";

    // * 회원정보 Post로 받아오기
    $user_idx = $_POST['user_idx']; // 회원정보 인덱스
    $user_addr = $_POST['user_addr']; // 주소정보(거주지역 + 관심지역)
    $user_interest_exer = $_POST['user_interest_exer']; //선택한 운동

  
    
/* 1.선택한 운동 */
    //1)문자열을 배열로 변환  
    $ar_exer = explode('@', $user_interest_exer);
    //마지막 인덱스 삭제
    $delete_last_idx = array_pop($ar_exer);
    //Json으로 변환 
    $json_exer = json_encode($ar_exer, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

/* 2.거주지역, 관심지역 */
     // * 선택한 주소 Json으로 변경하기 
     //0번 : 거주지역
     //1번 : 관심지역
    //1)문자열을 배열로 변환  
    $ar_addr = explode('/', $user_addr);
    //Json으로 변환 
    $json_addr =  json_encode($ar_addr, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);


    // * 회원정보 수정 (주소정보, 관심운동)
    // db의 데이터를 변경하는 코드 // 쉼표로 , 여러개의 값을 동시에 변경할 수 있다.
    $sql_update="UPDATE USERS
    SET user_addr = '$json_addr',
    user_interest_exer = '$json_exer'
    WHERE user_idx = $user_idx "; // where 조건 설정은 and, or, not, in 연산자 사용

    $result_update = mysqli_query($con, $sql_update);
    
    if($result_update === true){
        echo "회원정보수정성공";
        //결과가 성공일때 실행되는 코드
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
    }
}

?>