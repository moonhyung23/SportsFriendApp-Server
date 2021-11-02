<!-- 삭제 이유를 받는 페이지 -->

<?php
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

 //2.세션 초기화
 session_start();
 settype($_SESSION['id'], 'integer');
 $id = $_SESSION['id'];

  /* 사용자 로그인 메뉴 ui */
     //1-1)비로그인
     if($_SESSION['id']==null) {
        $login = '
        <li><a href="Login.php">로그인</a></li>
        <li><a href="Agree.php">회원가입</a></li>
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
     <!-- 상단 헤더 css 파일 -->
     <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- 부트스트랩5 링크 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- (공용)많이 쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>
    <title>회원 탈퇴 페이지</title>
</head>
<body>
<div class="iv_main">
    <!-- 사이드 바 -->
    <ul>
        <?=$sidemenu;?>
    </ul>
    
<!-- 메인이미지 -->
    <a href="Main.php" target="_self">
    <img class="card-img" src="../web_image/weblogo.jpg">
    </a>

    <!-- myMenu: 로그인 관련-->
    <ul class="navbar_myinfor_mypage">
        <?=$login;?>
    </ul>
</div>
 <!-- 페이지 이름 -->
 <h1 class="text_mypage">회원탈퇴</h1>
 <main class="main">
    
 <div class="form-group shadow-textarea" style=" text-align:center; position: absolute; left: 50%; transform: translateX(-50%);">
 <input id="ipt_pw" type="password" require class="form-control" style="width: 400px; height:60px; text-align:center; font-size:30px;" placeholder="비밀번호를 입력해주세요" aria-label="Username ">
    <!-- 회원 탈퇴 버튼  -->
  <input  class="btn btn-primary" onclick="delete_check()" type="submit" name="submit" 
 value="회원 탈퇴" style="font-size: 30px; width: 200px; margin:10px;" id="btn_additem" role="button" />
</div>
  
 </main>
</body>
</html>


    <script>
    function delete_check(){
    //입력한 비밀번호를 받아온다
    let input_pw = document.getElementById('ipt_pw').value;
    
    if(input_pw == ""){
        alert("비밀번호를 입력해주세요")
        return;
    }

    //회원 php 파일로 이동
    post_to_url("../check/Delete_Register_Chk.php", {'pw' : input_pw});
    } 


    </script>