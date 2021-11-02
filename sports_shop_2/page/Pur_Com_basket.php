<!-- 결제완료 페이지 (장바구니 전용) -->



<?php

//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
    
//2.세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];


$item_infor = $_POST['item_infor_json']; //1)상품정보 json 배열
$phone_Num = "0".$_POST['phone_Num']; //2)구매자 전화번호 
$addr = $_POST['addr']; //3)구매자 주소 앞부분
$addr_detail = $_POST['addr_detail'];//4)구매자 상세주소
$post_num = $_POST['post_num'];//5)구매자 우편변호
$item_cost_all = $_POST['item_cost'];//6)전체 결제금액
$request_detail = $_POST['request_detail'];//7)결제 요청사항
$redundancy_chk = $_POST['redundancy_chk'];//8)주문 중복체크 번호
$buyer_Name = $_POST['buyer_Name'];//9)구매자 이름

    /*자식의  배열(상품 정보) 인덱스 
            -0번: 상품수량 
            -1번: 상품가격
            -2번: 상품번호 
            -3번: 상품정보(상품이름 + 사이즈)
            -4번: 상품이미지
            */
//상품정보 Json배열을 배열로 변환한다.
$item_infor_ar = json_decode($item_infor);
//배열의 길이
$item_size = count($item_infor_ar) +1;

/* 합쳐져있는  데이터  */
//1)배송주소 => 앞주소 + 상세주소 + 우편번호 (주소 합치기)
//2)상품정보 => 상품이름 + 사이즈 + 갯수 

//1)배송주소 합치기
$addr_end = $addr.$addr_detail."(".(string)$post_num.")";

/* 주문 중복 검사 
    -결제 페이지를 실수로 두번 들어갔을 때 이전에 결제했던 주문정보가 중복되게 들어가는
    것을 방지하는 검사
*/
$sql_select = "SELECT * FROM OrderList WHERE redundancy_chk = '$redundancy_chk' "; 
$result_select = mysqli_query($con, $sql_select);
//주문번호와 같은 번호가 있는 행의 갯수를 구한다.
$row_num = mysqli_num_rows($result_select);

//같은 주문번호가 없는 경우(상품 중복 X)
if($row_num == 0 ){
    //주문내역에 데이터 저장
/* 테이블에 입력하는 데이터
1)구매자 id $id
2)결제금액 $item_cost
3)주문 번호 $order_Num
4)주문 날짜 NOW()
5)배송 주소 $addr_end
6)구매자 이름 $buyer_Name
7)구매자 핸드폰 번호 $phone_Num
8)배송 요청사항 request

상품에 따라 변하는 데이터 
9)상품 이름 $item_name
10)상품 수량 
11) 상품 이미지 url
*/

/* 상품정보 배열의 갯수만큼 반복해서 테이블에 추가  */
for ($i = 0; $i < count($item_infor_ar); $i++){

    $order_Num = sprintf('%08d', rand(00000000, 99999999));
    /* 배열에서 상품정보를 갖고온다
    -상품 수량
    -상품 가격 
    -상품 정보(이름 + 사이즈)
    -상품 이미지
    */
   $item_count = $item_infor_ar[$i][0];
   $item_cost = $item_infor_ar[$i][1]; //상품 하나의 결제금액
   //공백제거 (trim)
   $item_name = trim($item_infor_ar[$i][3]);
   $item_img = $item_infor_ar[$i][4];
   //장바구니에 상품을 삭제할 때 필요한 장바구니 상품번호
   $basket_num = $item_infor_ar[$i][5];
   settype($basket_num, 'integer');
    $sql_insert = "INSERT INTO OrderList 
    (id, item_cost, 
    redundancy_chk, created, 
    addr, buyer_name, 
    phone_num, request, 
    item_name, item_count, 
    img_url, order_num) 
    VALUES (
    $id, $item_cost,
   '$redundancy_chk', NOW(),
   '$addr_end', '$buyer_Name',
   '$phone_Num', '$request_detail',
   '$item_name', $item_count,
   '$item_img', '$order_Num'
    )";
   $result_insert = mysqli_query($con, $sql_insert);
  
   /* 1-1)상품 정보 주문내역 테이블에 저장 성공
      -장바구니에 저장한 상품 정보 삭제
   */
    if($result_insert === true){
    /* 2.주문한 상품 장바구니에서 삭제 
        -나의 장바구니 상품만 삭제한다.
        -주문된 상품만 장바구니에서 삭제한다.
    */
        $sql_delete="DELETE from Basket WHERE basket_num = $basket_num";
        $result_delete = mysqli_query($con, $sql_delete);
        if($result_delete === false) {
        //2-1)장바구니 상품 삭제 실패
            echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
        }
    }
}

    //1-2)상품 정보 주문내역 테이블에 저장 실패 
    if($result_insert === false){ 
            echo '데이터 형식 및, 오타(저장 실패) ';
        }
}else{//1-3)상품 정보 중복 저장(실패)
    echo '주문번호 중복(저장 실패 )';
}


/* 배열의 갯수만큼 행을 추가해주는 함수 */
function get_row($item_infor_ar){
    for ($i = 0; $i < count($item_infor_ar); $i++){
        //2)배열에 저장된 상품정보 합치기(상품이름 + 사이즈 + 수량 )
        $item_name = $item_infor_ar[$i][3].$item_infor_ar[$i][0]."개";
        ?>
        <tr class="">
                    <td class="size">
                        <h1><?=$item_name?></h1>
                    </td>
                </tr>
    <?php
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- j쿼리 적용 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- (공용)많이 쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="../css_file/purchase.css?ver=1" />
    <!-- 부트스트랩 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <title>결제완료 페이지(장바구니)</title>
</head>
<body>
<div class="div_main">
        <h1>결제가 완료되었습니다</h1>
        <h1>주문 및 배송 확인은 마이페이지에서 확인 가능합니다</h1>

        <!-- 전체 테이블!! -->
        <table class="table table-bordered border-primary">
            <!--1. 테이블 헤드 -->
           
            <!--2. 테이블 바디  -->
            <tbody class="tbody">
                <!-- 2-1)테이블 행 -->

                <!-- 1)상품정보(상품이름 + 사이즈 + 수량) -->
                <tr class="">
                <!-- rowspan = 상품의 갯수 -->
                    <td style="width: 350px;" rowspan="<?=$item_size?>" class="">
                        <h1 id="item_name">상품정보</h1>
                    </td>
                    <td class="size">
               
                    </td>
                    
                </tr>
                    <!-- 상품이름 + 사이즈 + 수량 -->
                    <!-- 상품의 갯수에 따라서 테이블 행을 생성해고 상품의 이름을 입력해주는 메서드 -->
                <?= get_row($item_infor_ar); ?>
                <!--3)구매자 이름 -->
                <tr class="">
                    <td style="width: 350px;"  class="">
                        <h1 id="item_name">구매자</h1>
                    </td>
                    <td class="size">
                        <h1><?=$buyer_Name?></h1>
                    </td>
                </tr>

                <!-- 4)전화번호 -->
                 <tr class="">
                    <td style="width: 350px;"  class="">
                        <h1 id="item_name">전화번호</h1>
                    </td>
                    <td class="size">
                        <h1><?=$phone_Num?></h1>
                    </td>
                </tr>

                <!-- 5)배송지: 주소 -->
                <tr 
                    class="" >
                    <td  style="vertical-align:middle; " rowspan="3" >
                        <h1 id="item_name" >배송지</h1>
                    </td>

                    <td  class="">
                        <h1 id="item_name"><?=$addr?></h1>
                    </td>
                </tr>
                <!-- 5-2)배송지: 상세주소 -->
                <tr class="" >
                    <td  class="">
                        <h1 id="item_name"><?=$addr_detail?></h1>
                    </td>
                </tr>
                <!-- 5-3)배송지: 우편번호 -->
                <tr class="" >
                    <td  class="">
                        <h1 id="item_name"><?=$post_num."(우편번호)"?></h1>
                    </td>
                </tr>
                
                <!-- 6)배송방법 -->
                <tr class="">
                    <td class="">
                        <h1 id="item_name">배송방법</h1>
                    </td>
                    <td class="size">
                        <h1>택배</h1>
                    </td>
                </tr>
                <!-- 7)결제금액 -->
                <tr class="">
                    <td class="">
                        <h1 id="item_name">결제금액</h1>
                    </td>
                    <td class="size">
                        <h1><?=$item_cost_all?>원</h1>
                    </td>
                </tr>
                <!-- 8)요청사항 -->
                <tr class="">
                    <td class="">
                        <h1 id="item_name">요청사항</h1>
                    </td>
                    <td class="size">
                        <h1><?=$request_detail?></h1>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-success" onclick= 'move_page_link("Main.php")'>
        <h1>메인 화면</h1>    
        </button>
        <!-- 사용자에 따라서 이동하는 페이지를 분리한다
        1)관리자 => 관리자용 주문내역
        2)고객   => 고객용 주문내역 
         -->
         <?php
         //관리자용 
         if($id == 1){
                    ?>
            <button type="button" class="btn btn-primary" onclick= 'move_page_link("OrderList.php")'>
                <h1>주문내역 확인</h1>    
                </button>
                <?php
         }else{
        // 고객용
            ?>
            <button type="button" class="btn btn-primary" onclick= 'move_page_link("OrderList_Cus.php")'>
                <h1>주문내역 확인</h1>    
                </button>
                <?php
         }
         ?>
    
    </div>
</body>
</html>