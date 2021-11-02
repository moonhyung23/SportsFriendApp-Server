<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";
    //모집 글 댓글 정보 배열
    $ar_comment = array();
    //POST로 클라이언트에서 보낸 데이터를 받아온다
    $bltn_idx = $_POST['bltn_idx']; //1)모집 글 인덱스 번호
    $user_idx = $_POST['user_idx']; //2)회원정보 idx
    $limit = $_POST['limit']; //3)한 페이지에 조회되는 최대 댓글 개수
    $page = $_POST['page']; //4)페이지 번호

  
    
    // *모집글의 전체 댓글 개수 조회
    //댓글 번호와 맞는 답글 조회하기
    $sql_select = "SELECT * FROM COMMENTS WHERE bltn_idx = $bltn_idx" ;
    $result_select = mysqli_query($con, $sql_select);
    //전체 답글 개수 조회
    $comment_cnt_all = mysqli_num_rows($result_select);

    //조회할 댓글이 없는 경우
    if($comment_cnt_all == 0){
      echo "댓글없음";  
      exit();
    }

    //페이징 시작 번호 
    $comment_start = ($page -1) * $limit;

    //댓글 정보 조회 쿼리문
    $sql_select_comment = "SELECT * 
    FROM COMMENTS AS c
    INNER JOIN USERS AS u
    ON c.user_idx = u.user_idx 
    WHERE c.bltn_idx = $bltn_idx
    ORDER BY comment_idx ASC
    LIMIT $comment_start, $limit";

    //댓글 정보 조회
    $result_select_comment = mysqli_query($con, $sql_select_comment);
    //댓글 개수 조회
    $comment_cnt_paging = mysqli_num_rows($result_select_comment);

    //페이징할 댓글이 없는경우
    if($comment_cnt_paging == 0){
      echo "댓글없음";  
      exit();
      }
    
      //댓글 개수 만큼 반복
    while($row_comment = mysqli_fetch_assoc($result_select_comment)){
      //댓글의 답글 개수를 조회
      $sql_select_cnt = "SELECT * FROM REPLY WHERE comment_idx = {$row_comment['comment_idx']} "; 
      $result_reply = mysqli_query($con, $sql_select_cnt);
      //답글 개수 조회
      $reply_cnt = mysqli_num_rows($result_reply);  
  
      //클라이언트에 보낼 작성한 댓글정보 배열
      array_push($ar_comment, 
      array(
          'user_idx' => $row_comment['user_idx'],  // 1) 작성자 인덱스 번호 
          'bltn_idx' => $row_comment['bltn_idx'],  // 2) 모집 글 인덱스 번호
          'comment_idx' => $row_comment['comment_idx'], // 3) 댓글 인덱스 번호
          'comment_content' => $row_comment['comment_content'], // 4) 댓글 내용
          'comment_flag' => 0, //13)댓글 조회 구분  //나의 댓글과 다른 사람의 댓글을 10개안에 다 담은 경우 체크
          'created_date' => $row_comment['created_date'], // 6) 댓글 작성날짜
          'user_nickname' => $row_comment['user_nickname'],  // 7) 작성자 닉네임
          'user_img_url'=> $row_comment['user_img_url'], // 8) 작성자 프로필 사진
          'comment_rowCnt'=> $comment_cnt_all, // 9) 댓글 전체 갯수
          'reply_rowCnt'=> $reply_cnt, // 10) 답글 전체 갯수
          'message' => "댓글조회성공",  // 11) 클라이언트에 보낼 메세지 (저장, 수정, 삭제)
        )); 
      }

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    // JSON 변환
    $json_array = json_encode(array("ar_comment"=>$ar_comment), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // 클라이언트에 전달
    echo $json_array;
}
    ?>