<script>
/* 검색 기능 
 - 입력한 키워드를 Get방식으로 검색결과 페이지에 보낸다.
*/
function search_keyword() {
    // 검색한 키워드의 텍스트 값을 갖고온다.
    let keyword = document.querySelector("#input_keyword").value;
    // 텍스트 값을 Get으로 보낸다.
    document.location.href = "Search.php?keyword=" + keyword;
}
</script>
<?php 
//db파일 추가
include "../Db.php";

//Ui 파일 추가
include  "../Common_Ui.php";
    
//2.세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];

// id와 맞는 사용자 정보를 조회한다.
$sql_select = "SELECT * FROM User WHERE id = $id"; 
$result_select = mysqli_query($con, $sql_select);
$row = mysqli_fetch_assoc($result_select);


/* 로그인메뉴 Ui를 메서드에서 리턴받는다.  */
$login = Login_Menu($_SESSION['id'], $row['name']);

/* 클릭한 카테고리 값 GET메서드로 갖고오기 */
if(isset($_GET['category'])){
    $click_category = $_GET['category'];
}else{
    $click_category ="";
}

/* 카테고리별로 보여줄 상품 구분하기 */
//전체 상품이 아닌 경우 => 카테고리의 해당하는 상품만 보여주기
if($click_category != "전체상품"){
  $category_check = 2; 
}
//전체 상품인 경우 => 전체 상품 보여주기
else if($click_category == "전체상품"){
    $category_check = 1; 
}

//맨처음 페이지 시작(공백이 있는 경우)
if($click_category == "" || $click_category == null ){
    $category_check = 1; 
}

    //최근 본 상품 정보 표시해주는 함수
    function recently_item_infor($con, $id){

    //최근 본 상품 번호 배열이 쿠키에 저장되지 않은 경우(예외처리)
    if(!isset($_COOKIE[(string)$id])){
        return;
    }    

    /* 쿠키에서 상품 정보 json 배열을 갖고온다. 
        -갖고온 json 배열을 => 배열로 변환
    */
    $item_num_ar = json_decode($_COOKIE[(string)$id]);
  
    /* 상품 번호 배열의 갯수만큼  상품 정보 가져오기 */
    for($i = 0; $i < count($item_num_ar); $i++){
     //상품 번호와 맞는 상품 정보 로우를 조회한다
    $sql_select = "SELECT * FROM Add_Item WHERE item_num_A = $item_num_ar[$i]"; 
    $result_select = mysqli_query($con, $sql_select);
    //최근 본 상품의 정보 로우
    $row_item_infor_cookie = mysqli_fetch_assoc($result_select); 
    ?>
    <!-- 상품 정보 태그  -->
    <div>
        <!-- 1)상품 링크 -->
        <a href="Item_Infor.php?item_num=<?=$row_item_infor_cookie['item_num_A']?>">
        <!-- 2)상품 이미지 -->
        <img src="../image_files/<?=$row_item_infor_cookie['img_url']?>" alt="">
        <!-- 3)상품 이름 -->
        <h1><?=$row_item_infor_cookie['item_name_A']?></h1>
        <!-- 4)상품 가격 -->
        <h1><?=$row_item_infor_cookie['cost']?>원</h1>
        </a>
        </div>
    <?php 
}
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- J쿼리 -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <!-- css 파일 -->
    <link rel="stylesheet" href="../css_file/main.css?ver=1" />
    <!-- 부트스트랩 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
    <script src=""></script>
    <!-- 도메인 이름 -->
    <title>Jordan's basketball Shop</title>
</head>

<body>
<script>
function scroll_move(category){
    if(!category){
        return;
    }
    window.onload = function(){
    var offset = $(".item_category").offset();
        $('html, body').animate({scrollTop : offset.top}, 400);
} 
    }
    scroll_move("<?=$_GET['category']?>");
</script>

    <nav class="header">
        <!-- 1.제목(맨 위) -->
        <a class="title" href="Main.php">
            <h1>Jordan's basketball Shop</h1>
        </a>
        <!-- myMenu -->
        <?=$login;?>
            <div class="navbar_search">
                <a href="Main.php">
                    <!--2. 웹 로고 이미지 -->
                    <img src="../web_image/weblogo.jpg" alt="none_image" width="1000px" height="700px" />
                </a>
            </div>
            <div style="margin-top: 50px" class="navbar_search">
                <!-- 입력 텍스트 -->
                <input class="ed_search" id="input_keyword" size="100" style="height: 90px; font-size: 50px" type="text" placeholder="검색어 입력" />
                <!-- 검색 버튼 
                검색한 내용을 Get으로 보낸다. -->
                <button class="btn btn-primary"
                onclick='search_keyword()'
                style="
                font-size: 40px;
                margin-bottom: 25px;
                width: 200px;
                height: 90px;
          ">
          검색
        </button>
            </div>
            <!-- 카테고리메뉴 ui를 리턴받는다. (Ui.php 선언됨) -->
            <?= Category_menu(); ?>
    </nav>
    <main>
        <!-- 1.메인 화면 이미지 -->
        <div class="iv_jordan">
            <a href="Main.php">
                <img src="../image_files/main_image.jpg" style="width: 100%; height: 1200px; margin: 30px" alt="none" />
            </a>
        </div>
        <!-- 카테고리 상품 검색시 최근 본 상품 표시하지 않기 -->

      
            <!-- 최근 본 상품  표시 (카테고리, 검색하지 않았을 때)-->
        <?php 
        if($category_check == 1){
            ?>
        <div class="item_category">
            <h1>최근 본 상품 </h1>
        </div> 
        <section class="item_section">
            <!-- 최근 본 상품 정보 생성  -->
            <?= recently_item_infor($con, $id) ?>
            </section>
        <?php 
        }
        ?>
         
        <?php
    /* 1번: 전체 상품 카테고리    */
    if($category_check == 1){ ?>
            <!-- 1.농구화  -->
            <!-- 1-1) 상품 카테고리  -->
            <div class="item_category">
                <h1 id="item_name">농구화</h1>
            </div>

            <!--1-2)전체 농구화 상품 정보(이미지 + 이름 + 가격)-->
            <section class="item_section">
                <?php 

    // 카테고리 컬럼이 '농구화'인 것만 조회한다
  $sql_select = "SELECT * FROM Add_Item WHERE category = '농구화' ORDER BY Item_num_A DESC LIMIT 10 "; 
     $result = mysqli_query($con, $sql_select);
            while ($row = mysqli_fetch_assoc($result)) { 
                ?>
                <!--1-3)농구화 상품 정보(1개))-->
                <div>
                    <!-- 1)상품 번호 -->
                    <!-- 클릭하면 상품 정보 창으로 이동 -->
                    <!-- 클릭한 상품의 id를 get방식으로 url에 저장 -->
                    <a href="Item_Infor.php?item_num=<?=$row['item_num_A']?> ">
                        <!-- 2)상품 이미지 -->
                        <img class="img-thumbnail" src="../image_files/<?=$row['img_url']?>" />
                        <!-- 3)상품 이름 -->
                        <h1><?=$row['item_name_A']?></h1>
                        <!-- 4)상품 가격 -->
                        <h1><?=$row['cost']?>원</h1>
                    </a>
                </div>
                <?php
}
/* 페이징 페이지번호 Ui 
    1.농구공
*/
  ?>
            </section>
            <!-- 2.농구공  -->
            <!-- 2-1) 상품 카테고리  -->
            <div class="item_category">
                <h1 id="item_name" >농구공</h1>
            </div>
            <!--2-2)전체 농구공 상품 정보(이미지 + 이름 + 가격)-->
            <section class="item_section">
                <?php 
   // 카테고리 컬럼이 '농구공'인 것만 조회한다
   $sql_select = "SELECT * FROM Add_Item WHERE category = '농구공' ORDER BY Item_num_A DESC LIMIT 10 " ;
    $result = mysqli_query($con, $sql_select);
           while ($row = mysqli_fetch_assoc($result)) { ?>
                <!--2-3)농구화 상품 정보(1개))-->
                <div>
                    <!-- 1)상품 번호 -->
                    <!-- 클릭하면 상품 정보 창으로 이동 -->
                    <!-- 클릭한 상품의 id를 get방식으로 url에 저장 -->
                    <a href="Item_Infor.php?item_num=<?=$row['item_num_A']?> ">
                        <!-- 2)상품 이미지 -->
                        <img class="img-thumbnail" src="../image_files/<?=$row['img_url']?>" />
                        <!-- 3)상품 이름 -->
                        <h1>
                            <?=$row['item_name_A']?>
                        </h1>
                        <!-- 4)상품 가격 -->
                        <h1>
                            <?=$row['cost']?>원</h1>
                    </a>
                </div>
                <?php
        }
    /* 페이징 페이지번호 Ui
    2.농구공
    */
    ?>
            </section>
            <!-- 3.보호대  -->
            <!-- 3-1) 상품 카테고리  -->
            <div class="item_category">
                <h1 id="item_name">보호대</h1>
            </div>

            <!--3-2)전체 보호대 상품 정보(이미지 + 이름 + 가격)-->
            <section class="item_section">
                <?php 
   // 카테고리 컬럼이 '보호대'인 것만 조회한다
   $sql_select = "SELECT * FROM Add_Item WHERE category = '보호대' ORDER BY Item_num_A DESC LIMIT 10 ";
   $result = mysqli_query($con, $sql_select);
           while ($row = mysqli_fetch_assoc($result)) { ?>
                <!--1-3)농구화 상품 정보(1개))-->
                <div>
                    <!-- 1)상품 번호 -->
                    <!-- 클릭하면 상품 정보 창으로 이동 -->
                    <!-- 클릭한 상품의 id를 get방식으로 url에 저장 -->
                    <a href="Item_Infor.php?item_num=<?=$row['item_num_A']?> ">
                        <!-- 2)상품 이미지 -->
                        <img class="img-thumbnail" src="../image_files/<?=$row['img_url']?>" />
                        <!-- 3)상품 이름 -->
                        <h1>
                            <?=$row['item_name_A']?>
                        </h1>
                        <!-- 4)상품 가격 -->
                        <h1>
                            <?=$row['cost']?>원</h1>
                    </a>
                </div>
                <?php
}
    /* 페이징 페이지번호 Ui
        3.보호대
        */
 ?>
            </section>
            <?php 
    } // $category_check = 1 => 전체 상품 카테고리 끝 //$category_check = 2 => 상품 분류하기 else
      if($category_check == 2){
          /* *** 4.클릭한 카테고리  *** */
    if(isset($_GET["page_click"])){
        $page_click = $_GET["page_click"]; 
    }
    else {
        $page_click = 1; // 게시판 처음 들어가면 1페이지로 시작
    }

    $bulletin_count_click = 10;
    //클릭한 상품의 카테고리의 행의 갯수를 구한다
    $sql_bulletin = "SELECT * FROM Add_Item where category = '$click_category' "; 
    $result_all = mysqli_query($con, $sql_bulletin);
    $bulletin_total_click = mysqli_num_rows($result_all);
    $bulletin_start_click = ($page_click - 1) * $bulletin_count_click; 
    $total_page_num_click = ceil($bulletin_total_click / $bulletin_count_click); 
    
    $page_count_click = 5; 
    $now_page_num_click = ceil($page_click / $page_count_click); 
    
    $page_start_num_click = (($now_page_num_click - 1) * $page_count_click) + 1;
    $page_end_num_click = $page_start_num_click + $page_count_click - 1;
    if($page_end_num_click > $total_page_num_click){
        $page_end_num_click = $total_page_num_click;
    }
          ?>
            <!-- 3-1) 상품 카테고리  -->
            <div class="item_category">
                <h1>
                    <?=$click_category?>
                </h1>
            </div>

            <!-- 상품 정보 영역 -->
            <section class="item_section">
                <?php 
   //클릭한 상품 카테고리의 정보를 조회한다.
   $sql_select = "SELECT * FROM Add_Item WHERE category = '$click_category' ORDER BY Item_num_A DESC  LIMIT $bulletin_start_click, $bulletin_count_click";
   $result = mysqli_query($con, $sql_select);
           while ($row = mysqli_fetch_assoc($result)) { ?>
                <!--1-3)농구화 상품 정보(1개))-->
                <div>
                    <!-- 1)상품 번호 -->
                    <!-- 클릭하면 상품 정보 창으로 이동 -->
                    <!-- 클릭한 상품의 id를 get방식으로 url에 저장 -->
                    <a href="Item_Infor.php?item_num=<?=$row['item_num_A']?> ">
                        <!-- 2)상품 이미지 -->
                        <img class="img-thumbnail" src="../image_files/<?=$row['img_url']?>" />
                        <!-- 3)상품 이름 -->
                        <h1>
                            <?=$row['item_name_A']?>
                        </h1>
                        <!-- 4)상품 가격 -->
                        <h1>
                            <?=$row['cost']?>원</h1>
                    </a>
                </div>
                <?php
}
?>       <!--페이지번호 표시 -->
            <nav class="page_nav">
            <ul class="page_ul">
            <?php 
            /* 1.페이지의 '처음', '이전' 텍스트링크 표시하기 */
            // 1-1)첫 페이지인 경우
            if ($page_click <= 1){
            //   -'처음' 텍스트링크 없애기
            //   -'이전' 텍스트링크 없애기
            } else {
            //1-2)첫 페이지가 아닌 경우
            $pre = $page_click - 1; //이전할 페이지의 번호
                /*   현재 페이지가 1보다 큰 경우 */
                ?>
                <!-- '처음' 페이지로 이동 -->
                <li class='page-item'><a class='page-link'  id="page_item" href='Main.php?category=<?=$click_category?>&&page_click=1'>처음</a></li>
                <!-- '이전' 페이지로 이동 -->
                <li class='page-item'><a class='page-link' id="page_item" href='Main.php?category=<?=$click_category?>&&page_click=<?=$pre?>'>◀ 이전</a></li>
            <?php
            }

            /* 2.페이지 갯수만큼 페이지 번호 표시 */
            for($i = $page_start_num_click; $i <= $page_end_num_click; $i++){
            /* 현재 내가 위치한 페이지의 번호와 다른 페이지 번호의 색깔을 구분한다 */
            if($page_click == $i){
                //현재 내가 위치한 페이지의 번호
                ?>
                <li class='page-item'>
                <a class='page-link' disabled style="color: #df7366;"><?=$i?></a>
                </li>
            <?php
            } else {
                //다른 페이지의 번호
                ?>
                <li class='page-item'><a class='page-link' href='Main.php?category=<?=$click_category?>&&page_click=<?=$i?>'><?=$i?></a></li>
            <?php
            }
            }

            /* 3.마지막 페이지인 경우 '다음', '마지막' 텍스트 링크 표시하기*/
            // 3-1)마지막 페이지인 경우
            if($page_click >= $total_page_num_click){
            } else {
            // 3-2)마지막 페이지가 아닌 경우    
            $next = $page_click + 1; //이동할 페이지 
            ?>
                <li class='page-item'><a class='page-link' onclick="" id="page_item" href='Main.php?category=<?=$click_category?>&&page_click=<?=$next?>'>다음 ▶</a>
            <!-- 마지막 페이지로 이동 -->
                <li class='page-item'><a class='page-link' id="page_item" href='Main.php?category=<?=$click_category?>&&page_click=<?=$total_page_num_click?>'>마지막</a>
            <?php
            }
            ?>
            </ul>
            </nav>
            </section>
            <?php }
    ?>
    </main>
</body>

</html>





    
  





    
    
    
    

