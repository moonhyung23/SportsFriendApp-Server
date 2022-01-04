
<?php
// 채팅 방의 채팅내역 조회 
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    $page = $_POST["page"]; //페이지 번호
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수
     $page_start = ($page -1) * $limit; //페이징 시작 번호
    //나의 회원정보 idx 
    $room_uuid = $_POST['room_uuid'];  //채팅방 uuid 
    //1번 -> 노티피케이션 클릭해서 채팅방 입장
    //2번 -> 이미 만들어진 채팅방에서 채팅방 입장
    $flag_notify = $_POST['flag_notify'];  
    
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

  //서로 일치하는 컬럼이 있는 행만 조회 
  // * LEFTJOIN 조건
  // 1) 채팅 방에 맞는 채팅 내역 
  // 2) Chat테이블의 채팅 보낸 사람 idx 
  //   -USERS 테이블의 사용자 idx와 일치하는 로우 조회
    $sql_select = "SELECT *  FROM
      Chat AS A LEFT JOIN USERS AS B 
      ON A.chat_user_idx = B.user_idx
      WHERE A.chat_room_uuid = '$room_uuid'
      ORDER BY chat_id DESC 
     LIMIT $page_start, $limit"
      ;
    $result_select = mysqli_query($con, $sql_select);
    //한 페이지에 조회할 로우(채팅) 개수 조회
    $paging_row = mysqli_num_rows($result_select);

    //채팅을 담을 배열
    $ar_chatInfor = array();

      //1번 -> 노티피케이션 클릭해서 채팅방 입장
  // -채팅내역 + 유저 정보 + 채팅방 정보 조회
    if($flag_notify == 1){
  //채팅방 정보 조회
  $sql_select_chatRoom = "SELECT attend_idx, room_title, room_person_cnt   FROM ChatRoom 
      WHERE room_uuid = '$room_uuid' 
      ";
    $result_select_chatRoom = mysqli_query($con, $sql_select_chatRoom);
    $row_chatRoom = mysqli_fetch_assoc($result_select_chatRoom);
    //채팅방 참가한 유저 idx 모음
    $chat_room_attend_idx = $row_chatRoom['attend_idx'];
    //채팅방 제목          
    $chat_room_title = $row_chatRoom['room_title'];
    //채팅방 참여 인원 수   
    $chat_room_person_cnt = $row_chatRoom['room_person_cnt'];

        // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
        while($row = mysqli_fetch_assoc($result_select)){
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
            'attend_idx'=>$chat_room_attend_idx,
            'room_title'=>$chat_room_title,
            'room_person_cnt'=>$chat_room_person_cnt,
            'all_row' => $paging_cnt_all, //전체 페이징할 로우 개수
            'paging_row' => $paging_row, //한 페이지에 갖고온 로우 개수
            'message'=> '채팅내역조회성공'
            ));
    }
  } 
  //이미 생성된 채팅방에 입장한 경우 
  // -채팅내역 + 유저 정보 조회
  else if ($flag_notify == 2){
        // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
        while($row = mysqli_fetch_assoc($result_select)){
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
    }

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    
    // JSON 변환
    $json_array = json_encode(array("ar_chatInfor"=>$ar_chatInfor), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    
    // 클라이언트에 전달
    echo $json_array;
}


?>