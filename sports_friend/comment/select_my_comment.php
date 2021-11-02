<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";
    //모집 글 댓글 정보 배열
    $ar_comment = array();
    //POST로 클라이언트에서 보낸 데이터를 받아온다
    $bltn_idx = $_POST['bltn_idx']; //1)모집 글 인덱스 번호
    $user_idx = $_POST['user_idx']; //2)회원정보 idx

   /*  //페이징에 필요한 데이터
    $my_page = $_POST["my_page"]; //페이지 번호 
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수
    //페이징 시작 번호 
    $comment_start = ($page -1) * $limit; */

     // ! 본인이 작성한 댓글 정보 조회
     // 조건: 1)모집글의 인덱스번호와 같은 댓글 
     //      2)본인의 인덱스번호와 다른 댓글
     $sql_select_comment_my = "SELECT * 
     FROM COMMENTS AS c
     INNER JOIN USERS AS u
     ON c.user_idx = u.user_idx 
     WHERE c.user_idx = $user_idx
     AND c.bltn_idx = $bltn_idx
     ORDER BY c.comment_idx ASC";

     $result_select_comment_my = mysqli_query($con, $sql_select_comment_my);
     //모집 글의 댓글 개수 조회
     $comment_cnt_my = mysqli_num_rows($result_select_comment_my);
     
    //조회된 모집 글의 댓글 개수가 없는 경우 
    if($comment_cnt_my == 0){
      echo "댓글없음";  
      exit();
    }

    while($row_comment = mysqli_fetch_assoc($result_select_comment_my)){
        
        //댓글의 답글 개수를 조회
        $sql_select = "SELECT * FROM REPLY WHERE comment_idx = {$row_comment['comment_idx']}"; 
        $result_reply = mysqli_query($con, $sql_select);
        //답글 개수 조회
        $reply_cnt = mysqli_num_rows($result_reply);  
    
        //클라이언트에 보낼 작성한 댓글정보 배열
        array_push($ar_comment, 
        array(
            'user_idx' => $row_comment['user_idx'],  // 1) 작성자 인덱스 번호 
            'bltn_idx' => $row_comment['bltn_idx'],  // 2) 모집 글 인덱스 번호
            'comment_idx' => $row_comment['comment_idx'], // 3) 댓글 인덱스 번호
            'comment_content' => $row_comment['comment_content'], // 4) 댓글 내용
            'comment_flag' => $row_comment['comment_flag'], // 5) 댓글 상태번호 플래그
            'created_date' => $row_comment['created_date'], // 6) 댓글 작성날짜
            'user_nickname' => $row_comment['user_nickname'],  // 7) 작성자 닉네임
            'user_img_url'=> $row_comment['user_img_url'], // 8) 작성자 프로필 사진
            'comment_rowCnt'=> 0, // 9) 댓글 전체 갯수
            'reply_rowCnt'=> $reply_cnt, // 10) 답글 전체 갯수
            'message' => '본인댓글조회성공'  // 11) 클라이언트에 보낼 메세지 (저장, 수정, 삭제)
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