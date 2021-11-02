<?php
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
   //2.세션 초기화
   session_start();
   settype($_SESSION['id'], 'integer');
   $id = $_SESSION['id'];

    //나의 사용자 정보를 조회한다(사용자 정보 테이블)
    $sql_select = "SELECT * FROM User WHERE id = $id"; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);

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

    /*1. 현재 페이지 번호 받아오기 */
    if(isset($_GET["page"])){
        // 하단에서 다른 페이지 번호를 클릭하면 해당 페이지 값 가져와서 보여줌
        $page = $_GET["page"]; 
        }
        else {
        $page = 1; // 게시판 처음 들어가면 1페이지로 시작
        }


    

  /*3. 게시물의 전체 로우의 개수 조회하기 */
  $sql_bulletin = "SELECT * FROM Basket"; 
  $result_all = mysqli_query($con, $sql_bulletin);
  // 전체 게시물의 행 개수 조회하기
  $bulletin_total = mysqli_num_rows($result_all);
  // 한 페이지에 보여줄 게시물 개수
  $bulletin_count = 5;

  
  /*4.페이지 하나 당 게시물 나누기 */
   //4-1)페이지에 보여줄 게시물의 시작 번호 
  $bulletin_start = ($page - 1) * $bulletin_count; 
  
  //4-2)전체 필요한 페이지의 수  
  $total_page_num = ceil($bulletin_total / $bulletin_count); 
  
  // *블록: 페이지 하단의 페이지의 번호를 표시하는 영역

    
  /* 5.블록 한개 당 페이지번호 나누기 */
  //5-1)한 블록에 표시할 페이지 개수 
  $page_count = 5; 
  
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
    <!-- JQuery 파일 -->
    <!-- 반드시 js쿼리를 적용하려는 js파일보다 위에 있어야 한다 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- (공용)많이 쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>
        <!-- css 파일 -->
    <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
     <!-- font-awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous" />
    <!-- 부트스트랩5 링크 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- js파일 -->
    <script src="../js_file/item_basket.js"></script>
    <title>장바구니</title>
</head>
<body>
<script>
/* 페이지 시작 시 장바구니 상품갯수, 가격 계산  */
window.onload = function() {
    basket.reCalc();
    basket.updateUI();
};
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
 <h1 class="text_mypage">장바구니</h1>
   <!-- Main -->
   <main class="main">
        <!-- 상품 목록 테이블 -->
        <div class="div_basket_table">
        <table class="table table-hover">
                <!-- 테이블 헤드 -->
                <thead >
                    <tr>
                        <!-- 체크박스 -->
                        <th style="width:60px;"></th>
                        <!-- 상품정보: 상품 이미지 + 상품 이름 + 상품 사이즈 -->
                        <th style="text-align: center;">상품 정보</th>
                         <th style="width:250px;">가격</th>
                        <th style="width:350px;">수량</th>
                        <th style="width:250px;">합계</th>
                        <th>삭제</th>
                    </tr>
                </thead>

                <!-- 테이블 바디 -->
                <tbody>
            <?php 
            /* 나의 장바구니 정보를 조회한다 */
            $sql_select = "SELECT * FROM Basket WHERE id = $id ORDER BY basket_num DESC LIMIT $bulletin_start, $bulletin_count "; 
            $result_select_basket = mysqli_query($con, $sql_select);
            while($row_basket = mysqli_fetch_assoc($result_select_basket)){
            $item_number = $row_basket['number'];//상품번호
            /*장바구니에 추가한 상품의 번호와 맞는 상품정보를 조회한다 */
            $sql_select = "SELECT * FROM Add_Item WHERE item_num_A = $item_number"; 
            $result_select = mysqli_query($con, $sql_select);
            //상품 정보 로우
            $row_item = mysqli_fetch_assoc($result_select);
           
            //상품 하나의 가격합계(수량 * 가격)
            $item_price = $row_basket['count'] * $row_item['cost'];
            
            //카테고리가 농구공이 아닌 경우에만 상품 '사이즈'컬럼 추가
            if($row_item['category'] != "농구공"){
                //상품 이름 + 상품 사이즈
                $item_name =  $row_item['item_name_A']."(".$row_basket['size'].")";
            }else {
                //농구공인 경우 사이즈 X
                $item_name =  $row_item['item_name_A'];
            }
            ?>
           
            <tr class="tr_basket">
                <!-- 1.체크 박스 -->
                <td style="width:50px;">
                <input onclick="basket.item_check();"
                       style="transform:scale(3); margin-top:15px;" 
                       class="form-check-input" 
                       type="checkbox" 
                       name="check_basket"
                       checked
                       id="check_basket">
                </td>
                <!-- 2.상품정보 -->
                <td style="width: 800px;" class="item_name_img">
                    <!-- 2-1)상품이미지 -->
                    <img src="../image_files/<?=$row_item['img_url']?>" alt="" 
                    style="width: 300px; height: 260px; margin-right:15px; margin-top:10px;">
                    <!-- 2-2)상품정보(상품이름 + 상품 사이즈) -->
                    <h1><?=$item_name?> </h1>
                </td>

                <!--3.상품가격(1개당)  -->
                <td style="text-align:center;">
                    <h1 style="font-size:50px;"><?=$row_item['cost']?></h1>
                </td>


                <!-- 4.수량-->
                <td style="text-align:center;">
                <div class="num">
                        <div class="updown">
                            <!-- 수량을 확인하는 input태그 
                                장바구니 로우(row_basket) 사용 -->
                                <!-- id를 장바구니 번호로  지정한다 -->
                            <input type="text" onchange="basket.input_change_count(<?=(string)$row_basket['basket_num']?>)" name="p_num"  id="<?=(string)$row_basket['basket_num']?>" size="2"class="p_num" value="<?=$row_basket['count']?>"  />
                            <!-- 업버튼 -->
                            <span >
                            <i class="fas fa-arrow-alt-circle-up up" 
                               onclick="javascript:basket.changePNum(<?=(string)$row_basket['basket_num']?>);"></i>
                            </span>
                            <!-- 다운 버튼 --> 
                            <span >
                            <i class="fas fa-arrow-alt-circle-down down"
                               onclick="javascript:basket.changePNum(<?=(string)$row_basket['basket_num']?>);"></i>
                            </span>
                        </div>
                    </div>
                </td>
                <!--5.합계  -->
                <td style="text-align:center;">
                    <h1 id="item_cost" style="font-size:50px;"><?=$item_price?></h1>
                </td>

                <!-- 6.삭제 버튼 -->
                <td style="text-align:center; ">
                <button id="btn_delete_basket_item"
                        style="font-size: 40px;" 
                        onclick="basket.delItem(<?=$row_basket['basket_num']?>)" 
                        type="button" class="btn btn-primary">삭제</button>
                </td>
                <!-- 7.상품 번호 -->
                <input type="hidden" value="<?=$row_basket['number']?>">
                <!--8. 장바구니 상품 번호 
                -결제완료 시 체크된 장바구니 상품을 삭제할 때 구분하기 위해서-->
                <td>
             <input type="hidden" id="basket_num" value="<?=$row_basket['basket_num']?>">
             
                </td>
               
            </tr><!-- 테이블 행 End -->  
           <?php  }
                    ?>
        </tbody>
        <!-- 테이블 내용  -->
    </table>
    <div class="div_basket_option">
    <div id="sum_p_num">상품 갯수: 0개 </div>
    <div id="">배송비: 2500원</div>
        <div id="sum_p_price">상품 금액: 0원 </div>
    <div>
    <!-- 상품 주문 버튼 -->
    <button id="btn_item_order_basket" onclick="basket.item_order();" type="button" class="btn btn-primary">선택한 상품 주문</button>
    </div>
     </div>   
</div>
</main>
</body>
</html>


