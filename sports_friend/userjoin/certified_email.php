
<?php

/* email라이브러리 추가 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

header('Content-Type: application/json; charset=utf8');

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/PHPMailer.php';
$mail = new PHPMailer(true);

//입력한 이메일
$email = $_POST['email_certified'];
/* post로 입력한 이름, 이메일을 받는다. */
$certified_num = sprintf("%06d", rand(000000,999999));

   /* 사용자 email에 보낼 메세지 */
  $msg = "안녕하세요 회원가입 인증 번호 입니다.<br>\n
    회원가입 인증 번호는[ $certified_num ] 입니다.<br>\n
  -농구친구 어플-";
  //이메일에 인증번호 보내기
  try {
    //1)메일 전송 성공
    // 서버세팅
    $mail -> SMTPDebug = 0; // 디버깅 설정
    $mail -> isSMTP(); // SMTP 사용 설정
    // 지메일일 경우 smtp.gmail.com, 네이버일 경우 smtp.naver.com
    $mail -> Host = "smtp.naver.com";               // 네이버의 smtp 서버
    $mail -> SMTPAuth = true;                         // SMTP 인증을 사용함
    $mail -> Username = "dlansgud613@naver.com";    // 메일 계정 (지메일일경우 지메일 계정)
    $mail -> Password = "aver$102369M";                  // 메일 비밀번호
    $mail -> SMTPSecure = "ssl";                       // SSL을 사용함
    $mail -> Port = 465;                                  // email 보낼때 사용할 포트를 지정
    $mail -> CharSet = "utf-8"; // 문자셋 인코딩
    // 보내는 메일
    $mail -> setFrom("dlansgud613@naver.com", "이메일 인증번호 발송");
    // 받는 메일
    $mail -> addAddress("$email", "DDDD");
    // 메일 내용
    $mail -> isHTML(true); // HTML 태그 사용 여부
    $mail -> Subject = "안녕하세요 농구친구 어플 회원가입 인증메일입니다."; 
    $mail -> Body = $msg;     // 메일 내용
    
    /*Gmail인 경우*/
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

    echo "$certified_num";
   
}
    //2)메일전송 실패
    catch (Exception $e) {
    //에러 로그
    echo "Message could not be sent. Mailer Error : ", $mail -> ErrorInfo;
    }


}        







