<?php
/* 로그인 php 파일 */

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  include_once "../dbcon.php";

    $user_email = $_POST['user_email'];
    $user_pw = $_POST['user_pw'];

     /* 이메일 검사 */
    $sql_select = "SELECT * FROM USERS WHERE user_email = '$user_email'"; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);
    $num = mysqli_num_rows($result_select);

    //이메일 검사 
    if($num  == 0){
        //이메일 불일치
      echo "이메일불일치";
      exit();
    }

       /* 비밀번호 검사 */
        //db테이블에 저장된 해시화 된 비밀번호를 갖고온다.
        $hash_pwd = $row['user_pw'];
        //해시 암호와 입력한 암호를 비교한다.
    if(!password_verify($user_pw, $hash_pwd)){
      //비밀번호 검사 실패
      echo "비밀번호불일치";
      exit();
    }
    
      /* user_status_num (회원상태번호) 컬럼의 번호에 따라서 로그인 처리를 한다
      -0번: 기본
      -1번: 비밀번호 변경
      -2번: 회원탈퇴
      */
      //기본 로그인 (0번)
      if($row['user_status_num'] == 0){
            echo "{$row['user_idx']}";
      }
      //비밀번호 찾기 후 로그인 (1번)
      else if($row['user_status_num'] == 1){

        //user_status_num 컬럼을 다시 0번(기본 로그인)으로 변경
        $sql = "UPDATE User SET 
        user_status_num = 0
        WHERE email= '$input_email' ";
        $res = mysqli_query($con, $sql);
      }
           
}
?>