<?php 


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    //검색한 모집 글 정보를 담고있는 배열
    $ar_bulletin = array();
    
     //1)post로 입력받은 데이터를 $filltered
      //sql injection 데이터 필터링
     /* search_flag 번호에 따라서 다르게 쿼리
      1번 -> 동네 입력
      2번 -> 관심운동 입력
      3번 -> 관심운동, 동네 둘 다
      4번 -> 둘 다 입력되지 않은 경우 */
      $filltered = array(
         //1) 검색 키워드
         'search_keyword' => mysqli_real_escape_string($con, $_POST['search_keyword']),
         //2) 검색한 동네
         'search_addr' => mysqli_real_escape_string($con, $_POST['search_addr']),
         //3) 검색한 관심운동
         'search_exer' => mysqli_real_escape_string($con, $_POST['search_exer']),
         //4) search_flag
         'search_flag' => mysqli_real_escape_string($con, $_POST['search_flag'])
         );

         //1번 -> 동네 입력
      if($filltered['search_flag'] == 1 ){
        $sql_select_bltn = "select * from Bulletin 
        WHERE bltn_title LIKE '%{$filltered['search_keyword']}%'
        AND bltn_addr = '{$filltered['search_addr']}'
        ";
      }
      //2번 -> 관심운동 입력
      else if($filltered['search_flag'] == 2){
        $sql_select_bltn = "select * from Bulletin 
        WHERE bltn_title LIKE '%{$filltered['search_keyword']}%'
        AND bltn_exer = '{$filltered['search_exer']}'
        ";
      }
      //3번 -> 관심운동, 동네 둘 다
      else if($filltered['search_flag'] == 3){
        $sql_select_bltn = "select * from Bulletin 
        WHERE bltn_title LIKE '%{$filltered['search_keyword']}%'
        AND bltn_addr = '{$filltered['search_addr']}'
        AND bltn_exer = '{$filltered['search_exer']}'
        ";
      }
      //4번 -> 둘 다 입력되지 않은 경우
      else if($filltered['search_flag'] == 4){
        $sql_select_bltn = "select * from Bulletin 
        WHERE bltn_title LIKE '%{$filltered['search_keyword']}%'";
      }

        $result_select_bltn = mysqli_query($con, $sql_select_bltn);
        // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
        while($row_bltn = mysqli_fetch_assoc($result_select_bltn)){
         //모집글의 댓글 개수를 조회한다
         $sql_select_cnt = "SELECT * FROM COMMENTS WHERE bltn_idx = {$row_bltn['bltn_idx']}"; 
         $result_select_cnt = mysqli_query($con, $sql_select_cnt);
         //모집글의 댓글 개수
         $comment_cnt = mysqli_num_rows($result_select_cnt);

         array_push($ar_bulletin, 
         array(
           'user_idx' =>$row_bltn['user_idx'], 
           'bltn_idx'=>$row_bltn['bltn_idx'],
           'bltn_title'=>$row_bltn['bltn_title'],
           'bltn_content'=>$row_bltn['bltn_content'],
           'bltn_img_url'=>$row_bltn['bltn_img_url'],
           'bltn_exer'=>$row_bltn['bltn_exer'],
           'bltn_addr'=>$row_bltn['bltn_addr'],
           'bltn_flag'=>$row_bltn['bltn_flag'],
           'created_date'=>$row_bltn['created_date'],
           'comment_cnt'=>$comment_cnt
           )); 
        }
        //DB에서 가져온 행을 JSON으로 변환 후 출력
        header('Content-Type: application/json; charset=utf8');
        
        //1.JSON 변환
        $json_array = json_encode(array("ar_bulletin"=>$ar_bulletin), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        
        // 2.출력
        echo $json_array;
}

?>