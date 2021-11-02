<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php"; 

  
      //1)post로 입력받은 데이터를 filltered
     //sql injection 데이터 필터링
     $filltered = array(
        //1) 사용자 인덱스 번호
        'comment_idx' => mysqli_real_escape_string($con, $_POST['comment_idx']),
        //2) 댓글 내용
        'comment_content' => mysqli_real_escape_string($con, $_POST['comment_content_edit']),
        //3) 모집 글 인덱스 번호
        'bltn_idx' => mysqli_real_escape_string($con, $_POST['bltn_idx'])
    );

      //현재 존재하는 모집 글 인지 확인
      $sql_select_bulletin = "SELECT * FROM Bulletin  WHERE bltn_idx = '{$filltered['bltn_idx']}' "; 
      $result_select = mysqli_query($con, $sql_select_bulletin);
      //모집 글이 존재하지 않는 경우 
      if(mysqli_num_rows($result_select) == 0){
          echo "삭제된모집글";
          exit();
      }

    //댓글 수정시간 생성
    $update_date = date("Y-m-d H:i:s");

    // 모집 글의 댓글 개수 조회
    $sql_select = "SELECT * FROM COMMENTS WHERE bltn_idx = {$filltered['bltn_idx']} "; 
    $result_select = mysqli_query($con, $sql_select);
    $comment_rowCnt = mysqli_num_rows($result_select);

    //댓글 정보 수정
    $sql_update="UPDATE COMMENTS
    SET   comment_content = '{$filltered['comment_content']}',
          created_date = '$update_date'
    WHERE comment_idx = '{$filltered['comment_idx']}'"; 
    $result_update = mysqli_query($con, $sql_update);
    
    if($result_update === true){ 
        //결과가 성공일때 실행되는 코드
            $ar_comment = array(); //클라이언트에 보낼 배열
            
        //배열안에 배열 넣기 
        array_push($ar_comment, 
        array(
            'user_idx' => "", 
            'bltn_idx' => "", 
            'comment_idx' => "", 
            'comment_content' => $filltered['comment_content'], 
            'comment_flag' => "", 
            'created_date' => "$update_date", 
            'user_nickname' => "", 
            'user_img_url'=> "", 
            'comment_rowCnt'=> "$comment_rowCnt", //모집 글 댓글 개수
            'reply_rowCnt'=> "", // 답글 전체 갯수
            'message' => '댓글수정성공'
            )); 
            
    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    // JSON 변환
    $json_array = json_encode(array("ar_comment"=>$ar_comment), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // 클라이언트에 전달
    echo $json_array;
    }else {
        //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
    }
}

?>