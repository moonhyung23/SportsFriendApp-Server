<!-- 결제완료 페이지 -->

<?php 
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
       
//2.세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];


/* post로 가져온 결제에 필요한 상품과 구매자 정보 */
$item_name = $_POST['item_name']; //1)아이템이름
$item_count = $_POST['item_count']; //3)상품수량
$item_cost = $_POST['item_cost']; //4)결제금액
$order_Num = $_POST['order_Num']; //5)주문번호(String)
//6)구매자 전화번호
//String으로 형변환  0이 빠져서 나와서 "0"추가
$phone_Num =  "0".(string)$_POST['phone_Num']; 
$buyer_Name = $_POST['buyer_Name']; //7)구매자 이름
$addr = $_POST['addr']; //8)주소
$addr_detail = $_POST['addr_detail']; //9)상세주소
$post_num = $_POST['post_num']; //10)우편번호
$request_detail = $_POST['request_detail']; //11)배송 요구사항
$img_url = $_POST['img_url'];//12)상품 이미지 url




/* 합쳐지는 데이터  */
//1)상품이름 => 상품이름 + 사이즈


//2)배송주소 => 앞주소 + 상세주소 + 우편번호 (주소 합치기)
$addr_end = $addr.$addr_detail."(".(string)$post_num.")";


/* 주문내역에 저장하려는 주문번호와 같은 번호가 
있는지 확인한다.(중복검사) 
이유: 페이지 이전 버튼을 눌렀을 때 이전에 저장되었던 주문정보가 중복되어 저장되는 것을 방지 
*/
$sql_select = "SELECT * FROM OrderList WHERE order_num = '$order_Num' "; 
$result_select = mysqli_query($con, $sql_select);
//주문번호와 같은 번호가 있는 행의 갯수를 구한다.
$row_num = mysqli_num_rows($result_select);
/* 테이블에 입력하는 데이터
1)구매자id
2)상품 이름
3)결제금액 
4)주문 번호
5)상품 수량 
6)주문 날짜
7)배송 주소 
8)구매자 이름
9)구매자 핸드폰 번호 
10) 상품 이미지 url */

// 주문번호와 같은 상품이 없을 때만 주문내역에 추가.
    if($row_num == 0 ){
    // 주문내역 Table에 저장
    $sql_insert = "INSERT INTO OrderList 
    (id, item_name, 
    item_cost, order_num, 
    item_count, created, 
    addr, buyer_name, 
    phone_num, img_url
    ,request
    ) 
    VALUES (
    $id, '$item_name',
    $item_cost, '$order_Num',
    $item_count, NOW(),
    '$addr_end', '$buyer_Name',
    '$phone_Num', '$img_url',
    '$request_detail'
    )";
    $result_insert = mysqli_query($con, $sql_insert);
    error_log(mysqli_error($con));
    if($result_insert === false){
    echo "mysqli_error:".mysqli_error($con)."<br>";
    echo '데이터 형식, 오타(저장 실패) ';
    }
      
}else{ 
    echo '주문번호 중복(저장 실패 )';
 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- css -->
    <link rel="stylesheet" href="../css_file/purchase_com.css?ver=1" />
    <!-- 부트스트랩 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
     <!-- (공용)많이 쓰는 함수 js파일 -->
     <script src="../js_file/Global.js"></script>
    <title>결제완료 페이지</title>

   
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

                <!-- 1)상품정보(상품이름 + 사이즈) -->
                <tr class="">
                    <td style="width: 350px;"  class="">
                        <h1 id="item_name">상품이름</h1>
                    </td>
                    <td class="size">
                        <!-- 상품이름 + 사이즈 -->
                        <h1><?=$item_name?></h1>
                    </td>
                </tr>
                 <!--2)상품수량 -->
                <tr class="">
                    <td style="width: 350px;"  class="">
                        <h1 id="item_name">수량</h1>
                    </td>
                    <td class="size">
                        <h1><?=$item_count?></h1>
                    </td>
                </tr>
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
                <!-- 7)주문번호 -->
                <tr class="">
                    <td class="">
                        <h1 id="item_name">주문번호</h1>
                    </td>
                    <td class="size">
                        <h1><?=$order_Num?></h1>
                    </td>
                </tr>
             
                <!-- 8)결제금액 -->
                <tr class="">
                    <td class="">
                        <h1 id="item_name">결제금액</h1>
                    </td>
                    <td class="size">
                        <h1><?=$item_cost?></h1>
                    </td>
                </tr>
                <!-- 9)요청사항 -->
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
        <button type="button" class="btn btn-primary" onclick= 'move_page_link("OrderList_Cus.php")'>
        <h1>주문내역 확인</h1>    
        </button>
    </div>
</body>

</html>