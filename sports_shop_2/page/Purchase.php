<!-- 상품 바로구매 -->
<!-- 결제 페이지 (주소 및 사용자 정보 입력) -->

<?php 
//db파일 추가
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//2.세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];
/* 1.상품정보에서 즉시 구매한 경우 
   상품정보 
 */
    //1)상품이름
    $item_name = $_POST['item_name'];
    //2)상품가격
    $item_cost = $_POST['item_cost'];
    //3)상품 수량
    $item_count = $_POST['item_count'];
    //4)상품 사이즈
    $item_size = $_POST['item_size'];
    //5)상품 최종가격(가격 * 수량)
    $item_cost_end = $_POST['item_cost_end']; 
    //6)상품 이미지url
    $item_img_url = $_POST['img_url']; 
    //7)상품 번호
    $item_num = $_POST['item_num'];

  // DB에서 나의 사용자 정보 조회하기
    $sql_select = "SELECT * FROM User WHERE id = $id "; 
    $result_select = mysqli_query($con, $sql_select);
    $row_user = mysqli_fetch_assoc($result_select)

 ?>

<script>
 
    function purchase() {
      /* 입력한 구매정보  */
        //1)구매자이름
        let buyerName = document.querySelector("#new_receiver_name").value;
        //2)구매자 전화번호
        let phone_Num_low = document.querySelector("#phone_Low").value;//앞자리 
        let phone_Num_middle = document.querySelector("#phone_Middle").value;//중간
        let phone_Num_high = document.querySelector("#phone_High").value;//뒷자리
        // parseInt를 하면 앞에 0이 사라진다.
        let buyerPhone_Num_end = parseInt(phone_Num_low + phone_Num_middle + phone_Num_high);
        //3)구매자 주소
        let addr = document.querySelector("#member_addr").value; //앞주소
        let address_detail = document.querySelector("#address_detail").value; //상세주소
        let buyer_addr = addr + address_detail;
        //4)구매자 우편번호
        let buyer_post_Num = document.querySelector("#member_post").value;
        //5)상품이름 h1태그
        let item_name = document.querySelector("#item_name").innerHTML;
        //6)결제금액(최종 결제 금액) h1태그
        let item_cost = parseInt(document.querySelector("#item_cost").value);
        //7)요청사항
        let request_detail = document.querySelector("#request_detali").value;
        //8)구매자 이메일
        let buyer_eamil = document.querySelector("#email").value;
        //9)주문번호
        let order_Num = generateRandomCode(8);
        //10)상품 사이즈
        let item_size = document.querySelector("#item_size").value;
        //11)상품 수량
        let item_count = document.querySelector("#item_count").innerHTML;
        //12)상품 이미지 
        let img_url = document.querySelector("#img_url").src;
        //12-1)상품의 이름만 갖고온다.
        let image_name = img_url.replace('https://moonhyung23.shop/sports_shop_2/image_files/', '');

        //1)구매자 이름 공백체크
            if((buyerName.length) === 0){
                alert('구매자를 입력하세요.');
                return;
            }

        //2)전화번호 공백체크    
            //전화번호가 10자리 미만일 때
            //숫자로 형변환되면서 11자리가 10자리로 변함.
            if(buyerPhone_Num_end.toString().length  < 10){
                alert('전화번호를 입력해주세요.');
                return;
            }
        //3) 주소 공백 체크    
             if(addr.length === 0){
                alert('주소를 입력 해주세요');
                return;
            } 

        //3)상세 주소 공백 체크    
             if(address_detail.length === 0){
                alert('상세 주소를 입력 해주세요');
                return;
            } 


        IMP.request_pay({
                pg: "inicis", // version 1.1.0부터 지원.
                pay_method: "card", //결제 방식
                merchant_uid: "merchant_" + new Date().getTime(), //결제 날짜
                name: item_name, //상품이름
                amount: item_cost, //결제 금액(수량 * 가격)
                buyer_email: buyer_eamil, //구매자  이메일
                buyer_name:  buyerName, //구매자
                buyer_tel: buyerPhone_Num_end, //구매자 전화번호
                buyer_addr: buyer_addr, //구매자 주소
                buyer_postcode: buyer_post_Num, //구매자 우편번호
                m_redirect_url: "https://www.yourdomain.com/payments/complete", 
            },
            function(rsp) {
                /* 결제 성공 후 출력될 다이얼로그  */
                if (rsp.success) {
                    var msg = "결제가 완료되었습니다.";
                    msg += "고유ID : " + rsp.imp_uid;
                    msg += "상점 거래ID : " + rsp.merchant_uid;
                    msg += "결제 금액 : " + rsp.paid_amount;
                    msg += "카드 승인번호 : " + rsp.apply_num;
                      rsp.a
                    //POST 방식으로 데이터를 담고 페이지 이동
                    post_to_url("Purchase_Complete.php", 
                                {'item_name': item_name, //1)상품이름
                                 'save_check': 2, //2)저장 확인 번호: db에 딱 한번만 저장하기 위해서 생성  2:저장 가능
                                 'phone_Num': buyerPhone_Num_end, //3)핸드폰 번호
                                 'addr': addr, //4)주소(앞)
                                 'addr_detail': address_detail, //5)상세주소
                                 'post_num': buyer_post_Num, //6)우편번호
                                 'item_cost': item_cost, //7)결제금액
                                 'request_detail': request_detail, //8)배송메세지
                                 'order_Num': order_Num, //9)주문번호
                                 'buyer_Name': buyerName, //10)구매자
                                 'item_count': item_count, //11)상품 갯수
                                 'img_url': image_name //12)이미지 url
                                })
                } else {
                    /*결제 실패 후 출력될 다이얼로그  */
                    var msg = "결제에 실패하였습니다.";
                    msg += "에러내용 : " + rsp.error_msg;
                }
                alert(msg);
            }
        );
    }

//주문번호를 만드는 메서드
function generateRandomCode(n) {
    let str = ''
    for (let i = 0; i < n; i++) {
      str += Math.floor(Math.random() * 10)
    }
    return str
  }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>결제화면</title>
    <!-- css파일 -->
    <link type="text/css" rel="stylesheet" href="../css_file/purchase.css?ver=1" />
    <!-- daum 주소검색 api -->
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <!-- (공용)많이 쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>
    <!-- 결제 api -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!-- 아임포트 -->
    <script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
    <!-- 부트스트랩5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

<!-- 결제모듈 -->
    <script>     
    var IMP = window.IMP; // 생략가능
    IMP.init("imp44887117"); // 'iamport' 대신 부여받은 "가맹점 식별코드"를 사용
    </script>

    <div>
    <a href="#" onclick="history.back()">
    <h1 style="font-size: 50px; margin:30px;">뒤로가기</h1>
    </a>
    </div>
  <!-- main -->
  <div class="div_main">
    <div class="div_main">
    <!-- 구매한 상품목록 테이블 -->
        <table class="table table-bordered border-primary">
            <!-- 테이블 헤드 -->
            <thead class="th_table">
                <tr>
                    <th class="th_item_infor">
                        <!-- 1.품목 -->
                        <h1>상품정보</h1>
                    </th>
                    
                    <th style="width: 220px;">
                        <!-- 2.수량 -->
                        <h1>수량(개)</h1>
                    </th>
                   
                    <th style="width: 350px;">
                        <!-- 3.결제금액 -->
                        <h1>합계 금액(원)</h1>
                    </th>
                </tr>
            </thead>
            <!-- 테이블 바디 -->
            <tbody>
            <?php 
            //상품의 번호와 같은 행을 조회한다
            $sql_select = "SELECT * FROM Add_Item WHERE item_num_A = $item_num "; 
            $result_select = mysqli_query($con, $sql_select);
            $row = mysqli_fetch_assoc($result_select);?>
                <!-- 테이블의 행 -->
                <tr class="tr_p_item_row">
                    <!-- 1.상품정보 -->
                    <td class="td_item_infor">
                        <!-- 1-1)상품이미지 -->
                        <img id="img_url" src="../image_files/<?=$row['img_url']?>" alt="" />
                        <!-- 1-2)상품이름 + 사이즈 -->
                        <h1 id="item_name"><?=$item_name."(".$item_size.")"?></h1>
                        <!-- 상품 사이즈(자바스크립트로 보내기 위해서) -->
                        <input id="item_size" type="hidden" value="<?=$item_size?>">
                    </td>
                    <!--2.수량 -->
                    <td 
                        class="count">
                        <h1 id="item_count"><?=$item_count?></h1>
                    </td>
                    <!-- 3.상품가격 (상품가격 * 수량)-->
                    <td class="cost">
                        <h1 ><?=$item_cost?></h1>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="div_purchase">
           <h1>배송비: 2500원</h1> 
          <!-- 결제금액((상품가격 * 수량) + 배송비) -->
          <input type="hidden" id="item_cost" value="<?= $item_cost + 2500?>">
          <!-- 결제금액 텍스트  -->
          <h1>최종 결제 금액: <?= $item_cost + 2500?>원</h1>
        </div>

        <!-- 배송지 정보 폼 -->
        <div class="div_deliver_infor">
            <h1>배송지 정보</h1>

            <!-- 배송지 선택 폼 -->
            <div class="div_delever_option">
                <ul class="radio_inline_list delivery">
                    <!-- 1)배송지 선택 텍스트 -->
                    <li >
                        <strong  class="req" title="필수입력">배송지 선택</strong>
                        <!-- 1.기본 배송지 -->
                    </li>
                    <li class="_baseDeliveryInfo">
                    <div class="form-check-inline">
               <input 
               style="transform:scale(2); margin-right:30px;" 
               type="radio" class="form-check-input" name="optradio">기본 배송지
               </div>
                    </li>

                    <!-- 2)신규 배송지 폼 -->
                    <li class="_newDeliveryInfo">
                    <div class="form-check-inline">
               <input 
               style="transform:scale(2); margin-right:30px;" 
               type="radio" class="form-check-input" checked name="optradio">신규 배송지
               </div>
                    </li>

                    <!-- 배송지 목록 확인 버튼 -->
                    <button style="font-size: 50px" class="btn btn-primary">
              배송지 목록
            </button>
                </ul>
            </div>
        </div>
          <!-- 1. 신규 배송지 입력 폼  -->
            <ul class="new_delivery_addr">
            <!-- 1.구매자 div -->
            <li>
            <div class="input-group-prepend">
             <input type="text" class="form-control" style="width: 200px; text-align:center;" disabled value="구매자">   
            <!-- 구매자 이름  입력-->
              <input
                id="new_receiver_name"
                type="text"
                class="form-control"
                aria-label="Large"
                aria-describedby="inputGroup-sizing-sm"
                style="width: 200px; text-align:center;"
              />
            </div>
        </li>
        <li>
          <!--2.연락처 div  -->
          <div class="div_phone_number">
            <strong
              class="req short"
              title="필수입력"
              style="margin: 10px; font-size: 40px">연락처1</strong>
            <!-- 전화번호 첫자리 -->
            <select id="phone_Low">
              <option value="010" selected="">010</option>
              <option value="011">011</option>
              <option value="016">016</option>
              <option value="017">017</option>
              <option value="018">018</option>
              <option value="019">019</option>
              <option value="02">02</option>
              <option value="031">031</option>
              <option value="032">032</option>
              <option value="033">033</option>
              <option value="041">041</option>
              <option value="042">042</option>
              <option value="043">043</option>
              <option value="044">044</option>
              <option value="051">051</option>
              <option value="052">052</option>
              <option value="053">053</option>
              <option value="054">054</option>
              <option value="055">055</option>
              <option value="061">061</option>
              <option value="062">062</option>
              <option value="063">063</option>
              <option value="064">064</option>
              <option value="070">070</option>
              <option value="080">080</option>
            </select>
            -
            <input id="phone_Middle" type="text" title="핸드폰번호 중간" maxlength="4" /> -
            <input id="phone_High" type="text" title="핸드폰번호 뒷자리" maxlength="4" />
          </div>
        </li>

        <!-- 3.배송지(주소)div -->
        <div class="form-group">
          <label style="font-size: 60px; margin-top: 30px" for="inputtelNO"
            >배송지 주소</label
          >

          <!--4. 배송지 주소 
            Global.js파일에서 주소검색 함수를 가져옴
        -->

          <!-- 4-1)우편번호 -->
          <input
            readonly
            onclick='findAddr("member_post", "member_addr")'
            id="member_post"
            required
            name="post_num"
            style="font-size: 40px; width: 1000px"
            type="tel"
            class="form-control"
            id="inputtelNO"
            placeholder="우편번호 찾기(클릭)"
          />
          <!-- 4-2)배송지 주소 -->
          <input
            readonly
            onclick="findAddr('member_post', 'member_addr')"
            id="member_addr"
            required
            style="font-size: 40px; width: 1000px"
            type="tel"
            class="form-control"
            id="address"
            placeholder="주소를 입력해 주세요"
          />
          <!-- 4-3)상세주소 -->
          <input
            required
            style="font-size: 40px; width: 1000px"
            type="tel"
            class="form-control"
            id="address_detail"
            placeholder="상세주소"
          />
          <!-- 4-4)요청사항 -->
          <input
            required
            style="font-size: 40px; width: 1500px"
            type="tel"
            class="form-control"
            id="request_detali"
            placeholder="요청사항을 입력해주세요"
          />
          <!-- 5)고객 이메일 -->
          <input
            type="hidden"
            class="form-control"
            id="email"
            value="<?=$row_user['email']?>"
          />
        </div>
      </ul>
       <!-- 5.결제하기 버튼 -->
       <button
        type="button"
        onclick="purchase();"
        style="
          width: 300px;
          margin-top: 30px;
          height: 100px;
          font-size: 40px;
          font-weight: bold;
        "
        class="btn btn-primary btn-lg">
        결제하기
      </button>
      </div>
</body>

</html>