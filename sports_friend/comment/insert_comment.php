<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    //댓글 정보를 담고있는 배열
    $ar_comment = array();
    
    //1)post로 입력받은 데이터를 $filltered
     //sql injection 데이터 필터링
     $filltered = array(
        //1) 사용자 인덱스 번호
        'user_idx' => mysqli_real_escape_string($con, $_POST['user_idx']),
        //2) 모집 글 인덱스 번호
        'bltn_idx' => mysqli_real_escape_string($con, $_POST['bltn_idx']),
        //3) 댓글 내용
        'comment_content' => mysqli_real_escape_string($con, $_POST['comment_content'])
        );

    //현재 존재하는 모집 글 인지 확인
    $sql_select_bulletin = "SELECT * FROM Bulletin  WHERE bltn_idx = '{$filltered['bltn_idx']}' "; 
    $result_select = mysqli_query($con, $sql_select_bulletin);
    //모집 글이 존재하지 않는 경우 
    if(mysqli_num_rows($result_select) == 0){
        echo "삭제된모집글";
        exit();
    }

    //현재 시간 생성
    $insert_date = date("Y-m-d H:i:s");

    //댓글 정보 Db테이블에 추가 -1)
    $sql_insert = "INSERT INTO COMMENTS (user_idx, bltn_idx, comment_content, comment_flag, created_date)
        VALUES (
    '{$filltered['user_idx']}', 
    '{$filltered['bltn_idx']}', 
    '{$filltered['comment_content']}', 
    0, 
    '$insert_date'
    )";
     //댓글 추가       
    $result_insert = mysqli_query($con, $sql_insert);
   //1)댓글 저장 성공
    if($result_insert === true){ 
    //모집 글  전체 댓글 개수 조회 -2)
    $sql_select_comment = "SELECT * FROM COMMENTS  WHERE bltn_idx = '{$filltered['bltn_idx']}' "; 
    $result_select_comment = mysqli_query($con, $sql_select_comment);
    $count_Allrow = mysqli_num_rows($result_select_comment);


    // ! 댓글 테이블 회원정보 테이블 Join
    // 조건1) 가장 댓글 idx 값이 높은 로우 조회 (가장 최근에 추가한 로우)
    // 조건2) 본인이 작성한 댓글 조회
    $sql_select = "SELECT * 
    FROM COMMENTS AS c
    INNER JOIN USERS AS u
    ON c.user_idx = u.user_idx 
    WHERE c.comment_idx = (select max(comment_idx) from COMMENTS)
    AND c.user_idx = {$filltered['user_idx']}";

    $result_select = mysqli_query($con, $sql_select);
    $row_comment = mysqli_fetch_assoc($result_select);
      //배열안에 배열 넣기 
      array_push($ar_comment, 
      array(
          'user_idx' => $row_comment['user_idx'], 
          'bltn_idx' => $row_comment['bltn_idx'], 
          'comment_idx' => $row_comment['comment_idx'], 
          'comment_content' => $row_comment['comment_content'], 
          'comment_flag' => $row_comment['comment_flag'], 
          'created_date' => $insert_date, 
          'user_nickname' => $row_comment['user_nickname'], 
          'user_img_url'=> $row_comment['user_img_url'], 
          'comment_rowCnt'=> "$count_Allrow", //모집 글 댓글 개수
          'reply_rowCnt'=> "", // 답글 전체 갯수
          'message' => '댓글저장성공' //댓글저장성공 메세지
          )); 
    }
    else {
        //2)댓글 저장 실패
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
    }

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    //1.JSON 변환
    $json_array = json_encode(array("ar_comment"=>$ar_comment), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // JSON 배열 클라이언트에 보내기
    echo $json_array;
    
}


?>