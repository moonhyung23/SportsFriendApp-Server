 <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
 <script src="../js_file/Global.js"></script>

<?php 
/* 로그인 체크 php 파일 */
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
//2.세션 초기화
session_start(); 

//3.post로 데이터 받기
$input_email = $_POST['email'];//입력한 이메일
$input_pw = $_POST['pw'];//입력한 비밀번호

//4.입력한 이메일과 같은 행을 조회한다
$sql  ="SELECT * FROM User WHERE email = '$input_email'";
$result = (mysqli_query($con, $sql));
$row = mysqli_fetch_assoc($result);
$num = mysqli_num_rows($result);

 //탈퇴된 회원인 경우
 if($row['status_chk'] == 2){
  ?>
  <script>
  history_back("탈퇴된 회원의 아이디입니다.");
  </script>
<?php
  exit();
}

//5.아이디 검사 
//5-1)입력한 아이디와 같은 행이 있는 경우(아이디 검사 성공)
if($num  == 1){
  //db테이블에 저장된 해시화 된 비밀번호를 갖고온다.
  $hash_pwd = $row['pw'];
  //6.해시 암호와 입력한 암호를 비교한다.
  if(password_verify($input_pw, $hash_pwd)){
    //6-1)로그인 성공(비밀번호 검사 성공)
    
     //세션에 내 id를 저장한다
      $_SESSION['id'] = $row['id'];
      
      /* status_chk 컬럼의 번호에 따라서 로그인 처리를 한다
      -0번: 기본
      -1번: 비밀번호 변경
      -2번: 회원탈퇴
      */

     
      
      //기본 로그인 (0번)
      if($row['status_chk'] == 0){
        ?> 
        <script>
        //  메인페이지로 이동
        move_page("로그인에 성공하셨습니다.", "../page/Main.php" );
        </script>
        <?php 

      }
      //비밀번호 찾기 후 로그인 (1번)
      else if($row['status_chk'] == 1){

        //status_chk 컬럼을 다시 0번(기본 로그인)으로 변경
        $sql = "UPDATE User SET 
        status_chk = 0
        WHERE email= '$input_email' ";
        $res = mysqli_query($con, $sql);

        ?> 
        <script>
        //비밀번호 변경 선택 다이얼로그
        //Yes => 비밀번호 변경 페이지
        //NO  => 메인 페이지
        alert_yes_No("임시비밀번호로 로그인 하셨군요~~ 비밀번호를 변경 하시겠습니까?", 
                     "../page/Edit_pw.php",
                     "../page/Main.php"
                     );
        </script>
        <?php 
      }
      
   
       
  }else{
    //6-2)로그인 실패(비밀번호 검사 실패)
    ?> 
    <script>
    history_back("비밀번호가 일치하지 않습니다.");
    </script>
    <?php 
  }
}
  //5-2)입력한 아이디와 같은 행이 없는 경우(아이디 검사 실패)
  else{
    ?> 
    <script>
    history_back("아이디가 일치하지 않습니다.");
    </script>
    <?php 
  }

?>