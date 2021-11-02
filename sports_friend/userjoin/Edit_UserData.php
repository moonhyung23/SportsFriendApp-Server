<?php
// ! 회원정보 조회 PHP파일
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    include_once "../dbcon.php";
    

    // * 회원정보 Post로 받아오기
    $user_idx = $_POST['user_idx']; // 회원정보 인덱스
    $user_nickname = $_POST['user_nickname']; // 닉네임
    $user_birth_date = $_POST['user_birth_date']; //  생년월일
    $user_addr = $_POST['user_addr']; // 주소정보(거주지역 + 관심지역)
    $user_content = $_POST['user_content']; // 상태메세지

     // * 선택한 주소 Json으로 변경하기 
     //0번 : 거주지역
     //1번 : 관심지역
    //1)문자열을 배열로 변환  
    $ar_addr = explode('/', $user_addr);
    //Json으로 변환 
    $json_addr =  json_encode($ar_addr, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    

   
    // 닉네임이 변경되었는지 확인
    $sql_select = "SELECT * FROM USERS WHERE user_idx = $user_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);

    //닉네임이 변경되었는지 검사 
    if($row['user_nickname'] != $user_nickname){
        //닉네임 중복검사 
        $sql_select = "SELECT * FROM USERS WHERE user_nickname = '$user_nickname' "; 
        $result_select = mysqli_query($con, $sql_select);
        $row_num = mysqli_num_rows($result_select);
        //중복된 닉네임이 있는 경우
        if($row_num != 0){
            echo "닉네임중복";
            exit();
        }    
    }

    // * 회원정보 수정 
    // db의 데이터를 변경하는 코드 // 쉼표로 , 여러개의 값을 동시에 변경할 수 있다.
    $sql_update="UPDATE USERS
    SET user_nickname = '$user_nickname',
        user_birth_date = '$user_birth_date',
        user_content = '$user_content',
        user_addr = '$json_addr'
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