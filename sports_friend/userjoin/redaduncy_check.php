<?php
/* 중복검사 php 파일 */

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    //중복검사할 데이터 
    $check_data = $_POST['check_data'];
    /* 키워드 
     키워드에 따라서 중복검사하는 데이터가 달라진다.
        -이메일
        -닉네임
    */ 
    $keyword = $_POST['keyword'];
    
    //1)이메일 중복검사
    if($keyword == "이메일"){
        $sql_select = "SELECT * FROM USERS WHERE user_email = '$check_data' "; 
        $result_select = mysqli_query($con, $sql_select);
        $total_rows = mysqli_num_rows($result_select);

        //중복된 행이 없는 경우 (중복X)
        if($total_rows == 0){
         echo "사용가능";   
        }else{
         echo "중복";   
        }
    }
    //2)닉네임 중복검사
   else if($keyword == "닉네임"){
    $sql_select = "SELECT * FROM USERS WHERE user_nickname = '$check_data' "; 
    $result_select = mysqli_query($con, $sql_select);
    $total_rows = mysqli_num_rows($result_select);

     //중복된 행이 없는 경우 (중복X)
     if($total_rows == 0){
        echo "사용가능";   
       }else{
        echo "중복";   
       }
    }

}

?>