<!-- 게시글 상세 내용 -->
<?php 
   //db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

    //세션 설정
    session_start();
    settype($_SESSION['id'], 'integer');
    $id = $_SESSION['id'];

    /* Get으로 클릭한 문의 글의 번호를 갖고온다 */
    $number = $_GET['number'];
    settype($number, 'integer');
    /* 문의 글번호에 맞는 문의 글 정보를 불러온다. */
    $sql_select = "SELECT * FROM Bulletin WHERE number = $number "; 
    $result_select = mysqli_query($con, $sql_select);
    $row_bulletin = mysqli_fetch_assoc($result_select);
    //날짜 년, 월, 일 만 표시하기
    $created = substr($row_bulletin['created'], 0, 10);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="../css_file/bulletin_infor.css">
    <title>문의 글 상세 정보</title>
    <!-- 부트스트랩5 링크 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- (공용)많이 쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>
    <!-- JQuery 파일 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
</head>

<body>
<script>
      /* 1.댓글 작성  */
      $(function () {
        // 댓글 작성 버튼 클릭 이벤트
        $("#insert_comment").click(function () {
          let input_content = $("#input_content").val();
          //공백 검사 
          if( input_content == ""){
            alert("댓글을 입력해주세요.")
            return;
          }

          //콜백 함수 (댓글 작성 버튼)
          $.ajax({
            url: "../check/Comment_CRUD.php", //이동할 url
            type: "post", //보내는 방식
            data: {
              /* crud_check 번호에 따라 추가 삭제 처리
              댓글 추가: 0번
              댓글 삭제: 1번
              */
              content: $("#input_content").val(), //댓글 내용
              bulletin_num: $("#bulletin_number").val(), //문의 글 번호
              crud_check: 0  //댓글 처리번호 (0: 추가)
            },
            success: function (data) {
          location.reload(); //바로 새로고침.
            
               // 작성한 댓글 공백처리
            $("#input_content").val("");
            },
          });
        });
      });

        /* 2.댓글 삭제  */
        function comment_delete(comment_number){
          // 정말 삭제할 것인지 체크
          delete_check("정말 삭제하시겠습니까?");
          $.ajax({
            url: "../check/Comment_CRUD.php", //삭제
            type: "post", //보내는 방식
            data: {
              /* crud_check 번호에 따라 추가  삭제 처리
               댓글 추가: 0번
               댓글 삭제: 1번
              */
              bulletin_num: $("#bulletin_number").val(), //문의 글 번호
              comment_num: comment_number, //댓글 번호
              crud_check: 1  //댓글 처리번호 (0: 삭제)

            },
            success: function (data) {
              location.reload(); //바로 새로고침.
            },
          });
        }

    </script>

    <article>
        <div class="container" role="main">
          <!-- 게시판 추가, 수정, 삭제 페이지로 이동 -->
            <form action="../check/Bulletin_CRUD.php" method="post">
            <!-- 게시글의 번호를 Post방식으로 페이지에 보낸다 -->
        <input type="hidden" name="number" id="bulletin_number" value="<?=$row_bulletin['number']?>">
        <!-- 게시글 수정, 삭제, 목록 버튼 -->
      

        <!-- 수정 버튼 클릭시 게시글 작성 페이지로 이동
            -게시글의 번호를 Get으로 보낸다.
    -->
            <?php 
            //문의 글 수정 삭제 가능한 아이디
            // 1.관리자 아이디
            // 2.문의 글 작성자의 아이디인 경우
            if($id == 1 || $id == $row_bulletin['id'] ){ ?>
                <div class="div_btn_crud">
                  <!-- 게시글 수정 -->
                <button type="button" name="crud_check"  onclick='location.href="Add_Bulletin.php?number=<?=$number?>"' value="수정" class="btn btn-sm btn-primary" id="btnUpdate">
                수정
              </button>
              <!-- 게시글 삭제 -->
              <button type="submit" name="crud_check" value="삭제"  class="btn btn-sm btn-primary" id="btnDelete">
                삭제
              </button>
              <!-- 문의 글 목록으로 이동 -->
              <button type="button" onclick='location.href="Bulletin.php"' class="btn btn-sm btn-primary" id="btnList">
                목록
              </button>
                </div>  
         <?php   }
            ?>
            <!-- 문의 글 제목 -->
            <div class="bg-white rounded shadow-sm">
                <div class="board_title"> 제목: <?=$row_bulletin['title'] ?> </div>
                <!-- 작성자 -->
                <div class="board_info_box">
                <h1 class="board_author">작성자 ID: <?=$row_bulletin['writter']?> </h1>
                </div>
                <!-- 날짜 -->
                <div  class="board_date" >날짜: <?=$created?> </div>

                <!-- 문의 내용 -->
                <div  class="board_content">문의 내용: <?=$row_bulletin['content']?> </div>
            </div>

            <!-- 댓글 입력 폼 -->
            <div class="my-3 p-3 bg-white rounded shadow-sm" style="padding-top: 10px">
                    <div class="row">
                        <div class="col-sm-10">
                        <!-- 댓글 입력 -->
                            <textarea style="font-size:40px;" path="content" id="input_content" class="form-control" rows="4" placeholder="댓글을 입력해 주세요"></textarea>
                        </div>
                        <div class="col-sm-2">
                        
                        <!-- 댓글 작성 버튼 -->  
                        <button style="width:100px; font-size: 30px;" type="button" class="btn btn-sm btn-primary" 
                                id="insert_comment" style="width: 100%; margin-top: 10px"> 작성 </button>
                        </div>
                    </div>
            </div>

            <div class="my-3 p-3 bg-white rounded shadow-sm" style="padding-top: 10px">
                <!-- 테두리 선 -->
                <h1 class="border-bottom pb-2 mb-0">댓글</h1>
                <!-- 댓글 목록 -->
                <div id="replyList">
            <?php 
                /* 문의 글 번호에 맞는 댓글 정보를 조회한다. */
                $sql_select = "SELECT * FROM Comment WHERE bulletin_num = $number "; 
                $result_select = mysqli_query($con, $sql_select);
                while($row_comment = mysqli_fetch_assoc($result_select)){
                    ?>
                <!-- 댓글 형식 -->
                <div class="media text-muted pt-3">
                <!-- 1.작성자  -->
                <h2 style="font-size:30px; margin-bottom:20px;"><?=$row_comment['writter_uid']?></h2>
                <!-- 2.댓글 내용 -->
                <h1 style="font-size: 40px; margin-bottom:20px; color:black"><?=$row_comment['content']?></h1>
                <!-- 3.날짜 -->
                <h2 style="font-size:30px; margin-bottom:10px;"><?=$row_comment['created']?></h2>
                <!--(댓글 삭제 관리자), (댓글 작성자) 아이디만 삭제 텍스트 링크 보이게 하기 
                  -게시물 작성자도 댓글을 지울 수는 없음.
                -->
              <?php  if($id == 1 || $id ==  $row_comment['id']){
                ?>
                <!-- 댓글 삭제 텍스트 링크 영역 -->
                <span style="text-align: end;" class="d-block">
                <strong class="text-gray-dark"></strong>
                <span style=" font-size: 25px; ">
                <!--4. 댓글 삭제 텍스트 -->
                <a onclick="comment_delete(<?=$row_comment['comment_num']?>);" id="btn_delete" href="#" >삭제</a>
                </span>
                </span>
                <?php
              }
              ?>
                </div><!-- 댓글 형식 end -->
                <?php 
                }
            ?>
                </div> 
            </div>
            </form>
        </div>
    </article>
    </div>
</body>

</html>