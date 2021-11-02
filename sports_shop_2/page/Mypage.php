<?php 
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";


//1.세션설정
session_start();
$id = $_SESSION['id'];
settype($_SESSION['id'], 'integer');

//1-1)비로그인
 if($_SESSION['id']==null) {
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
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
        
    <link rel="stylesheet"  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script  src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- id에 따라서 제목 분리 
        -관리자 계정($id =1)
        -회원 계정($id != 1)
    -->
    <?php 
    /* 관리자 계정 */
    if($_SESSION['id'] == 1){
       ?> <title>관리자 페이지</title>
    <?php
    }else{
    /* 회원 계정 */
        ?>
        <title>마이 페이지</title>
        <?php 
    }
    ?> 
    </head>

<body>
<nav class ="header">

<!-- myMenu: 로그인 관련-->
<ul style="margin: 20px;" class="navbar_myinfor_mypage">
<?=$login;?>
</ul>
</nav>

<!-- 메인이미지 -->
<div class= "div_main_img">
<a href="Main.php" target="_self">
    <img class="card-img" src="../web_image/weblogo.jpg"  style="border:0px; width:350px; height:300px;">
</a>
</div> 

<!-- 페이지 이름 -->
<?php 
    /* 관리자 계정 */
    if($_SESSION['id'] == 1){
       ?> <h1 class="text_mypage">관리자 페이지</h1>
    <?php
    }else{
    /* 회원 계정 */
        ?>
        <h1 class="text_mypage">마이 페이지</h1>
        <?php 
    }
    ?>   
<main class ="main">
<ul id="mypage_sidemenu">
    <?=$sidemenu?>
     </ul>
</main>
</body>
</html>
