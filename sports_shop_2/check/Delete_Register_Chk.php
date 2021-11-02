<!-- 회원탈퇴 PHP 파일 -->

<!-- 자주 사용하는 php 파일 -->
<script src="../js_file/Global.js"></script>

<?php 

include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

    //2.세션 설정
    session_start();
    settype($_SESSION['id'], 'integer');
    $id = $_SESSION['id'];

    $pw = $_POST['pw']; //입력한 비밀번호
    
   //회원정보를 조회
    $sql_select = "SELECT * FROM User WHERE id = $id "; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);
    
    //비밀번호가 일치하는지 검사
      if(!password_verify($pw, $row['pw'])){
        //1)일치하지 않는 경우
        ?>
        <script>
          alert('비밀번호가 일치하지 않습니다.');
          history.back();
        </script>
      <?php
        exit();
      }
      


  /* status_chk 번호 수정
    -0번: 기본
    -1번: 임시 비밀번호 변경
    -2번: 회원탈퇴
  */

  //회원탈퇴로 변경
  $sql = "UPDATE User SET 
            status_chk = 2
            WHERE id = $id";
  $result = mysqli_query($con, $sql);

  //회원탈퇴 성공
    if($result === true){ 
          //세션 종료
       session_destroy();
        ?> 
        <script>
       move_page("회원탈퇴가 완료되었습니다.", "../page/Main.php" );
        </script>               
    <?php
    //회원탈퇴 실패
    }else {
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }

?>



