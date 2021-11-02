    <!--주문내역_관리자용 -->
    <?php
    //db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

    //1.세션설정
    session_start();
    settype($_SESSION['id'], 'integer');
    $id = $_SESSION['id'];

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

    /* 페이징 관련코드  */

    /*1. 현재 페이지 번호 받아오기 */
   if(isset($_GET["page"])){
    // 하단에서 다른 페이지 번호를 클릭하면 해당 페이지 값 가져와서 보여줌
    $page = $_GET["page"]; 
    }
    else {
    $page = 1; // 게시판 처음 들어가면 1페이지로 시작
    }
 
   // 한 페이지에 보여줄 게시물 개수
   $bulletin_count = 10;

    /*2. 페이지에 보여줄 게시물의 시작 번호 */
   $bulletin_start = ($page - 1) * $bulletin_count; 

  /*3. 게시물의 전체 로우의 개수 조회하기 */
  $sql_bulletin = "SELECT * FROM OrderList"; 
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
                <style>
                </style>
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
                <title>주문 내역(관리자용)</title>
            </head>
            <body>
            <script>
                 let delivery = {
                /* 1. 배송 시작(배송상태 "상품 준비  중" => "배송 중" 으로 변경) */    
                start: function() {
                    //주문번호를 모아놓은 배열
                    var order_Num_ar = new Array();
                    //운송장 번호를 모아놓은 배열
                    var transport_Num_ar = new Array();
                    
                    //1-1)배송 시작 물어보기 (Yes or No)
                    if (confirm("선택한 상품의 배송을 시작 하시겠습니까?") == true) { 
                    /* 2. => 체크된 박스들의 배송 상태를 "상품준비중" => "배송 중"으로 변경
                          => 체크된 박스들의 운송장 번호 input태그 입력처리(비활성화)
                    */
                    //2-1)체크된 박스 태그를 갖고온다.
                    let checkbox = $("input:checkbox[name=order_check]:checked");
                    //1-3)체크된 상품 확인  
                    if(checkbox.length == 0){
                        alert("상품을 선택해주세요.")
                        exit();
                    }

                     //1-4)이미 배송 중인 상품 체크 
                     for(let k = 0; k < checkbox.length ; k++){
                        //2-3)체크된 체크 박스의 로우를 갖고온다.
                        let tr =  checkbox.parent().parent().eq(k);
                        //2-4)로우의 전체 컬럼을 가져온다
                        let all_td = tr.children();
                        //2-5)배송 상태 컬럼을 갖고온다.
                        let delivery_status = all_td.eq(8).children().eq(0);
                        //배송상태 컬럼의 값이 하나라도 "배송 중"인 경우 
                        if(delivery_status.text() == "배송 중"){
                        alert("이미 배송중인 상품입니다.");
                        exit();
                    }
                    }

                    //2-2)체크된 상품의 수 만큼 반복
                    checkbox.each(function(i){ //i = 0부터 시작
                    //2-3)체크된 체크 박스의 로우를 갖고온다.
                    let tr =  checkbox.parent().parent().eq(i);
                    //2-4)로우의 전체 컬럼을 가져온다
                    let all_td = tr.children();
                    //2-5)배송 상태 컬럼을 갖고온다.
                    let delivery_status = all_td.eq(8).children().eq(0);
                    //2-6)운송장 번호 컬럼(input 태그)를 갖고온다.
                    let transPort_Num = all_td.eq(10).children().eq(0);

                       //1-5)운송장 번호 공백 체크
                    for(let i = 0; i < checkbox.length; i++){
                        let tr =  checkbox.parent().parent().eq(i);
                        //2-4)로우의 전체 컬럼을 가져온다
                        let all_td = tr.children();
                       //2-6)운송장 번호 컬럼(input 태그)를 갖고온다.
                       let transPort_Num = all_td.eq(10).children().eq(0);
                        if(transPort_Num.val().length == 0){
                        alert("운송장 번호를 입력해주세요.")
                        exit();
                       }
                       }
                       //2-7)로우의 배송상태 컬럼의 값을 "배송 중"으로 변경한다.
                       //*** 배송 상태 td(8).자식(0) => h1.text
                       delivery_status.text("배송 중");
                       //2-8)운송장 번호 입력 태그 비활성화
                       transPort_Num.attr("disabled", true);
                       
                       /* 3. 체크된 박스들의 주문번호, 운송장번호 배열에 저장 
                        -주문번호와, 운송장번호가 여러개인 경우 다중처리를 해줘야 해서 각각의 배열에 담는다.
                       */
                       //3-1)주문번호를 갖고온다
                       let order_Num = all_td.eq(1).children().eq(0).text();
                       //3-2)배열안에 주문번호를 담는다.
                       order_Num_ar.push(order_Num);
                       //3-3)배열안에 운송장 번호를 담는다. 
                       transport_Num_ar.push(transPort_Num.val());

                    });
                     /* 4.배열을 Json으로 변환해서 Post로 데이터를 페이지에 전달한다
                        -주문번호 배열  => JSON
                        -운송장번호 배열  => JSON
                        -delivery_check => 1번 (배송 시작)
                    */
                    post_to_url("../check/order_delivery.php", 
                                {'delivery_Json' : JSON.stringify(order_Num_ar),
                                 'transport_Json' : JSON.stringify(transport_Num_ar),
                                 'delivery_check' : 1
                                }
                                );
                    }else{ //배송 시작 선택 알림에서 취소를 누른 경우
                       exit();
                    } 
                   
                },

                /* 2.주문 취소 */
                cancel: function(){
                    if (confirm("배송을 취소 하시겠습니까?") == true) { 
                    //주문 번호를 담을 배열 선언
                    var order_num_ar = new Array();
                    
                     //체크된 박스 태그를 갖고온다.
                     let checkbox = $("input:checkbox[name=order_check]:checked");
                     //체크된 상품 확인(체크)
                     if(checkbox.length == 0){
                        alert("상품을 선택해주세요.")
                        exit();       
                    }
                     //체크된 갯수만큼 반복(for문)
                     checkbox.each(function(i){
                    //  체크된 박스의 로우를 갖고온다
                    let tr =  checkbox.parent().parent().eq(i);
                    // 로우의 전체 컬럼을 갖고온다
                    let all_td = tr.children();
                    // 컬럼 중 운송장 번호 컬럼의 input태그를 비활성화 시킨다.
                    all_td.eq(9).children().eq(0).attr('disabled', true)
                    // 컬럼 중 주문번호 컬럼의 값을 갖고온다.
                    let order_Num = all_td.eq(1).children().eq(0).text();
                    //주문번호를 배열에 저장한다.
                    order_num_ar.push(order_Num)
                    });

                     /* 배열을 Json으로 변환해서 Post로 데이터를 페이지에 전달한다
                        -주문번호 배열  => JSON
                        -delivery_check(배송 상태 처리번호) => 0번 (배송 취소)
                    */
                    post_to_url("../check/order_delivery.php", 
                                 {"delivery_Json": JSON.stringify(order_num_ar),
                                  "delivery_check" : 0
                                }   
                     )

                }else{ //배송 취소 선택 알림에서 취소를 누른 경우
                exit();    
            }
                
            }, 
            /* 3.배송 완료 */
            complete: function(){
                //배송 완료 선택 알림
                if (confirm("배송 완료를 하시겠습니까?") == true) { 
                    //주문 번호를 담을 배열 선언
                    var order_num_ar = new Array();
                    //체크된 박스 태그를 갖고온다.
                    let checkbox = $("input:checkbox[name=order_check]:checked");
                     //체크된 상품 확인(체크)
                     if(checkbox.length == 0){
                        alert("상품을 선택해주세요.")
                        exit();       
                    }
                    //체크된 갯수만큼 반복(for문)
                    checkbox.each(function(i){
                    //  체크된 박스의 로우를 갖고온다
                    let tr =  checkbox.parent().parent().eq(i);
                    // 로우의 전체 컬럼을 갖고온다
                    let all_td = tr.children();  
                    /* 1.배송 상태 컬럼의 값을 갖고온다 */
                    let delivery_status = all_td.eq(9).children().eq(0);
                    //1-1)배송상태가 == "배송 중"인 상품만 배송완료 처리 가능
                    if(delivery_status.text() != "배송 중"){
                        alert("배송 중인 상품만 가능합니다.");
                        exit();
                    }
                    
                    /* 2.주문번호 컬럼의 값을 갖고온다. 
                    */
                     let order_Num = all_td.eq(1).children().eq(0).text();
                     //2-1)주문번호를 배열에 저장한다.
                     order_num_ar.push(order_Num)

                    });
                    /* 배열을 Json으로 변환해서 Post로 데이터를 페이지에 전달한다
                        -주문번호 배열  => JSON
                        -delivery_check(배송 상태 처리번호) => 2번 (배송 완료)
                    */
                    post_to_url("../check/order_delivery.php", 
                                 {"delivery_Json": JSON.stringify(order_num_ar),
                                  "delivery_check" : 2
                                }   
                     )
                }else{//배송 완료 선택 알림에서 취소를 누른 경우
                    exit();   
                }
            },

             /* 3.상품 삭제 */
            delete: function(){
                // 삭제 확인 안내창 
                if (confirm("정말로 삭제하시겠습니까?") == true) { 
                  //주문 번호를 담을 배열 선언
                  var order_num_ar = new Array();
                  //체크된 박스 태그를 갖고온다.
                  let checkbox = $("input:checkbox[name=order_check]:checked");
                  //체크된 상품 확인(체크)
                  if(checkbox.length == 0){
                        alert("상품을 선택해주세요.")
                        exit();       
                    }  
                  //체크된 박스의 갯수만큼 반복(for문)
                  checkbox.each(function(i){
                  //  체크된 박스의 로우를 갖고온다
                  let tr =  checkbox.parent().parent().eq(i);
                  // 로우의 전체 컬럼을 갖고온다
                  let all_td = tr.children(); 
                  // 전체 컬럼에서 주문번호 컬럼의 값을 갖고온다.  
                  let order_Num = all_td.eq(1).children().eq(0).text();
                  //주문번호를 배열에 저장한다.
                  order_num_ar.push(order_Num)
                  });
                /* 배열을 Json으로 변환해서 Post로 데이터를 페이지에 전달한다
                    -주문번호 배열  => JSON
                    -delivery_check(배송 상태 처리번호) => 3번 (상품 삭제)
                                    */
                    post_to_url("../check/order_delivery.php", 
                                 {"delivery_Json": JSON.stringify(order_num_ar),
                                  "delivery_check" : 3
                                }   
                     )
                }else{ //삭제 확인 안내창에서 취소를 누른 경우
                    exit();
                }
            }
            };

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
                <h1 class="text_mypage">고객 주문 내역</h1>
                <!-- 보류 중!!!! -->
                    <!-- 주문 처리 버튼 모음 -->
                    <div class="div_btn_orderList">
                        <!-- 1.배송시작 버튼
                        배송 시작 => 배송 중 
                    -->
                        <button onclick="delivery.start();"
                                class="btn btn-lg btn-primary btn-block text-uppercase" >
             <h1>배송 시작</h1>
            </button>
                        <!-- 2.배송완료 버튼 
                        배송 중 => 배송 완료
                    -->
                        <button onclick="delivery.complete();"
                                class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">
             <h1>배송완료</h1>
            </button>
                        <!-- 3.배송취소  버튼 
                    -->
                        <button onclick="delivery.cancel();"
                                class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">
            <h1>배송취소</h1>
                        </button>

                        <!-- 4.상품 삭제 버튼 -->
                        <button onclick="delivery.delete();"
                                class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">
             <h1>상품 삭제</h1>
            </button>
                    </div>

                    <!-- Main -->
                    <main class="main">
                         <!--페이지번호 표시 -->
      
        <!-- 등록한 상품 내역 테이블 -->
        <div class="table_orderList">
            <table class="table table-hover">
                <!-- 테이블 헤드 -->
                <thead>
                    <tr>
                        <!-- 체크박스 -->
                        <th style="width:60px;"></th>
                        <th>주문번호</th>
                        <!-- 상품정보: 상품 이미지 + 상품 이름 + 상품 사이즈 -->
                        <th style="text-align: center;">상품 정보</th>
                        <th style="width:120px;">수량</th>
                        <th>상품가격</th>
                        <th>배송비</th>
                        <th>주문자 이름</th>
                        <th style="width:500px;">주소</th>
                        <th>전화번호</th>
                        <th>배송 상태</th>
                        <th>운송장 번호</th>
                        <th style="width:300px;">배송 요청사항</th>
                    </tr>
                </thead>

                <!-- 테이블 바디 -->
                <tbody>
                    <?php 
            //페이징 처리한 전체 상품 정보 조회(5개씩)
            $sql_select = "SELECT * FROM OrderList ORDER BY auto_num DESC LIMIT $bulletin_start, $bulletin_count"; 
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
                <!-- 1.체크 박스 -->
                <td style="width:50px;">
                    <input style="transform:scale(3)" type="checkbox" name="order_check" >
                </td>

                <!-- 2.주문번호 -->
                <td style="width:300px">
                    <h1><?=$row_orderList['order_num'] ?></h1>
                </td>

                <!-- 3.품목 -->
                <td style="width: 600px;" class="item_name_img">
                    <!-- 1-1)상품이미지 -->
                    <img src="../image_files/<?=$row_orderList['img_url']?>" alt="" style="width: 280px; height: 260px; margin-right:15px">
                    <!-- 1-2)상품이름 -->
                    <h1>
                        <?=$row_orderList['item_name'] ?>
                    </h1>
                </td>

                <!-- 4.수량 -->
                <td style="text-align:center;">
                    <h1>
                        <?=$row_orderList['item_count'] ?>
                    </h1>
                </td>

                <!--5.상품가격  -->
                <td class="cost">
                    <h1>
                        <?=$row_orderList['item_cost'] ?>
                    </h1>
                </td>

                <!--배송비  -->
                <td class="cost">
                    <h1>2500원</h1>
                </td>

                <!--6.주문자이름  -->
                <td class="buyer_name">
                    <h1>
                        <?=$row_orderList['buyer_name'] ?>
                    </h1>
                </td>

                <!--7.주소  -->
                <td style="width:200px;" class="cost">
                    <h1>
                        <?=$row_orderList['addr'] ?>
                    </h1>
                </td>

                <!--8.전화번호  -->
                <td class="phone_num">
                    <h1>
                        <?=$row_orderList['phone_num'] ?>
                    </h1>
                </td>

                 <!--9.배송 상태  -->
                 <td class="delivery">
                     <!-- 배송 처리 번호에 따라서 $delivery의 텍스트값이 달라짐. -->
                    <h1 id="delivery_status"><?=$delivery?></h1>
                </td>
                
                <!--10.운송장 번호  -->
                <td  class="transport_num">
                <?php 
                //1)delivery_num(배송 상태 번호)가 0인 경우 => 입력 버튼 활성화
                if($row_orderList['delivery_num'] == 0){
                ?>
                <input id="input_transport_num"
                style="font-size:40px;"
                value=""
                type="text">
                <?php 
                /* 2)delivery_num가 0이 아닌 경우  
                 - 운송장 번호입력
                 - 입력 버튼 비활성화
                */
                } else { ?>
                    <input id="input_transport_num"
                    style="font-size:40px;"
                    disabled
                    value="<?=$row_orderList['transport_num']?>"
                    type="text">    
                <?php
                }
                ?>
                    
                </td>
                 <!--11.배송 요청사항  -->
                 <td class="delivery">
                    <h1  id="delivery_status"><?=$row_orderList['request']?></h1>
                </td>
            </tr>
            <?php 
}
?>
        </tbody>
        <!-- 테이블 내용  -->
    </table>

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
                <li class='page-item'><a class='page-link' id="page_item" href='OrderList.php?page=1'>처음</a></li>
                <!-- '이전' 페이지로 이동 -->
                <li class='page-item'><a class='page-link' id="page_item" href='OrderList.php?page=<?=$pre?>'>◀ 이전</a></li>
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
                <li class='page-item'><a class='page-link' href='OrderList.php?page=<?=$i?>'><?=$i?></a></li>
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
                <li class='page-item'><a class='page-link' id="page_item" href='OrderList.php?page=<?=$next?>'>다음 ▶</a>
            <!-- 마지막 페이지로 이동 -->
                <li class='page-item'><a class='page-link' id="page_item" href='OrderList.php?page=<?=$total_page_num?>'>마지막</a>
            <?php
            }
            ?>
        </ul>
        </nav>
    </div>

    </nav>
    </main>
</body>

</html>