<!-- 비밀번호 검사 페이지(비밀번호 입력해야됨) -->

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
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

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
    <h1 class="text_mypage">    회원정보 수정   </h1>
    <h1 class="text_mypage">사용자 비밀번호를 입력해주세요.</h1>
    <form action="../check/Pw_register_Chk.php" method="post">
    <div  class="input-group mb-3" id="div_input_pw">
    <!-- 1)입력한 사용자 비밀번호 -->
    <input style="text-align:center; height: 70px; font-size:30px;" type="password" name="input_pw" id="input_pw" class="form-control" placeholder="비밀번호를 입력해주세요" aria-label="Recipient's username" aria-describedby="basic-addon2">
    <!-- 2)비밀번호 확인 버튼 -->
    <button style="font-size: 40px;" class="btn btn-primary" type="submit">입력</button>
    </div>
    </form>
  
        
    </body>
    </html>