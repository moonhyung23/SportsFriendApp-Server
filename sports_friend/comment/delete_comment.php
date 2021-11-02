<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";
    //Post로 데이터 갖고오기
    $comment_idx = $_POST['comment_idx']; //1)댓글 인덱스 번호
    $bltn_idx = $_POST['bltn_idx']; //2) 모집 글 인덱스 번호



    //댓글 삭제 요청 
    // Join을 사용해서 댓글에 작성된 답글도 같이 삭제한다.
    $sql_delete="DELETE FROM T1, T2 
    USING COMMENTS AS T1 
    LEFT JOIN REPLY AS T2 
    ON T1.comment_idx = T2.comment_idx 
    WHERE T1.comment_idx = $comment_idx";
    $result_delete = mysqli_query($con, $sql_delete);

    // 모집 글의 댓글 개수 조회
    $sql_select = "SELECT * FROM COMMENTS WHERE bltn_idx = $bltn_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    $comment_rowCnt = mysqli_num_rows($result_select);
    
    
    if($result_delete === true){ 
       //결과가 성공일때 실행되는 코드
       $ar_comment = array(); //클라이언트에 보낼 배열 생성
       
       //서버에 보낼 내용 배열에 추가
       array_push($ar_comment, 
       array(
           'user_idx' => "", 
           'bltn_idx' => "", 
           'comment_idx' => "", 
           'comment_content' => "", 
           'comment_flag' => "", 
           'created_date' => "", 
           'user_nickname' => "", 
           'user_img_url'=> "",
           'reply_rowCnt'=> "", // 답글 전체 갯수
           'comment_rowCnt'=> "$comment_rowCnt",
           'message' => '댓글삭제성공'
           )); 

   //DB에서 가져온 행을 JSON으로 변환 후 출력
   header('Content-Type: application/json; charset=utf8');
   // JSON 변환
   $json_array = json_encode(array("ar_comment"=>$ar_comment), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
   // 클라이언트에 전달
   echo $json_array;
    }else 
    {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }
}