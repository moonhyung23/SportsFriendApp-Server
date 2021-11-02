<!-- 회원가입 등록 체크 -->

  <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
  <script src="../js_file/Global.js"></script>
<?php 
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//회원정보 
$email =  $_POST['email']; //이메일 주소
$pw = $_POST['pw']; //비밀번호
$pw2 = $_POST['pw2']; // 비밀번호 확인
$name = $_POST['name']; //이름
/* 주소 */
$post_num = $_POST['post_num']; //1)우편번호
$address = $_POST['address']; //2)주소
$address_detail = $_POST['address_detail']; //3)상세주소
$phone_num = $_POST['phone_num']; //핸드폰 번호


/* 주소 배열에 저장 */
$addr_ar = array(
"post_num" => $post_num, //1)우편번호
"address" => $address,   //2)주소
"address_detail" => $address_detail //3)상세주소
);

/* 주소 배열 => json으로 변환 */
/* 키: "addr"(db.php 파일에서 따로 정의함) */
$json_addr = json_encode_ar($addr_ar, $key);

/* 1.검사 */
//1)비밀번호 정규식 검사
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

//1)이메일 중복검사
//회원정보 테이블에서 입력한 아이디와 같은 행을 조회한다
$sql_select = "SELECT * FROM User WHERE email = '$email' "; 
$result_select = mysqli_query($con, $sql_select);
$num = mysqli_num_rows($result_select);
//입력한 아이디와 같은 행이 있을 때 (이메일 중복)
if($num != 0){
    echo '<script> alert("중복된 이메일 입니다."); </script>';
    echo("<script> history.back(); </script>"); 
    return;
}

//2)비밀번호 재확인 검사
if($pw != $pw2){
    echo '<script> alert("비밀번호가 일치하지 않습니다."); </script>';
    //apply.php로 이동
       echo("<script> history.back(); </script>"); 
    }
    // 2-2)비밀번호가 일치하는 경우(재확인)
    else if($pw == $pw2){
      //입력한 비밀번호 해시암호화 처리
    $new_pw_hash = password_hash($pw, PASSWORD_DEFAULT);

  // 3)테이블에 입력한 회원정보 데이터 저장
  /* 이메일, 비밀번호, 이름, 주소, 전화번호, 가입날짜 */
    $sql_insert = "insert into User(email, pw, name, addr, phone_num, created) values (
    '{$email}', 
    '{$new_pw_hash}', 
    '{$name}',
    '{$json_addr}',
    '{$phone_num}',
    NOW()
    )";
    }
    $result = mysqli_query($con, $sql_insert);
    
    //데이터가 저장된 경우
    if($result === true){
    //회원가입 완료 다이얼로그 뛰우기
    //완료 후 메인화면으로 이동
      echo '<script> alert("회원가입을 하였습니다.");
        document.location.href="../page/Main.php"; 
      </script>';
    }else{
    //회원가입 완료 다이얼로그 뛰우기
    //완료 후 회원가입 화면으로 다시 이동
      echo '<script> alert("회원가입을 실패하셨습니다.."  );
      history.back();
       </script>';
    }
?>
