
<!-- 모임 글 비밀번호 인증 페이지 -->

<script>
/* 입력한 비밀번호 검사 함수 */
 function pw_check(){
//  입력한 비밀번호를 갖고온다
    let input_pw = document.querySelector("#input_pw").value;
// 문의 글의 비밀번호를 갖고온다.
    let bulletin_pw = document.querySelector("#pw").value;    
// 문의 글의 번호를 갖고온다.
    let number = document.querySelector("#number").value;    

 //입력한 비밀번호가 일치하는지 검사한다
    if(input_pw == bulletin_pw){
    //1)비밀번호 일치 
     /***  문의 글 페이지로 이동 ***
         -문의 글 번호를 Get방식으로 보냄.
    */
   document.location.href = "Bulletin_infor.php?number=" + number;
}else{
    //2)비밀번호 불일치
    alert("비밀번호가 일치하지 않습니다.");
    }
}
</script>


<?php 
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//2.세션설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];

//1-1)비로그인
if($_SESSION['id'] == null) {
    $login = '
    <li><a href="Login.php">로그인</a></li>
    <li><a href="Register.php">회원가입</a></li>
    <li><a href=""></a></li>
    <li><a href=""></a></li>
    <li><a href=""></a></li>
';
?>

<?php
//1-2)로그인
}else{
    $login = '
    <li><a href="../check/Logout_check.php">로그아웃</a></li>
    <li><a href="Basket.php">장바구니</a></li>
';
}
    //사이드 메뉴 ui
$sidemenu = Side_Menu($id);

  //문의 글 번호를 Get으로 갖고온다
  $number = $_GET['number'];
  //문의 글 번호로 클릭한 문의 글의 비밀번호를 갖고온다.
  $sql_select = "SELECT * FROM Bulletin WHERE number = $number "; 
  $result_select = mysqli_query($con, $sql_select);
  $row = mysqli_fetch_assoc($result_select);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- js파일 -->
     <script src="../js_file/Global.js"></script>
    <!-- css 파일 적용 -->
    <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
     <!-- 부트스트랩 적용 -->
     <link rel="stylesheet"  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <title>비밀번호 입력 창 </title>
    </head>
    <body>
    <!-- 메인이미지 -->
    <div class="iv_main">
                        <!-- 사이드 바 -->
                        <ul>
                            <?=$sidemenu;?>
                        </ul>

                        <a href="Main.php" target="_self">
                            <img class="card-img" src="../web_image/weblogo.jpg">
                        </a>

                        <!-- myMenu: 로그인 관련-->
                        <ul class="navbar_myinfor_mypage">
                            <?=$login;?>
                        </ul>
                    </div>

    <!-- 페이지 이름 -->
    <h1 class="text_mypage">이 글은 비밀 글 입니다. </h1>
    <h1 class="text_mypage">비밀번호를 입력해주세요. </h1>

    <div  class="input-group mb-3" id="div_input_pw">
    <!--   문의글의 비밀 번호  -->
    <input type="hidden" id="pw" value="<?=$row['pw']?>">
    <!--  문의 글의 번호 -->
    <input type="hidden" id="number" value="<?=$row['number']?>">
    <input style="text-align:center; height: 70px; font-size:30px;" type="password" id="input_pw" class="form-control" placeholder="비밀번호를 입력해주세요" aria-label="Recipient's username" aria-describedby="basic-addon2">
        <button style="font-size: 40px;" class="btn btn-primary" onclick="pw_check();" type="button">입력</button>
    </div>
        
    </body>
    </html>