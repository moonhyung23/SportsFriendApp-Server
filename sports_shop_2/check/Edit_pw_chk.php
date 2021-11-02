

<!-- 비밀번호 변경 처리 php 파일 -->

  <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
 <script src="../js_file/Global.js"></script>

    <?php 

include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
        
    //세션 설정
    session_start();
    settype($_SESSION['id'], 'integer');
    $id = $_SESSION['id'];

    $pw = $_POST['pw'];//비밀번호
    $pw2 = $_POST['pw2'];//비밀번호 확인

    //입력한 비밀번호 해시암호화 처리
    $new_pw_hash = password_hash($pw, PASSWORD_DEFAULT);

    /*1. 비밀번호 재확인 */
    if($pw == $pw2){
    //1-1)비밀번호 재확인 성공

    //2.비밀번호 변경 SQL문
    $sql = "UPDATE User SET 
    pw = '$new_pw_hash'
    WHERE id = $id ";
    $res = mysqli_query($con, $sql);
    
    //2-1)비밀번호 변경 성공
    if($res === true){
    ?>
    <script>
    //메인 페이지로 이동
    move_page("비밀번호 변경 성공",  "../page/Main.php");
    </script>
    <?php

    }else{
    //2-2)비밀번호 변경 실패
    ?>
    <script>
    //이전 페이지로 이동
    history_back("비밀번호 변경 실패 (db오류)" );
    </script>
    <?php    
    }

    }else{
    //1-2)비밀번호 재확인 실패    
    ?>
    <script>
    //이전 페이지로 이동
    history_back("재확인 비밀번호가 일치하지 않습니다.");
    </script>
    <?php    
    }



?>