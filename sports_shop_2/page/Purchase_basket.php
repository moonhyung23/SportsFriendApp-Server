<!-- 장바구니 상품 전용 결제 완료 페이지 -->
<?php 
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];
//상품의 배송비를 제외한 최종 가격
$item_cost_end = 0;

/***  $item_infor_json json 배열 구조 설명 ***
1)부모의 배열(상품)

2)자식의  배열(상품 정보) 인덱스 
-0번: 상품수량 
-1번: 상품가격
-2번: 상품번호 */

//상품 정보(json)배열 post로 받아오기
$item_infor_json = $_POST['item_infor_json'];
//상품정보(json)파일을 => 배열로 변환
$item_infor_ar = json_decode($item_infor_json);


// DB에서 나의 사용자 정보 조회하기
 $sql_select = "SELECT * FROM User WHERE id = $id "; 
 $result_select = mysqli_query($con, $sql_select);
 $row_user = mysqli_fetch_assoc($result_select);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- (공용)많이 쓰는 함수 js파일 -->
   <script src="../js_file/Global.js"></script>
   <!-- 결제관련 js 파일 -->
   <script src="../js_file/purchase_basket.js"></script>
    <!-- 결제 api -->
     <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!-- 아임포트 -->
    <script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
    <!-- daum 주소검색 api -->
      <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <!-- 부트스트랩 적용 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <!--결제 테이블  css파일 -->
      <link type="text/css" rel="stylesheet" href="../css_file/purchase.css" />


    <title>상품 결제(장바구니 전용)</title>
</head>
<body>
<!-- php 변수를 자바스크립트로 갖고오기.
  -가져오는 변수: 상품정보 json 배열
 -->
<script> set_data(<?=$item_infor_json?>); </script>

<!-- 장바구니에 추가된 상품정보 json 배열
    -자바스크립트에 전송용
    -Post로 결제완료 페이지에 보내기 위함.
-->
<input type="hidden" id="item_infor_json" value="<?=$item_infor_json?>" >

<!-- 결제모듈 세팅 코드 -->
 <script>     
 var IMP = window.IMP; // 생략가능
 IMP.init("imp44887117"); // 'iamport' 대신 부여받은 "가맹점 식별코드"를 사용
</script>


<div>
<a href="#" onclick="history.back()">
<h1 style="font-size: 50px; margin:30px;">뒤로가기</h1>
</a>
</div>
    <div class="div_main">
        <!-- 전체 테이블!! -->
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
            for($i = 0; $i < count($item_infor_ar); $i++){ 
            /*자식의  배열(상품 정보) 인덱스 
            -0번: 상품수량 
            -1번: 상품가격
            -2번: 상품번호 
            -3번: 상품이름
            */

            /* 배열안에 배열의 값(상품정보)을 변수에 저장 */
            $item_count = $item_infor_ar[$i][0]; //1.상품 하나의 전체 수량
            $item_cost = $item_infor_ar[$i][1]; //2.상품 하나의 전체 가격
            $item_number = $item_infor_ar[$i][2]; //3.상품번호
            $item_name = $item_infor_ar[$i][3]; //4.상품이름(이름 + 사이즈)

            settype($item_number, 'integer');
            settype($item_cost, 'integer');
            //상품 최종 결제 금액
            $item_cost_end += $item_cost;
            
            
            //상품의 번호와 같은 행을 조회한다
            $sql_select = "SELECT * FROM Add_Item WHERE item_num_A = $item_number "; 
            $result_select = mysqli_query($con, $sql_select);
            $row = mysqli_fetch_assoc($result_select);
            ?>
                <!-- 테이블의 행 -->
                <tr class="tr_p_item_row">
                    <!-- 1.상품정보 -->
                    <td class="td_item_infor">
                        <!-- 1-1)상품이미지 -->
                        <img id="img_url" src="../image_files/<?=$row['img_url']?>" alt="" />
                        <!-- 1-2)상품이름 + 사이즈 -->
                        <h1 id="item_name"><?=$item_name?></h1>
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
        <?php
            }
            ?>
               
            </tbody>
        </table>

        <div class="div_purchase">
           <h1>배송비: 2500원</h1> 
          <!-- 결제금액((상품가격 * 수량) + 배송비) -->
          <input type="hidden" id="item_cost" value="<?= $item_cost_end + 2500?>">
          <!-- 결제금액 텍스트  -->
          <h1>최종 결제 금액: <?= $item_cost_end + 2500?>원</h1>
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
               style="transform:scale(3); margin-right:20px;" 
               type="radio" class="form-check-input" name="optradio">기본 배송지
               </div>
                    </li>

                    <!-- 2)신규 배송지 폼 -->
                    <li class="_newDeliveryInfo">
                    <div class="form-check-inline">
               <input 
               style="transform:scale(3); margin-right:20px;" 
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

        <!-- 1. 신규 배송지 입력 폼 
                - display:none이 되어 있으면 태그가 숨겨짐.
            -->
        <ul class="new_delivery_addr">
            <!-- 1.구매자 div -->
            <li>
                <div class="new_delevery_receiver">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-lg">구매자</span
              >
              <!-- 구매자 이름  -->
              <input
                id="new_receiver_name"
                type="text"
                class="form-control"
                aria-label="Large"
                aria-describedby="inputGroup-sizing-sm"
              />
            </div>
          </div>
        </li>
        <li>
          <!--2.연락처 div  -->
          <div class="div_phone_number">
            <strong
              class="req short"
              title="필수입력"
              style="margin: 10px; font-size: 40px"
              >연락처1</strong
            >
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
        class="btn btn-primary btn-lg"
      >
        결제하기
      </button>
    </div>
  </body>
</html>