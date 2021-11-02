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
/*  검색한 키워드를 Get방식으로 받는다. */
$keyword = $_GET['keyword'];
//따옴표 처리
$keyword_slashes =  addslashes($keyword);

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
  
  /*3.검색한 게시물의 전체 로우의 개수 조회하기 */
  $sql_bulletin = "SELECT * FROM Add_Item WHERE item_name_A  LIKE '%$keyword_slashes%'"; 

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
    <!-- css 파일 -->
    <link rel="stylesheet" href="../css_file/main.css?ver=1" />
    <!-- 부트 스트랩 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>상품 검색</title>
</head>
<body>

<script>
function scroll_move(){
    
    window.onload = function(){
    var offset = $(".text_mypage").offset();
        $('html, body').animate({scrollTop : offset.top}, 400);
} 
    }
    scroll_move();
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
                <input class="ed_search" value="<?=$keyword_slashes?>" id="input_keyword" size="100" style="height: 90px; font-size: 50px" type="text" placeholder="검색어 입력" />
                <!-- 검색 버튼 -->
                <button class="btn btn-primary"
                onclick='search_keyword()'
                style="
                font-size: 40px;
                margin-bottom: 25px;
                width: 200px;
                height: 90px;
          ">검색</button>
            </div>
                <!-- 카테고리메뉴 ui를 리턴받는다. (Ui.php 선언됨) -->
                <?= Category_menu(); ?>
                </nav>
                
                <main>
                    <!-- 상품정보 영역 -->
         <section class="item_section">
         <!-- 페이지 이름 -->
 <h1 class="text_mypage">"<?=$keyword_slashes?>"검색결과</h1>
    <?php 

    //검색한 상품의 로우를 조회하고 내림차순으로 변경해서 페이징 
     $sql_select = "SELECT * FROM Add_Item WHERE item_name_A  LIKE '%$keyword_slashes%' ORDER BY Item_num_A DESC LIMIT $bulletin_start, $bulletin_count"; 
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
  ?>

     <!--페이지번호 표시 -->
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
        <li class='page-item'><a class='page-link' id="page_item" href='Search.php?keyword=<?=$keyword_slashes?>&&page=1'>처음</a></li>
        <!-- '이전' 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='Search.php?keyword=<?=$keyword_slashes?>&&page=<?=$pre?>'>◀ 이전</a></li>
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
        <li class='page-item'><a class='page-link' href='Search.php?keyword=<?=$keyword_slashes?>&&page=<?=$i?>'><?=$i?></a></li>
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
        <li class='page-item'><a class='page-link' id="page_item" href='Search.php?keyword=<?=$keyword_slashes?>&&page=<?=$next?>'>다음 ▶</a>
      <!-- 마지막 페이지로 이동 -->
        <li class='page-item'><a class='page-link' id="page_item" href='Search.php?keyword=<?=$keyword_slashes?>&&page=<?=$total_page_num?>'>마지막</a>
      <?php
      }
    ?>
</ul>
</nav>
            </section>

    </main>

</body>
</html>



    
  






    
    
    
    

