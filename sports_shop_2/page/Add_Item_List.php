<?php

//1.db연결
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//2.세션설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];

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

  /*1. 현재 페이지 번호 받아오기 */
  if(isset($_GET["page"])){
    // 하단에서 다른 페이지 번호를 클릭하면 해당 페이지 값 가져와서 보여줌
    $page = $_GET["page"]; 
    }
    else {
    $page = 1; // 게시판 처음 들어가면 1페이지로 시작
    }


    /*3. 게시물의 전체 로우의 개수 조회하기 */
    $sql_bulletin = "SELECT * FROM Add_Item"; 
    $result_all = mysqli_query($con, $sql_bulletin);
    // 전체 게시물의 행 개수 조회하기
    $bulletin_total = mysqli_num_rows($result_all);
    // 한 페이지에 보여줄 게시물 개수
    $bulletin_count = 10;

    /*4.한 페이지에 보여줄 게시물의 시작 번호 */
    $bulletin_start = ($page - 1) * $bulletin_count; 

    /*5.전체 필요한 페이지의 수  */
    $total_page_num = ceil($bulletin_total / $bulletin_count); 

    /*6.한 블록에 표시할 페이지 개수 */
    $page_count = 10; 

    /*7.현재 페이지 블록  */
    $now_page_num = ceil($page / $page_count); 

    /*8.페이지의 시작번호 */
    $page_start_num = (($now_page_num - 1) * $page_count) + 1;
        
    /*9. 페이지의 마지막번호 */
    $page_end_num = $page_start_num + $page_count - 1;

    /*10.페이지 마지막 번호가 전체 페이지 수 보다 큰 경우(예외처리)*/
    if($page_end_num > $total_page_num){
    // 블록 마지막 번호가 총 페이지 수보다 크면 마지막 페이지 번호를 총 페이지 수로 지정함
      $page_end_num = $total_page_num;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
</style>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--자주 사용하는  js파일 함수 모음 -->
    <script src="../js_file/Global.js"></script>
    <!-- css 파일 적용 -->
    <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
     <!-- 부트스트랩 적용 -->
    <link rel="stylesheet"  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
    <title>등록한 상품 리스트</title>
</head>
<body>
  <script>
  function scroll_move(page){
      window.onload = function(){
      var offset = $(".text_mypage").offset();
          $('html, body').animate({scrollTop : offset.top}, 400);
  } 
      }

      scroll_move(<?=$page?>)
  </script>

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
<h1 class="text_mypage">상품등록내역</h1>

<!-- Main -->
<main class ="main">
    <!-- 전체 테이블!! -->
    <table class="table table-hover">
      <!-- 테이블 헤드 -->
    <thead class="th_table">
      <tr>
        <th style="text-align: center; ">
        <!-- 1.품목 -->
        <h1>품목</h1>
      </th>
        <th> 
            <!-- 2.카테고리 -->
          <h1>카테고리</h1>
      </th>

      <th style="width: 300px;"> 
            <!-- 3.세부 카테고리 -->
          <h1>세부 카테고리</h1>
      </th>
        <th style="width: 200px;">
       <!-- 4.수량 -->
        <h1>수량</h1>
      </th>
        <th>
            <!-- 5.결제금액 -->
          <h1>결제금액</h1>
        </th>

      </tr>
    </thead>
    <!-- 테이블 바디 -->
    <tbody>
    <?php 
    //상품 정보 불러오기
    $sql_select = "SELECT * FROM Add_Item "; 
    $result_select = mysqli_query($con, $sql_select);
   //게시글의 정보를 페이징 처리해서 조회한다  ($start 부터  $count의 개수만큼)
   $sql_select = "SELECT * FROM Add_Item ORDER BY item_num_A DESC LIMIT $bulletin_start, $bulletin_count"; 
   $result_select = mysqli_query($con, $sql_select);
   while ($row = mysqli_fetch_assoc($result_select)) {
    ?>
    <!-- 테이블의 행 -->
    <tr class="tr_add_item_List">
     <!-- 1.품목 -->
   <td class="item_name_img" style="width: 800px;"
    onclick="location.href=location.href='Item_Infor.php?item_num=<?=$row['item_num_A']?>'">
    
 <!-- 1-1)상품이미지 -->
   <img src="../image_files/<?=$row['img_url'] ?>" alt="" style="width: 300px; height: 300px; margin-right:15px">
 <!-- 1-2)상품이름 -->
   <h1> <?=$row['item_name_A'] ?> </h1>
   </td>
   <!-- 2.카테고리 -->
   <td class="category" 
        onclick="location.href=location.href='Item_Infor.php?item_num=<?=$row['item_num_A']?>'">
        <h1
        ><?=$row['category'] ?> </h1> 
  </td>

    <!-- 3.세부 카테고리 -->
    <td class="category_detail" 
        onclick="location.href=location.href='Item_Infor.php?item_num=<?=$row['item_num_A']?>'">
        <h1
        ><?=$row['category_detail'] ?> </h1> 
  </td>
    <!--4. 수량 -->
    <td 
        class="count" 
        onclick="location.href=location.href='Item_Infor.php?item_num=<?=$row['item_num_A']?>'"> 
        <h1><?=$row['count'] ?></td> </h1>
        
  <!-- 5.결제금액 -->
  <td class="cost"
        onclick="location.href=location.href='Item_Infor.php?item_num=<?=$row['item_num_A']?>'"> 
   <h1><?=$row['cost'] ?></h1> 
  </td>

  <!-- 6.수정 삭제 버튼 -->
  <td class="td_button"> 
      <!-- 클릭한 상품의 번호를 get으로 보낸다. -->
      <!-- 6-1)수정 -->
    <button  style="width: 150px; height:80px;" 
             class="btn btn-lg btn-primary btn-block text-uppercase" 
             type="submit"
             onclick='btn_click("edit", <?=$row['item_num_A']?> );'>
             <h1>수정</h1>
            </button>
            
      <!-- 6-2)삭제  -->
    <button style="width: 150px; height:80px;"
            class="btn btn-lg btn-primary btn-block text-uppercase" 
            type="submit" 
            onclick='btn_click("delete", <?=$row['item_num_A']?>);'>
            <h1>삭제</h1>
          </button>
  </td>
     </tr>
    <?php }
?>    
    </tbody>
  </table> <!--페이지번호 표시 -->
   <nav class="page_nav">
    <ul class="page_ul">
    <?php 
   /* 1.페이지의 '처음', '이전' 텍스트링크 표시하기 */
    // 1-1)첫 페이지인 경우
    if ($page <= 1){
      //   -'처음' 텍스트링크 없애기
      //   -'이전' 텍스트링크 없애기
      } else {
      //1-2)첫 페이지가 아닌 경우
      $pre = $page - 1; //이전할 페이지의 번호
        /*   현재 페이지가 1보다 큰 경우 */
        ?>
        <!-- '처음' 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='Add_Item_List.php?page = 1'>처음</a></li>
        <!-- '이전' 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='Add_Item_List.php?page=<?=$pre?>'>◀ 이전</a></li>
      <?php
      }
      
    /* 2.페이지 갯수만큼 페이지 번호 표시 */
    for($i = $page_start_num; $i <= $page_end_num; $i++){
      /* 현재 내가 위치한 페이지의 번호와 다른 페이지 번호의 색깔을 구분한다 */
      if($page == $i){
          //현재 내가 위치한 페이지의 번호
        ?>
          <li class='page-item'>
          <a class='page-link' disabled style="color: #df7366;"><?=$i?></a>
          </li>
      <?php
      } else {
          //다른 페이지의 번호
        ?>
        <li class='page-item'><a class='page-link' href='Add_Item_List.php?page=<?=$i?>'><?=$i?></a></li>
      <?php
      }
  }

     /* 3.마지막 페이지인 경우 '다음', '마지막' 텍스트 링크 표시하기*/
      // 3-1)마지막 페이지인 경우
      if($page >= $total_page_num){
      } else {
      // 3-2)마지막 페이지가 아닌 경우    
      $next = $page + 1; //이동할 페이지 
      ?>
        <li class='page-item'><a class='page-link' id="page_item" href='Add_Item_List.php?page=<?=$next?>'>다음 ▶</a>
      <!-- 마지막 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='Add_Item_List.php?page=<?=$total_page_num?>'>마지막</a>
      <?php
      }
    ?>
</ul>
</nav>
</main>

</body>

</html>

<!-- 자바 스크립트 코드 -->
<script>

/* 아이템 수정, 삭제 */
  //1)클릭한 버튼에 따라서 페이지 이동하는 함수
  function btn_click(str, item_num){  
    //1)수정
    //메서드에서 인자값으로 받은 Str변수에 따라서 코드 진행  
    if(str == "edit"){   
        //1-1)클릭한 상품의 번호를 저장한다.
        //1-2)키:value 형식으로  post를 사용해서 배열에 데이터를 저장한다 
        //1-3)아이템 수정/입력 페이지로 이동
        post_to_url("Add_Edit_Item.php", {'edit': 1,'item_num': item_num});
    }//2)삭제 
    else if(str == "delete"){ 
       //1)yes 삭제완료
    console.log("1:" + item_num)

    if (confirm("정말로 삭제하시겠습니까?") == true) {
    //삭제 페이지로 이동 후 get으로 삭제할 상품의 번호 전달 
    move_page_link('../check/item_delete.php?item_num=' + item_num);
    } //2)No //삭제 취소
    else {
        return;
    }     
         
    } 
}

    //2)post방식으로 데이터를 전달하는 함수
  function post_to_url(path, params, method) {
      //post방식 사용
    method = method || "post";     
    //post형식을 받을 form태그를 생성   
    var form = document.createElement("form");       
    //post방식을 입력
    form.setAttribute("method", method);
    //이동할 페이지 경로를 입력
    form.setAttribute("action", path);        
    //key:value로 저장한 배열의 갯수만큼 반복한다.
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key); //키
        hiddenField.setAttribute("value", params[key]); //value
        form.appendChild(hiddenField);
    }        document.body.appendChild(form);
    //전달
    form.submit();
    }

 </script>