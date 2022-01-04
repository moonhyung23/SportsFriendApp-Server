<?php
// !내가 작성한 모집 글 정보 조회 PHP 파일

include_once "../dbcon.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $user_idx = $_POST['user_idx']; //사용자 인덱스 번호

// 내가 작성한 모집 글 정보만 모두 갖고온다.
// bltn_flag번호가 0번인 것만 갖고온다.
/* bltn_flag -> 0번: 기본, 1번: 삭제 */
 $sql_select = "SELECT * FROM Bulletin WHERE bltn_flag = 0 && user_idx = '$user_idx'
  ORDER BY bltn_idx ASC";
 $result_select = mysqli_query($con, $sql_select);
 
 $ar_bulletin = array();
     // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
     while($row_bltn = mysqli_fetch_assoc($result_select)){
           //모집글의 댓글 개수를 조회한다
        $sql_select_cnt = "SELECT * FROM COMMENTS WHERE bltn_idx = {$row_bltn['bltn_idx']} "; 
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
            'comment_cnt'=> $comment_cnt
            )); 
 }


 //DB에서 가져온 행을 JSON으로 변환 후 출력
 header('Content-Type: application/json; charset=utf8');
 
 //1.JSON 변환
 $json_array = json_encode(array("ar_my_bulletin"=>$ar_bulletin), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
 
 // 2.출력
 echo $json_array;
}
?>



?>