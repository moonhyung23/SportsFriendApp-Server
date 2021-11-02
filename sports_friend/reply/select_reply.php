<?php 

/* 답글 목록 전체 조회 PHP 파일 */
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    //페이징에 필요한 데이터
    $page = $_POST["page"]; //페이지 번호 
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수

    //1)post로 입력받은 데이터를 filltered
     //sql injection 데이터 필터링
     $filltered = array(
        //1) 댓글 인덱스 번호
        'cmnt_idx' => mysqli_real_escape_string($con, $_POST['cmnt_idx']),
        //2) 작성자의 인덱스 번호
        'user_idx' => mysqli_real_escape_string($con, $_POST['user_idx'])
        );
      //답글 정보 배열
      $ar_reply = array();
         
      
      //페이징 시작 번호 
      $reply_start = ($page -1) * $limit;
      
      // *모집글의 전체 답글 개수 조회
      //댓글 번호와 맞는 답글 조회하기
      $sql_select = "SELECT * FROM REPLY WHERE comment_idx = {$filltered['cmnt_idx']}" ;
      $result_select = mysqli_query($con, $sql_select);
      //전체 답글 개수 조회
      $reply_cnt_all = mysqli_num_rows($result_select);

      // * 답글이 없는 경우
      //답글의 시작번호 >= 전체 답글의 개수
      if($reply_start >= $reply_cnt_all){
        echo "답글없음";
        exit();
      }

      //답글 추가 성공 시 작성한 답글 내용 전체 조회하기
      //REPLY 테이블과 USERS 테이블을 JOIN한다.
      // * JOIN 조건
      // 1) user_idx 컬럼이 서로 일치하는 행들만 조회
      // 2) reply_idx 컬럼이 일치하는 행 조회
      // 3) comment_idx 컬럼이 일치하는 행 조회
      // 4) 페이징해서 최대 $limit만큼 조회
      $sql_select = "SELECT * 
      FROM REPLY AS r
      INNER JOIN USERS AS u
      ON r.user_idx = u.user_idx 
      WHERE comment_idx = {$filltered['cmnt_idx']}
      ORDER BY reply_idx ASC
      LIMIT $reply_start, $limit";
      
      $result_select = mysqli_query($con, $sql_select);
      //답글 개수 조회
      while($row_reply = mysqli_fetch_assoc($result_select)){
          //답글의 배열에 답글 내용 추가
           array_push($ar_reply, 
            array(
              'user_idx' =>$row_reply['user_idx'], //1)작성자 idx
              'comment_idx'=>$row_reply['comment_idx'], //2)댓글 idx
              'reply_idx'=>$row_reply['reply_idx'], //3)답글 idx
              'user_nickname'=>$row_reply['user_nickname'], //4)작성자 닉네임
              'user_img_url'=>$row_reply['user_img_url'], //5)작성자 프로필 이미지 url
              'reply_flag'=>$row_reply['reply_flag'], //6)답글 상태번호 
              'reply_content'=>$row_reply['reply_content'], //7)답글 내용 
              'reply_created_date'=>$row_reply['reply_created_date'],  //8)답글 작성날짜 
              'reply_cnt_all'=>$reply_cnt_all,  //9)답글 개수
              'message'=> "답글조회성공"  //10)메세지   
              ));  
      } //While문 끝

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    // JSON 변환
    $json_array = json_encode(array("reply_json"=>$ar_reply), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // 클라이언트에 전달
    echo $json_array;
    }
?>