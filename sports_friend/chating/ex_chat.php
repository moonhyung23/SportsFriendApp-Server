<?php 
// ! 채팅 내역 페이징 예제 파일 
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    $page = $_POST["page"]; //페이지 번호
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수
     //페이징 시작 번호
     $page_start = ($page -1) * $limit;
     $room_uuid = "391e11c6-9358-48fe-aeed-9010728e22f8";
    
    
    
     // *전체 페이징 할 로우 개수 조회
     $sql_select = "SELECT * FROM Chat WHERE chat_room_uuid = '$room_uuid' " ;
     $result_select = mysqli_query($con, $sql_select);
     //전체 페이징할 로우 개수
     $paging_cnt_all = mysqli_num_rows($result_select);

  // * 페이징할 데이터가 없는 경우
    //페이징 시작번호 >= 전체 페이징할 개수
    if($page_start >= $paging_cnt_all){
        echo "페이징할데이터없음";
        exit();
      }


     $sql_select = "SELECT *  FROM
     Chat AS A LEFT JOIN USERS AS B 
     ON A.chat_user_idx = B.user_idx
     WHERE A.chat_room_uuid = '$room_uuid'
     ORDER BY chat_id DESC 
     LIMIT $page_start, $limit"
    ;

    $result_select_paging = mysqli_query($con, $sql_select);
    $paging_row = mysqli_num_rows($result_select_paging);

    $ar_chatInfor = array();

  // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
  while($row = mysqli_fetch_assoc($result_select_paging)){
    array_push($ar_chatInfor, 
      array(
        'chat_id' => $row['chat_id'],
        'chat_user_idx' =>$row['chat_user_idx'], 
        'chat_content'=>$row['chat_content'],
        'chat_uuid'=>$row['chat_uuid'],
        'created_date'=>$row['chat_created_date'],
        'chat_room_uuid'=> $row['chat_room_uuid'],
        'rp_cnt'=> $row['rp_cnt'],
        'chat_sendNickname'=> $row['user_nickname'],
        'chat_userImg_url'=> $row['user_img_url'],
        'chat_viewType' => $row['viewType'],
        'invite_Infor' => $row['invite_Infor'],
        'all_row' => $paging_cnt_all, //전체 페이징할 로우 개수
        'paging_row' => $paging_row, //한 페이지에 갖고온 로우 개수
        'message'=> '채팅내역조회성공'
        ));
}


  //DB에서 가져온 행을 JSON으로 변환 후 출력
  header('Content-Type: application/json; charset=utf8');
    
  // JSON 변환
  $json_array = json_encode(array("ar_chatInfor" => $ar_chatInfor), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  
  // 클라이언트에 전달
  echo $json_array;


 
  
 
   


}

      

?>