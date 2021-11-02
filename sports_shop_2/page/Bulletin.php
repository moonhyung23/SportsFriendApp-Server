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
   
   /* 페이징 관련 코드 */
   
   // 현재 페이지 번호 받아오기
     if(isset($_GET["page"])){
      // 하단에서 다른 페이지 클릭하면 해당 페이지 값 가져와서 보여줌
      $page = $_GET["page"]; 
      }
      else {
      $page = 1; // 게시판 처음 들어가면 1페이지로 시작
      }

    /* 게시물의 전체 로우의 갯수 조회하기 */
    $sql_bulletin = "SELECT * FROM Bulletin"; 
    $result_all = mysqli_query($con, $sql_bulletin);
    // 전체 게시물의 행 갯수 조회하기
    $bulletin_total = mysqli_num_rows($result_all);
    // 한 페이지에 보여줄 게시물 갯수
    $bulletin_count = 10;
    
    //페이지에 보여줄 게시물의 시작 번호
    $bulletin_start = ($page - 1) * $bulletin_count; 


      /*전체 필요한 페이지의 수 
        ceil(전체 게시물의 수/ 한페이지의 보여줄 게시물의 수)
        - 나눈 '몫'이 딱 맞아 떨어지는 경우에는 나눈 "몫"의 값을 받고
        - 나눈 '몫'이 딱 맞아 떨어지지 않는 경우(나눈 나머지 값 존재)에는 ceil함수를 사용해서 나눈 몫에 + 1을 한다.
        - 이유: 나눈 '몫'의 나머지 값을 처리해야 해서
        */
        $total_page_num = ceil($bulletin_total / $bulletin_count); 

      /* 페이지 번호를 표시할 블럭 설정하기 */
          //블록: 페이지를 표시하는 하나의 화면

        //한 블록에 표시할 페이지 갯수
        $page_count = 10; 

   /* 현재 페이지 블록
        - 공식: ceil(현재페이지/ 한 화면에 표시할 페이지 갯수)
        - 공식의 이유: 1개의 블록(화면)은 3개의 페이지를 보여줄 수 있다
        만약 페이지의 전체 갯수가 6개일 때 위의 공식을 적용하면
        - 페이지 번호 3번 =>  현재 블록의 번호는 1번
        - 페이지 번호 4번 =>  현재 블록의 번호는 2번        
        */
        $now_page_num = ceil($page / $page_count); 

       //페이지의 시작번호
       $page_start_num = (($now_page_num - 1) * $page_count) + 1;
        
       //페이지의 마지막번호
       $page_end_num = $page_start_num + $page_count - 1;

    //페이지 마지막 번호가 전체 페이지 수 보다 큰 경우(예외처리)
    if($page_end_num > $total_page_num){
    // 블록 마지막 번호가 총 페이지 수보다 크면 마지막 페이지 번호를 총 페이지 수로 지정함
      $page_end_num = $total_page_num;
    }

    function bulletin_infor($row, $created){ ?>
      <tr onclick='location.href="Bulletin_infor.php?number=<?=$row['number']?>"'>
    <!-- 1.번호 -->
    <td><?=$row['number']?></td>
      <!-- 2.제목 -->
      <td><?=$row['title']?></td>
      <!-- 3.작성자 -->
      <td><?=$row['writter']?></td>
      <!-- 4.작성날짜 -->
        <td><?=$created?></td>
    </tr> 
    <?php
       
      }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <!-- Main 영역 css파일 -->
    <link rel="stylesheet" href="../css_file/bulletin.css?ver=1">
    <!-- 상단 헤더 css 파일 -->
    <link rel="stylesheet" href="../css_file/mypage.css?ver=1">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- 부트스트랩5 링크 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
      <!-- (공용)많이 쓰는 함수 js파일 -->
      <script src="../js_file/Global.js"></script>
        <title>문의 게시판</title>
    </head>
    <body>
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
 <h1 class="text_mypage">문의 게시판</h1>
  <main class="main_Bulletin">
    <div>
      <a href="Add_Bulletin.php">
      <button style="font-size:40px; margin:10px;"  value="작성" type="button" class="btn btn-primary btn-lg">글 작성</button>
      </a>
    </div>
  

  <table class="table caption-top table-hover_bulletin">
  <!-- 테이블 헤드 -->  
  <thead>
    <!-- 테이블 로우 -->
      <tr>
        <th style="width: 50px;" scope="col">번호</th>
        <th style="width: 70%" scope="col">제목</th>
        <th style="width: 10%"  scope="col">작성자 ID </th>
        <th style="width: 10%"  scope="col">작성한 날짜</th>
      </tr>
    </thead>
    <!-- 테이블 컬럼 -->
    <tbody>
    <!-- 모든 문의글을 조회한다. -->
    
    <?php 

    /* 게시물 목록 가져오기
        -테이블의 데이터를 number의 값을 내림차순으로 정렬
        -$bulletin_start 시작으로 $bulletin_count(게시물)의 수만큼 정보를 가져온다.
        */
  $sql_select = "SELECT * FROM Bulletin ORDER BY number DESC LIMIT $bulletin_start, $bulletin_count"; 
  $result_select = mysqli_query($con, $sql_select);
  while($row = mysqli_fetch_assoc($result_select)){
    //작성한 날짜에서 '년도', '월', '일'만 가져오기
      $created = substr($row['created'], 0, 10);
      /*1. 문의 글  바로 열람 가능 
        -둘 중 하나라도 해당 되는 경우 
        1)관리자 아이디(id= 1), => 관리자는 모든 글을 확인할 수 있음
        2)작성자 아이디(id) => 관리자가 아닌 작성자는 본인이 작성한 글만 확인 가능
      */
    if($id == 1 || $id == $row['id']){ 
      bulletin_infor($row, $created);
   }
   else
   {
     /*** 2. 다른 사람이 작성한 글인 경우  ***/ 
 /* check_secret의 번호에 따라서 비밀글을 처리한다.
      1번: 비밀 글
      0번: 공개 글 
    */
    if($row['check_secret'] == 1){ //2-1)비밀 글 
      ?> <!-- 비밀글인 경우  
              -1)문의 글 번호
              -2)비밀 글 처리번호
              Get으로 전달 -->
       <!--문의 글 비밀번호 인증 페이지로 이동 -->
      <tr onclick='location.href="Check_bulletin_pw.php?number=<?=$row['number']?> && check_secret=<?=$row['check_secret']?>"'>
          <!-- 1)번호 -->
          <td><?=$row['number']?></td>
          <!-- 2)제목 ==> 비밀 글 처리 --> 
          <td>비밀 글 입니다.</td>
          <!-- 3)작성자 -->
          <td><?=$row['writter']?></td>
          <!-- 4.작성날짜 -->
          <td><?=$created?></td>
      </tr> 
    <?php }
    else if($row['check_secret'] == 0){ // 2-2)공개 글
      bulletin_infor($row, $created);
     ?>
      <?php
      }
    }
  }?>
  </tbody>
</table>

   <!-- 게시물 목록 중앙 하단 페이징 부분-->
  <nav class="page_nav">
  <ul class="page_ul_bulletin">
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
      <li class='page-item'><a class='page-link' id="page_item" href='Bulletin.php?page=1'>처음</a></li>
      <!-- '이전' 페이지로 이동 -->
      <li class='page-item'><a class='page-link' id="page_item" href='Bulletin.php?page=<?=$pre?>'>◀ 이전</a></li>
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
      <li class='page-item'><a class='page-link' href='Bulletin.php?page=<?=$i?>'><?=$i?></a></li>
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
      <li class='page-item'><a class='page-link' id="page_item" href='Bulletin.php?page=<?=$next?>'>다음</a>
    <!-- 마지막 페이지로 이동 -->
      <li class='page-item'><a class='page-link' id="page_item" href='Bulletin.php?page=<?=$total_page_num?>'>마지막</a>
    <?php
    }
  ?>

  </ul>
  </nav>
  </main>

</body>
</html>