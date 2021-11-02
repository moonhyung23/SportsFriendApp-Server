<?php 

/* 답글 추가 PHP 파일 */
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";


    //페이징에 필요한 데이터
    $page = $_POST["page"]; //페이지 번호 
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수
    $reply_start = ($page -1) * $limit;  //페이징 시작 번호 

    //1)post로 입력받은 데이터를 filltered
     //sql injection 데이터 필터링
     $filltered = array(
        //1) 댓글 인덱스 번호
        'cmnt_idx' => mysqli_real_escape_string($con, $_POST['cmnt_idx']),
        //2) 답글 내용
        'reply_content' => mysqli_real_escape_string($con, $_POST['reply_content']),
        //3) 작성자의 인덱스 번호
        'user_idx' => mysqli_real_escape_string($con, $_POST['user_idx'])
        );

        //현재시간 생성
        $date = date("Y-m-d H:i:s");
        
        //답글 정보 DB 테이블에 저장
        $sql_insert = "INSERT INTO REPLY (user_idx, comment_idx, reply_content, reply_created_date)
            VALUES (
        '{$filltered['user_idx']}', 
        '{$filltered['cmnt_idx']}', 
        '{$filltered['reply_content']}',
        '$date'
        )";
            
    $result_insert = mysqli_query($con, $sql_insert);
    
    //답글 추가 성공
    if($result_insert === true){ 
    //댓글의 답글 개수 조회
    $sql_select = "SELECT * FROM REPLY WHERE comment_idx = {$filltered['cmnt_idx']}";
    $result_select = mysqli_query($con, $sql_select);
    //답글 개수 조회
    $reply_cnt = mysqli_num_rows($result_select);

    //답글 정보 배열
    $ar_reply = array();
         
    // ! 답글 테이블 회원정보 테이블 Join
    // 조건1) 가장 답글 idx 값이 높은 로우 조회 (가장 최근에 추가한 로우)
    // 조건2) 본인이 작성한 답글 조회
    $sql_select = "SELECT * 
    FROM REPLY AS r
    INNER JOIN USERS AS u
    ON r.user_idx = u.user_idx 
    WHERE r.reply_idx = (select max(reply_idx) from REPLY)
    AND r.user_idx = {$filltered['user_idx']}";

    $result_select = mysqli_query($con, $sql_select);
    $row_reply = mysqli_fetch_assoc($result_select);
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
        'reply_cnt_all'=>$reply_cnt,  //9)답글 개수
        'message'=> "답글추가성공"  //10)메세지   
        ));  

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    // JSON 변환
    $json_array = json_encode(array("reply_json"=>$ar_reply), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // 클라이언트에 전달
    echo $json_array;
    }
    else {
        //답글 추가 실패
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
    }
    }
?>