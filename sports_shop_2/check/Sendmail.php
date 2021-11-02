<!-- 임시비밀번호를 이메일에 보내주고 임시비밀번호 db에 저장하는  php 파일 -->


  <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
  <script src="../js_file/Global.js"></script>
<?php 
//Ui 파일 추가
include "../Db.php";
include "../Common_Ui.php";



/* email라이브러리 추가 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/Exception.php';

$mail = new PHPMailer(true);

/* post로 입력한 이름, 이메일을 받는다. */
$name = $_POST['name'];
$email = $_POST['email'];

/* 입력한 이름, 이메일이 일치하는지  검사 */
$query1 = "SELECT * FROM User WHERE email ='$email' and name='$name' ";
$res = mysqli_query($con, $query1);
$num = mysqli_num_rows($res);

//입력 값이 일치하지 않은 경우
if($num != 1){ ?>
<script>
history_back("잘못 입력하셨습니다. ");
</script>
<?php
}

//입력한 이메일, 이름이 일치하는 경우
else if($num == 1){
  //임시비밀번호로 전송할 비밀번호를 암호화 해서 8자리 암호를 만든다.
  $new_pw = substr(hash("sha256", mt_rand()), 1,8);
  //만든 임시비밀번호를 해시처리화 시킨다
  $new_pw_hash = password_hash($new_pw, PASSWORD_DEFAULT);
  //해시처리한 임시비밀번호를 db의 사용자 비밀번호 컬럼에 수정
  //조건: 입력한 이메일과 같은 로우(행)
  $query2 = "UPDATE User SET 
            status_chk = 1,
            pw ='$new_pw_hash' 
            WHERE email= '$email' ";
  $res2 = mysqli_query($con,$query2);

  /* email에 보낼 메세지 */
  $msg = "안녕하세요 ".$name."\r\n님의 임시비밀번호"."\n"."는 
  "."\n"." ".$new_pw."
  "."\n"." 입니다.
  임시 비밀번호로 로그인을 하신 후 비밀번호를 바로 변경 해주시면 감사하겠습니다.
  "."\n"."
  좋은하루 되세요~~ :)
  "."\n"."
  -Jordan's Basketball_Shop-";

try {

echo "111";
    //1)메일 전송 성공
    // 서버세팅
    //디버깅 설정을 0 으로 하면 아무런 메시지가 출력되지 않습니다
    $mail -> SMTPDebug = 0; // 디버깅 설정
    $mail -> isSMTP(); // SMTP 사용 설정
    // 지메일일 경우 smtp.gmail.com, 네이버일 경우 smtp.naver.com
    $mail -> Host = "smtp.naver.com";               // 네이버의 smtp 서버
    $mail -> SMTPAuth = true;                         // SMTP 인증을 사용함
    $mail -> Username = "dlansgud613@naver.com";    // 메일 계정 (지메일일경우 지메일 계정)
    $mail -> Password = "qoxmf102369@";                  // 메일 비밀번호
    $mail -> SMTPSecure = "ssl";                       // SSL을 사용함
    $mail -> Port = 465;                                  // email 보낼때 사용할 포트를 지정
    $mail -> CharSet = "utf-8"; // 문자셋 인코딩
    // 보내는 메일
    $mail -> setFrom("dlansgud613@naver.com", "임시비밀번호 발송");
    // 받는 메일
    $mail -> addAddress("{$_POST['email']}", "{$_POST['name']}");
    // 메일 내용
    $mail -> isHTML(true); // HTML 태그 사용 여부
    $mail -> Subject = "Jordan's Sports_Shop에서 보낸 임시비밀번호 입니다.";  // 메일 제목
    $mail -> Body = $msg;     // 메일 내용
    
    // Gmail로 메일을 발송하기 위해서는 CA인증이 필요하다.
    // CA 인증을 받지 못한 경우에는 아래 설정하여 인증체크를 해지하여야 한다.
    $mail -> SMTPOptions = array(
      "ssl" => array(
      "verify_peer" => false
      ,"verify_peer_name" => false
      ,"allow_self_signed" => true
      )
    );
    // 메일 전송
    $mail -> send();
    ?>
   <!-- 새로운 비밀번호가 생성되었다는 다이얼로그 alert를 보낸 후 로그인 페이지로 이동 -->
    <script>
    move_page('입력하신 이메일로 임시 비밀번호를 보냈습니다.', "../page/Login.php");
    </script> 
    <?php
    echo 1;
}
    //2)메일전송 실패
    catch (Exception $e) {
    //에러 로그
    echo "Message could not be sent. Mailer Error : ", $mail -> ErrorInfo;
    }
}

?>