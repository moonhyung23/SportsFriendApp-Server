<?php
    /* Main 홈페이지에서 클릭한 아이템 정보를 조회하는 php 파일 */

    //db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
    //2.세션 초기화
    session_start();
    settype($_SESSION['id'], 'integer');
    $id = $_SESSION['id'];
    //클릭한 상품 정보 배열
    $item_ar = array();
    //클릭한 상품의 번호
    $item_num = $_GET['item_num'];
    settype($item_num, 'integer');
    //클릭한 게시물의 id와 맞는 데이터를 테이블에서 불러온다.
    $sql_item = "SELECT * FROM Add_Item WHERE item_num_A = $item_num";
    $result = mysqli_query($con,  $sql_item);
    //클릭한 id가 있는 행을 불러온다.
    $row = (mysqli_fetch_assoc($result));
    //상품 정보 배열에 저장
    $item_ar = array(
    'item_name'=> $row['item_name_A'],//상품이름
    'item_content'=> $row['content'],//상품 설명
    'item_category'=> $row['category'],//상품 카테고리
    'category_detail'=> $row['category_detail'],//상품 카테고리
    'item_cost'=> $row['cost'],//상품 가격
    'img_url'=> $row['img_url'],//상품 이미지
    'item_num_A'=> $row['item_num_A'],//상품 번호
    'item_count'=> $row['count']//상품 수량
    ); 

    $category = $item_ar['item_category']; //상품 카테고리
    $category_detail = $item_ar['category_detail']; //상세 카테고리
    
    //사용자 정보를 조회한다.
    $sql_select = "SELECT * FROM User WHERE id = $id"; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);

    /* 사용자 로그인 메뉴 ui */
    $login = Login_Menu($_SESSION['id'], $row['name'] );  


    /* 보호대 사이즈 형식 (카테고리 디테일에 따라서 바뀜.)
        -무릎 보호대
        -발목 보호대
        -어깨보호대
        -팔꿈치보호대
    */
    function guard_form ($category_detail){
        //카테고리 디테일이 없는 경우 종료
        if($category_detail == "" && $category_detail == null){
            exit();
        }

            if($category_detail == "무릎보호대"){
                ?> 
                <img style="width: 1300px;" src="../image_files/knee_size_form.jpg" alt="">
                <?php
            }
            else if($category_detail == "발목보호대"){
                ?> 
                <img style="width: 1300px;" src="../image_files/ankle_size_from.jpg" alt="">
                <?php
            }
            else if($category_detail == "어깨보호대"){
                ?> 
                <img style="width: 1300px;" src="../image_files/shoulder_size_form.jpg" alt="">
                <?php
            }
            else if($category_detail == "팔꿈치보호대"){
                ?> 
                <img style="width: 1300px;" src="../image_files/arm_guard_size_form.jpg" alt="">
                <?php
            }
    }

    /* 클릭한 상품의 번호를 쿠키에 저장하는 함수 
    -최근 클릭한 상품의 정보를 확인하기 위해서
    */
    function save_item_num_cookie($id, $item_ar){
        $check_redudancy = 0;
        /* 쿠키 정보를 불러온다
        -최근 검색한 상품 번호
        -KEY: 사용자의 세션에 저장된 ID => 사용자마다 클릭한 최근 상품정보를 다르게 하기 위해서
        */

        //최근 검색한 상품 번호(배열)가 있는 경우에만 
        if(isset($_COOKIE[(string)$id])){
        //최근 검색한 상품 번호 JSON 배열을 쿠키에서 가져온다
        //json배열  => 배열 
        $item_num_ar = json_decode($_COOKIE[(string)$id]); 
        }
        //없는 경우 새 배열 생성
        else{
        $item_num_ar = array();
    }

    /* 상품 번호 중복 되는 경우
        -이전에 등록했던 상품을 삭제하고 
        -등록한 상품을 맨 앞의 인덱스에 추가한다.
    */ 
    for($i = 0; $i < count($item_num_ar); $i++){
        if($item_num_ar[$i] == $item_ar['item_num_A']){
            /* 상품 번호 중복 체크 번호
                -1번 =>  상품번호 맨 뒤 인덱스에 추가 X
            */ 
            $check_redudancy = 1;
            /* 추가하려는 상품 번호가 이미 배열에 존재하는 경우 */
                // -1)중복되는 값(상품번호)를 배열에서 삭제
                unset($item_num_ar[$i]); 
                // -2)추가하려는 값(상품번호)을 가장 맨 앞에 인덱스에 추가
                array_unshift($item_num_ar, $item_ar['item_num_A']);                
        }
    }

    //중복아닌 경우 맨 뒤 상품번호를 삭제하지 않고 맨앞에 추가
    if($check_redudancy == 0 ){
        array_unshift($item_num_ar, $item_ar['item_num_A']); 
    }

    //1)상품번호가 7개 이상 저장된 경우 
    if(count($item_num_ar) >= 7){
    //처음 저장했던 상품번호를 지운다(마지막 인덱스 삭제)
    array_pop($item_num_ar);
    }
    
    //쿠키에 상품 번호 배열을 Json으로 변환 후 저장한다.
    setcookie((string)$id, json_encode($item_num_ar), time() + 3600, "/");
    }
    
    //상품 번호를 쿠키에 추가한다.
    save_item_num_cookie($id, $item_ar);
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css 파일 -->
    <link rel="stylesheet" href="../css_file/item.css">
    <!-- 부트스트랩 적용 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- js파일 적용 -->
    <script src="../js_file/item_infor.js"></script><!-- 상품 수량 및 가격 표시 -->
    <!-- 많이 쓰는 js 함수 모음  -->
    <script src="../js_file/Global.js"></script>
    <!-- Font_awesome -->
    <script src="https://kit.fontawesome.com/2d323a629b.js" crossorigin="anonymous"></script>
    <title>상품 정보</title>
</head>
    <body>
    <header>
        <nav class="header">
            <!-- myMenu -->
            <?=$login;?>
            <!-- 웹 로고 이미지 -->
            <div class="logo_image">
                <!-- 클릭 시 메인페이지로 이동 -->
                <a href="../page/Main.php">
                    <img src="../web_image/weblogo.jpg" alt="none_image" width="800px" height="600px" />
                </a>
            </div>
            <!-- 카테고리 메뉴 -->
            <?= Category_menu() ?>
        </nav>
    </header>

    <main>
        <!-- 상품 정보 목록 -->
        <section class="item_infor">
            <!--1) 상품 이미지 -->
            <img id="item_img" src="../image_files/<?=$item_ar['img_url']?>" alt=""
                style="width:1500px; height:1000px; margin-top: 40px;">
            <article>
                <ul>
                    <!--2) 상품 이름 -->
                    <li>
                        <h1 id="item_name" style="margin: 10px; font-size:50px;"><?=$item_ar['item_name']?></h1>
                    </li>
                    <li>
                        <input type="hidden" id="item_cost" value="<?=$item_ar['item_cost']?>">
                        <!--3) 상품 가격 -->
                        <h1 style="margin:10px; font-size:50px;"> <?=$item_ar['item_cost']?>원 </h1>
                    </li>
                    <!-- 구분선 -->
                    <hr size="15px" width="300%" noshade>
                    <li>
                        <h1 style="margin: 15px;  font-size: 40px;"> 배송방법: 택배</h1>
                    </li>
                    <li>
                        <h1 style="margin: 15px;  font-size: 40px;"> 배송비: 2500원</h1>
                    </li>

                    <!-- 상품 카테고리에 따라서 선택할 수 있는 사이즈 종류를 변경
                        1)농구화 
                        2)농구공
                        3)보호대
                     -->
                    <?php
                    // 1) 농구화
                     if($item_ar['item_category'] == "농구화"){ ?>
                    <li>
                        <h1 style="margin-top:100px; margin-left:15px;  font-size: 30px;"> 사이즈</h1>
                    </li>

                    <form class="form-inline">
                        <select id="item_size" style="height: 80px; font-size:30px;" class="custom-select my-1 mr-sm-2"
                            id="inlineFormCustomSelectPref">
                            <option selected>
                                <h1>사이즈선택</h1>
                            </option>
                            <option value="240">
                                <h1>240</h1>
                            </option>
                            <option value="245">
                                <h1>245</h1>
                            </option>
                            <option value="250">
                                <h1>250</h1>
                            </option>
                            <option value="255">
                                <h1>255</h1>
                            </option>
                            <option value="260">
                                <h1>260</h1>
                            </option>
                            <option value="265">
                                <h1>265</h1>
                            </option>
                            <option value="270">
                                <h1>270</h1>
                            </option>
                            <option value="275">
                                <h1>275</h1>
                            </option>
                            <option value="280">
                                <h1>280</h1>
                            </option>
                            <option value="290">
                                <h1>290</h1>
                            </option>
                        </select>
                    </form>

                    <?php }
                    // 2) 농구공
                     else if($item_ar['item_category'] == "농구공"){ ?>

                    <?php }
                    // 3) 보호대
                    else if($item_ar['item_category'] == "보호대"){
                           /*보호대 사이즈 형식 UI  */
                    $category_detail =  size_form($category_detail);
                        ?>
                    <li>
                        <h1 style="margin-top:100px; margin-left:15px;  font-size: 30px;"> 사이즈</h1>
                    </li>

                    <!-- 보호대 사이즈 형식 html 코드
                     보호대의 세부종류에 따라서 사이즈 형식이 달라짐. -->
                    <?=$category_detail?>
                    <?php }
                     ?>

                    <!--상품 구매하기, 장바구니 버튼 -->
                    <div class="buy_menu" style="margin: 15px;">
                        <!-- 1)상품 구매버튼 -->
                        <!-- 클릭시 post방식으로 구매할 상품의 정보를 보낸다. -->
                        <!-- 무슨 이유지는 모르지만 integer타입밖에 전달이 안된다. -->
                        <button type="button" class="btn btn-primary btn-lg" onclick="move_purchase_page(<?=$id?>);">
                            바로구매
                        </button>
                        <!-- 2)장바구니 담기 
                        -상품 번호
                        -사용자 id
                        -상품 사이즈
                        -상품 수량
                        을 Post로 보낸다
                    -->
                        <button type="button" onclick='add_basket_item(<?=$id?>,"<?=$item_ar['item_category']?>")'
                            class="btn btn-primary btn-lg">장바구니 </button>
                    </div>

                    <!--4) 상품 수량 및 업다운 버튼-->
                    <div class="num">

                        <h1 style="font-size: 50px;">수량</h1>
                        <!-- 업다운 버튼 폼 -->
                        <div class="updown">
                            <!-- 수량 확인 input태그   -->
                            <input type="text" name="p_num1" id="p_num1" size="2" maxlength="4" class="p_num" value="1"
                                onkeyup="javascript:basket.changePNum(1);" />
                            <span>
                                <i class="fas fa-arrow-alt-circle-up up" onclick="javascript:basket.changePNum(1);"></i>
                            </span>
                            <span>
                                <i class="fas fa-arrow-alt-circle-down down"
                                    onclick="javascript:basket.changePNum(1);"></i>
                            </span>
                        </div>
                    </div>
                    <!-- 5)상품 가격 -->
                    <div class="div_cost">
                        <h1 id="sum_p_price"><?=$item_ar['item_cost']?>원</h1>
                    </div>
                    <!-- 6)상품 번호  -->
                    <input type="hidden" id="item_num" value="<?=$item_num?>">

                </ul><!-- 우측 상품정보(이미지 제외) -->
            </article>
        </section> <!-- 상품정보 끝 -->

        <!-- 하단메뉴 (상세정보) -->
        <section>
            <ul class="bottom_menu">
                <li>
                    <h1 style="color: black; margin-top:30px; ">상세정보</h1>
                </li>
                <li>
                    <h1 id="h1_item_content" style="color: black;"><?=$item_ar['item_content']?></h1>
                </li>
                    <!-- 카테고리 디테일에 따라서 보호대 사이즈 형식을 달리한다 -->
                        <?php
                        guard_form($item_ar['category_detail'])
                        ?>
            </ul>
            <!-- 구분선 -->
            <hr size="15px" width="300%" noshade>
        </section>

    </main>
    <div id="footer">

    </div>
</body>

</html>