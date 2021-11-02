<?php 
// ! 모집 글 상세정보 PHP 파일 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";
    $bltn_idx = $_POST['bltn_idx']; //모집 글 인덱스 번호

// * 1) 모집 글 상세정보 조회    
//조건1) bltn_flag번호가 0번인 행을 갖고온다.
/* bltn_flag -> 0번: 기본, 1번: 삭제 */
//조건2) $bltn_idx 번호와 일치하는 컬럼의 행을 갖고온다
$sql_select = "SELECT * FROM Bulletin WHERE bltn_flag = 0 && bltn_idx = $bltn_idx"; 
$result_select = mysqli_query($con, $sql_select);
//모집 글 정보 로우
$row_bulletin = mysqli_fetch_assoc($result_select);


// * 2) 모집 글 작성자 정보 조회
$sql_select = "SELECT * FROM USERS WHERE user_idx = {$row_bulletin['user_idx']} "; 
$result_select = mysqli_query($con, $sql_select);
//모집 글 작성자 정보 로우
$row_user = mysqli_fetch_assoc($result_select);

/* echo "{$row_user['user_nickname']}";
echo "{$row_user['user_img_url']}"; */

$ar_bulletin = array();
    // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
         array_push($ar_bulletin, 
          array(
            'user_idx' =>$row_bulletin['user_idx'], 
            'bltn_idx'=>$row_bulletin['bltn_idx'],
            'bltn_title'=>$row_bulletin['bltn_title'],
            'bltn_content'=>$row_bulletin['bltn_content'],
            'bltn_img_url'=>$row_bulletin['bltn_img_url'],
            'bltn_exer'=>$row_bulletin['bltn_exer'],
            'bltn_addr'=>$row_bulletin['bltn_addr'],
            'bltn_flag'=>$row_bulletin['bltn_flag'],
            'created_date'=>$row_bulletin['created_date'],
            'user_nickname'=>$row_user['user_nickname'],
            'user_img_url'=>$row_user['user_img_url']
            )); 
}

 //DB에서 가져온 행을 JSON으로 변환 후 출력
 header('Content-Type: application/json; charset=utf8');
 
 //1.JSON 변환
 $json_array = json_encode(array("ar_detail_bulletin"=>$ar_bulletin), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
 
 // 2.출력
 echo $json_array;
?>