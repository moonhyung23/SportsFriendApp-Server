<!-- 회원정보 수정 php 파일 -->


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

/* post로 회원정보를 갖고온다 */
//1.입력한 이메일 주소
$input_email =  $_POST['input_email']; 
//1-1)기존  회원 이메일 
$email =  $_POST['email']; 
//2.비밀번호
$pw = $_POST['pw']; 
 //2-1) 비밀번호 확인
$pw2 = $_POST['pw2'];
//3.이름
$name = $_POST['name']; 
/* 4.주소 배열(우편번호, 주소, 상세주소) */
$post_num = $_POST['post_num']; //4-1)우편번호
$address = $_POST['address']; //4-2)주소
$address_detail = $_POST['address_detail']; //4-3)상세주소
/* 주소 배열에 (우편번호, 주소, 상세주소)저장 */
$addr_ar = array(
    "post_num" => $post_num, //1)우편번호
    "address" => $address,   //2)주소
    "address_detail" => $address_detail //3)상세주소
    );
/* 주소 배열 => json으로 변환 */
/* 키: "addr"(db.php 파일에서 따로 정의함) */
$json_addr = json_encode_ar($addr_ar, $key);
//5.핸드폰 번호
$phone_num = $_POST['phone_num']; 


/* 비밀번호 정규식 검사 */
$result = passwordCheck($pw);

if($result[0] === false){
    ?>
    <script>
    var error = '<?=$result[1]?>';
    //에러 메세지 보내기 
    history_back(error);
    </script>
    <?php
    //종료
    exit();
  }


/* 이메일검사 */

//1)기존이메일, 입력한 이메일 변경여부 검사
if($input_email != $email){

//2)이메일 중복검사
//회원정보 테이블에서 입력한 아이디와 같은 행을 조회한다
$sql_select = "SELECT * FROM User WHERE email = '$input_email' "; 
$result_select = mysqli_query($con, $sql_select);
$num = mysqli_num_rows($result_select);

//입력한 이메일과 같은 행이 있을 때 (이메일 중복)
if($num != 0){
    echo '<script> alert("중복된 이메일 입니다."); </script>';
    echo("<script> history.back(); </script>"); 
    exit();
}
}


//2)비밀번호 재확인 검사
if($pw != $pw2){
    echo '<script> alert("비밀번호가 일치하지 않습니다."); </script>';
    //apply.php로 이동
       echo("<script> history.back(); </script>"); 
       exit();
    }

//입력한 비밀번호 해시암호화 처리
 $new_pw_hash = password_hash($pw, PASSWORD_DEFAULT);

/* 입력한 회원정보 수정 */
$sql_update= "UPDATE User
SET email = '$input_email',
pw = '$new_pw_hash',
name = '$name',
addr = '$json_addr',
phone_num = '$phone_num'
WHERE id = $id"; // where 조건 설정은 and, or, not, in 연산자 사용

$result_update = mysqli_query($con, $sql_update);

if($result_update === true){
    ?>
    <script>
    //회원정보 수정 성공
    //마이페이지로 이동
    move_page("회원정보가 수정되었습니다.", "../page/Mypage.php");
    </script>
    <?php
}else {
    ?>
    <script>
    //회원정보 수정 실패
    //뒤로가기
    history_back("회원정보가 수정실패 db오류.");
    </script>
    <?php
    echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
}


?>