<!-- 회원정보 수정 시 입력해야 하는 비밀번호 검사 php파일 -->


<!-- 많이 사용하는 js함수 파일 -->
<script src="../js_file/Global.js"></script>

<?php
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//post로 입력한 비밀번호를 갖고온다.
$input_pw = $_POST['input_pw']; //입력한 비밀번호


//2.세션설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];

 //회원정보를 불라온다 
 //문의 글 번호로 클릭한 문의 글의 비밀번호를 갖고온다.
 $sql_select = "SELECT * FROM User WHERE id = $id "; 
 $result_select = mysqli_query($con, $sql_select);
 $row = mysqli_fetch_assoc($result_select);



/* 해시키를 해석해서 비밀번호가 일치하는지 확인한다 */

// 1.비밀번호 일치
if(password_verify($input_pw, $row['pw']) ){
    ?>
    <script>
    //회원정보 수정 페이지로 이동
    move_page_link("../page/Edit_Register_infor.php");
    </script>
<?php
}
//2.일치하지 않음
else{
    ?>
    <script>
    //뒤로가기
    history_back("비밀번호가 일치하지 않습니다.");
    </script>
<?php
}


?>