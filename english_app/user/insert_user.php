<?php 

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();

// 안드로이드에서 회원가입에 필요한 데이터를 받아온 경우 
if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
    //회원가입에 필요한 데이터 변수에 저장
      $uid = $_POST['uid'];
      $pw = $_POST['pw'];
      $nickname = $_POST['nickname'];
      $img_profile = $_POST['img_profile'];

      
      /* 아이디 중복 검사 */
      //mysql에서 내 이름으로 된 id가 있는 경우 중단 시킨다.
      $sql_idcheck = "SELECT * FROM user WHERE uid = '$uid'";
      $result_idcheck = mysqli_query($con, $sql_idcheck);
      //중복된 행이 있는 경우
      if(mysqli_num_rows($result_idcheck) != 0){
        echo '아이디중복';
        return;
      }
      

      //입력한 암호를 해시화 시킨다. 
      $encrypted_password = password_hash($pw, PASSWORD_DEFAULT);
    
        $uid2 = (String)$uid;

      /* 아이디 중복검사 통과!! */
    // 입력한 회원가입에 필요한 정보 테이블에 입력
      $sql = "INSERT INTO user 
      (uid, pw, nickname, img_profile, made_date)
      VALUES(
          '$uid2',
          '$encrypted_password',
          '$nickname',
          '$img_profile',
          NOW()
          )
      ";

  // 사용자 정보 mysql에 저장
   $result = mysqli_query($con, $sql);
  // 1.가입실패
  //테이블에 저장 실패시 에러 출력
   if($result === false){
     echo '실패';
     }//2. 가입성공
     else if($result === true){
       echo '성공';
     }
}
                
?>