<?php 

/* 답글 추가 PHP 파일 */
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    //1)post로 입력받은 데이터를 filltered
     //sql injection 데이터 필터링
     $filltered = array(
        //1) 댓글 인덱스 번호
        'cmnt_idx' => mysqli_real_escape_string($con, $_POST['cmnt_idx']),
        //2) 답글 내용
        'reply_content' => mysqli_real_escape_string($con, $_POST['reply_content']),
        //3) 작성자의 인덱스 번호
        'user_idx' => mysqli_real_escape_string($con, $_POST['user_idx']),
        //4) 답글 인덱스 번호
        'reply_idx' => mysqli_real_escape_string($con, $_POST['reply_idx'])
        );

        //답글 삭제
       $sql_delete="DELETE from REPLY WHERE reply_idx= {$filltered['reply_idx']}";
       $result_delete = mysqli_query($con, $sql_delete);
       
       //답글 삭제 성공
       if($result_delete === true){ 
           //결과가 성공일때 실행되는 코드
         //답글 정보 배열
         $ar_reply = array();
         
        //답글 추가 성공 시 작성한 답글 내용 전체 조회하기
        //REPLY 테이블과 USERS 테이블을 JOIN한다.
        // * JOIN 조건
        // 1) user_idx 컬럼이 서로 일치하는 행들만 조회
        // 2) reply_idx 컬럼이 일치하는 행 조회
        // 3) comment_idx 컬럼이 일치하는 행 조회
        $sql_select = "SELECT * FROM REPLY WHERE comment_idx = {$filltered['cmnt_idx']}";
        $result_select = mysqli_query($con, $sql_select);
        //답글 개수 조회
        $reply_cnt = mysqli_num_rows($result_select);
            //답글의 배열에 답글 내용 추가
             array_push($ar_reply, 
              array(
                'user_idx' =>"", //1)작성자 idx
                'comment_idx'=>"", //2)댓글 idx
                'reply_idx'=>"", //3)답글 idx
                'user_nickname'=>"", //4)작성자 닉네임
                'user_img_url'=>"", //5)작성자 프로필 이미지 url
                'reply_flag'=>0, //6)답글 상태번호 
                'reply_content'=>"", //7)답글 내용 
                'reply_created_date'=>"",  //8)답글 작성날짜 
                'reply_cnt_all'=>$reply_cnt,  //9)답글 개수
                'message'=> "답글삭제성공"  //10)메세지   
                ));  

        //DB에서 가져온 행을 JSON으로 변환 후 출력
        header('Content-Type: application/json; charset=utf8');
        // JSON 변환
        $json_array = json_encode(array("reply_json"=>$ar_reply), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        // 클라이언트에 전달
        echo $json_array;
    } else {
        //답글 삭제 실패
          echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
      }
    }



?>