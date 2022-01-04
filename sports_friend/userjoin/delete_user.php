<?php
// ! 회원정보 삭제 php 파일
if($_SERVER['REQUEST_METHOD'] == 'POST'){

 include_once "../dbcon.php";

    // * 회원정보 Post로 받아오기
    $user_idx = $_POST['user_idx']; // 회원정보 인덱스

    // db의 데이터를 삭제 요청
    $sql_delete="DELETE from USERS WHERE user_idx ='$user_idx'";
    $result_delete = mysqli_query($con, $sql_delete);


    if($result_delete === true){
        //결과가 성공일때 실행되는 코드
        echo "회원정보삭제성공";
        exit();
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }

}
?>



