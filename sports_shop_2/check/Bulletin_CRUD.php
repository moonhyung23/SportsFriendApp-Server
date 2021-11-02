
 <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
<script src="../js_file/Global.js"></script>
 
<!-- 게시판 글 추가 수정 삭제 -->
<?php 
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";


/*** 1.post로 게시판 글 정보 받아오기 ***/
$crud_check = $_POST['crud_check'];

/* 문의 글을 작성, 수정, 삭제에 따라 Post로 불러오는 데이터를 다르게 한다 */

// 1)문의 글 작성
if($crud_check == "작성"){
  $id = $_POST['id'];
  $title = $_POST['title'];
  $writter = $_POST['writter'];
  $content = $_POST['content'];
  //작성, 수정, 삭제 확인 텍스트 
  /* 입력한 비밀번호가 있을 때만 */
  if($_POST['pw']){
    $pw = $_POST['pw'];
  }
}
/* 수정과 삭제하는 경우 게시글의 번호를  Post로 불러온다 */

// 2)문의 글 수정
else if($crud_check == "수정"){
  //게시글의 번호를 post로 받아온다 (수정, 삭제 할 때만 )
  $number = $_POST['number']; //게시글 번호
  $id = $_POST['id'];
  $title = $_POST['title'];
  $writter = $_POST['writter'];
  $content = $_POST['content'];
  //작성, 수정, 삭제 확인 텍스트 
  /* 입력한 비밀번호가 있을 때만 */
  if(isset($_POST['pw'])){
    $pw = $_POST['pw'];
  }
}

// 3)문의 글 삭제
else if($crud_check == "삭제"){
  //게시글의 번호를 post로 받아온다 (수정, 삭제 할 때만 )
  $number = $_POST['number']; //게시글 번호
}

//게시글번호 integer 변환
settype($number, 'integer');
  
/*** 2.DB에서 데이터 받아오기 ***/

/* 1.문의 글 작성 */
if($crud_check == "작성"){
/* 1)비밀번호가 입력된 경우(비밀 글) 
  -비밀번호 체크번호 1번
*/
if(isset($pw)){
  //db에 데이터 저장 요청
  $sql_insert = "INSERT INTO Bulletin (id, title, writter, content, pw, check_secret, created) VALUES (
    $id, 
   '$title', 
   '$writter',
   '$content',
   '$pw',
    1,
    NOW()
   )";
 }else{ 
 /* 2)비밀번호가 입력되지 않은 경우(비밀 글X)
    -db에 데이터 저장 요청
    -비밀번호 체크번호 0번 */
 $sql_insert = "INSERT INTO Bulletin (id, title, writter, content, created) VALUES (
    $id, 
   '$title', 
   '$writter',
   '$content',
    NOW()
   )";
 }

 $result_insert = mysqli_query($con, $sql_insert);
 if($result_insert === true){ 
  ?> <script>  move_page("문의 글이 작성되었습니다", "../page/Bulletin.php" ); </script>  <?php
     //결과가 성공일때 실행되는 코드
 }else {
   //결과가 실패일때 실행되는 코드
     echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
 }
}

  /* 2.문의 글 수정 
    *** 수정할 내용 ***
      -글 제목
      -글 내용
      -작성자
      -비밀번호
      -비밀번호 체크 번호
  */
  else if($crud_check == "수정"){
  // *** 비밀 번호 처리 번호($check_secret) ***
  //  1번 => 비밀 글  
  //  0번 => 공개 글
    
  //2-1)비밀번호가 입력된 경우(비밀 글) 1번
  if(isset($pw)){
    $sql_update="UPDATE Bulletin
    SET title = '$title',
        content = '$content',
        writter = '$writter',
        pw = '$pw',
        check_secret = 1
    WHERE number = $number "; 
  }
  //2-2)비밀번호가 입력되지 않은 경우(공개 글) 0번
  //  비밀번호($pw)  "" 처리
  else{
    $sql_update="UPDATE Bulletin
    SET title = '$title',
        content = '$content',
        writter = '$writter',
        pw = '',
        check_secret = 0 
    WHERE number = $number "; 
  }

  $result_update = mysqli_query($con, $sql_update);
  if($result_update === true){
      //결과가 성공일때 실행되는 코드
      ?> <script>  move_page("문의 글이 수정되었습니다", "../page/Bulletin.php" ); </script>  <?php
  }else {
    //결과가 실패일때 실행되는 코드
      echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
  }
}

/* 3.문의 글 삭제 */
else if($crud_check == "삭제"){

    //3-1)삭제하려는 문의 글의 댓글의 갯수를 조회
  $sql_select = "SELECT * FROM Comment WHERE bulletin_num = $number "; 
  $result_select = mysqli_query($con, $sql_select); 
  $row_count = mysqli_num_rows($result_select);


    
  /*4. 삭제 구분
    -댓글이 적힌 문의 글
    -댓글이 적히지 않은 문의 글
    (이유: 댓글이 적히지 않은 문의 글 삭제시 이미 조인된 sql문을 사용하므로 삭제가 안되는 오류 발생해서.)
    */

    //4-1)댓글이 없는 문의 글 삭제
    if($row_count == 0){
      //문의 글 번호와 맞는 문의 글을 삭제한다.
      $sql_delete="DELETE from Bulletin WHERE number = $number";
      $result_delete = mysqli_query($con, $sql_delete);

       // 1)삭제 성공 
       if($result_delete === true){  ?>
        <script> move_page("삭제가 완료되었습니다", "../page/Bulletin.php") </script> 
        <?php
       }else {
         //2)삭제 실패
           echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
       }

    }
    else{ //4-2)댓글이 있는 문의 글 삭제
    /* 문의 글 번호에 맞는 로우를 삭제한다.
     1)JOIN한 테이블
       -문의 글
       -댓글
     2)삭제 조건: 문의 글 번호와 같은 컬럼이 있는 댓글과 게시글
     -댓글에도 어느 문의 글에 적힌 댓글인지 확인하는 문의글의 번호 컬럼이 있다. 
   */

      $sql_delete="DELETE t1, t2 
      FROM Bulletin t1 INNER JOIN Comment t2
      WHERE t1.`number` = $number AND t2.bulletin_num = $number";
      $result_delete = mysqli_query($con, $sql_delete);
      
     // 1)삭제 성공 
      if($result_delete === true){  ?>
       <script> move_page("삭제가 완료되었습니다", "../page/Bulletin.php") </script> 
       <?php
      }else {
        //2)삭제 실패
          echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
      }
    
    }

   
   
}



?>