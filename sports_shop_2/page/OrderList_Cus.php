<!--주문내역_고객용 -->
<?php

  //db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";



   //1.세션설정
   session_start();
   settype($_SESSION['id'], 'integer');
   $id = $_SESSION['id'];

/* 2.로그인 ui */
 //2-1)비로그인
 if($_SESSION['id'] == null) {
    $login = '
    <li><a href="Login.php">로그인</a></li>
    <li><a href="Agree.php">회원가입</a></li>
    <li><a href=""></a></li>
    <li><a href=""></a></li>
    <li><a href=""></a></li>
';

//2-2)로그인
}else{
    $login = '
    <li><a href="../check/Logout_check.php">로그아웃</a></li>
    <li><a href="Basket.php">장바구니</a></li>
';
}
   /* 3.사이드메뉴 ui */
   $sidemenu = Side_Menu($_SESSION['id']);


/*1. 현재 페이지 번호 받아오기 */
   if(isset($_GET["page"])){
    // 하단에서 다른 페이지 번호를 클릭하면 해당 페이지 값 가져와서 보여줌
    $page = $_GET["page"]; 
    }
    else {
    $page = 1; // 게시판 처음 들어가면 1페이지로 시작
    }
    
  /* 2. 한 페이지에 보여줄 게시물 개수 */
  $bulletin_count = 10;
  
  /*3. 게시물의 전체 로우의 개수 조회하기 */
  $sql_bulletin = "SELECT * FROM OrderList WHERE id = $id"; 
  $result_all = mysqli_query($con, $sql_bulletin);
  // 전체 게시물의 행 개수 조회하기
  $bulletin_total = mysqli_num_rows($result_all);


  
  /*4.페이지 하나 당 게시물 나누기 */
   //4-1)페이지에 보여줄 게시물의 시작 번호 
  $bulletin_start = ($page - 1) * $bulletin_count; 
  
  //4-2)전체 필요한 페이지의 수  
  $total_page_num = ceil($bulletin_total / $bulletin_count); 
  
  // *블록: 페이지 하단의 페이지의 번호를 표시하는 영역

    
  /* 5.블록 한개 당 페이지번호 나누기 */
  //5-1)한 블록에 표시할 페이지 개수 
  $page_count = 10; 
  
  // 5-2)현재 페이지 블록  
  $now_page_num = ceil($page / $page_count); 
  
  //5-3)페이지의 시작번호 
   $page_start_num = (($now_page_num - 1) * $page_count) + 1;
      
  //5-4)페이지의 마지막번호 
   $page_end_num = $page_start_num + $page_count - 1;
  
  //5-5)페이지 마지막 번호가 전체 페이지 수 보다 큰 경우(예외처리)
  if($page_end_num > $total_page_num){
  // 블록 마지막 번호가 총 페이지 수보다 크면 마지막 페이지 번호를 총 페이지 수로 지정함
    $page_end_num = $total_page_num;
  }
  
  
 
    ?> 
    
 


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css 파일 적용 -->
    <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
    <!-- 부트스트랩 적용 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- (공용)많이 쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>
    <!-- J쿼리 적용 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>주문내역</title>
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

<nav class="header">
                </nav>

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
                <h1 class="text_mypage">주문 내역</h1>
                <!-- 보류 중!!!! -->
                    <!-- Main -->
                    <main class="main">
                        <!-- nav (사이드바, 주문 내역) -->
                        <!-- 등록한 상품 내역 테이블 -->
                        <div class="table_orderList">
                            <table class="table table-hover">
                                <!-- 테이블 헤드 -->
                                <thead>
                                    <tr>
                                        <th>주문번호</th>
                                        <!-- 상품정보: 상품 이미지 + 상품 이름 + 상품 사이즈 -->
                                        <th style="text-align: center;">상품 정보</th>
                                        <th style="width:120px;">수량</th>
                                        <th>결제금액</th>
                                        <th>주문자 이름</th>
                                        <th style="width:500px;">주소</th>
                                        <th>전화번호</th>
                                        <th>배송 요청사항</th>
                                        <th>배송 상태</th>
                                        <th>운송장 번호</th>
                                    </tr>
                                </thead>

                                <!-- 테이블 바디 -->
                                <tbody>
             <?php 
            //고객의 상품 정보 조회(고객의 id와 같은 행만 조회)
            $sql_select = "SELECT * FROM OrderList WHERE id = $id ORDER BY auto_num DESC LIMIT $bulletin_start, $bulletin_count"; 
            $result_select = mysqli_query($con, $sql_select);
            while ($row_orderList = mysqli_fetch_assoc($result_select)) {
            /* delivery_num 컬럼의 번호에 따른 배송처리 
            $delivey_num = 0 => 상품 준비 중
                            1 => 배송 중 
                            2 => 배송 완료
            */
            if($row_orderList['delivery_num'] == 0){
                $delivery = "상품 준비 중";
            }else if($row_orderList['delivery_num'] == 1){
                $delivery = "배송 중";
            }else if($row_orderList['delivery_num'] == 2){
                $delivery = "배송 완료";
            }
            ?>
            <!-- 테이블의 행 -->
            <tr class="tr_orderList">
                <!-- 1.주문번호 -->
                <td style="width:300px">
                    <h1><?=$row_orderList['order_num'] ?></h1>
                </td>

                <!-- 2.품목 -->
                <td style="width: 600px;" class="item_name_img">
                    <!-- 1-1)상품이미지 -->
                    <img src="../image_files/<?=$row_orderList['img_url']?>" alt="" style="width: 280px; height: 260px; margin-right:15px">
                    <!-- 1-2)상품이름 -->
                    <h1>
                        <?=$row_orderList['item_name'] ?>
                    </h1>
                </td>

                <!-- 3.수량 -->
                <td style="text-align:center;">
                    <h1>
                        <?=$row_orderList['item_count'] ?>
                    </h1>
                </td>

                <!--4.결제금액  -->
                <td class="cost">
                    <h1>
                        <?=$row_orderList['item_cost'] ?>
                    </h1>
                </td>

                <!--5.주문자이름  -->
                <td class="buyer_name">
                    <h1>
                        <?=$row_orderList['buyer_name'] ?>
                    </h1>
                </td>

                <!--6.주소  -->
                <td style="width:200px;" class="cost">
                    <h1>
                        <?=$row_orderList['addr'] ?>
                    </h1>
                </td>

                <!--7.전화번호  -->
                <td class="phone_num">
                    <h1>
                        <?=$row_orderList['phone_num']?>
                    </h1>
                </td>

                <!--9.배송 요청사항  -->
                <td class="delivery">
                    <h1 id="delivery_status"><?=$row_orderList['request']?></h1>
                </td>
                
                <!--10.배송 상태  -->
                <td class="delivery">
                    <h1 id="delivery_status"><?=$delivery?></h1>
                </td>

                <!--11.운송장 번호  -->
                <td  class="transport_num">
                <input id="input_transport_num"
                       style="font-size:40px;"
                       value="<?=$row_orderList['transport_num']?>"
                       disabled
                       type="text">
                </td>
            </tr>
            <?php 
}
?>
        </tbody>
    </table>
    </div>

    </nav>
        <!--페이지번호 표시 -->
        <nav class="page_nav">
    <ul class="page_ul_order">
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
        <li class='page-item'><a class='page-link' id="page_item" href='OrderList_Cus.php?page=1'>처음</a></li>
        <!-- '이전' 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='OrderList_Cus.php?page=<?=$pre?>'>◀ 이전</a></li>
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
        <li class='page-item'><a class='page-link' href='OrderList_Cus.php?page=<?=$i?>'><?=$i?></a></li>
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
        <li class='page-item'><a class='page-link' id="page_item" href='OrderList_Cus.php?page=<?=$next?>'>다음 ▶</a>
      <!-- 마지막 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='OrderList_Cus.php?page=<?=$total_page_num?>'>마지막</a>
      <?php
      }
    ?>
</ul>
</nav>
    </main>
</body>
</html>
