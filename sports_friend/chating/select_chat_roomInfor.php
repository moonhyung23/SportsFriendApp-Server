<?php 
// ! 채팅 방  정보  조회
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    
    //나의 회원정보 idx 
    $chat_room_idx = $_POST['chat_room_idx']; 
    
    //1)테이블의 모든 행을 갖고온다.
    $sql_select = "SELECT * FROM ChatRoom WHERE room_idx = $chat_room_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    
    $ar_chatRoomInfor = array();
        // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
        while($row = mysqli_fetch_assoc($result_select)){
              //같은 idx번호가 있는 경우 해당 로우 추가
              array_push($ar_chatRoomInfor, 
              array(
              'attend_idx' =>$row['attend_idx'], 
              'room_idx'=>$row['room_idx'],
              'room_title'=>$row['room_title'],
              'room_person_cnt'=>$row['room_person_cnt'],
              'room_created_date'=>$row['room_created_date'],
              'room_host_idx'=>$row['room_host_idx'],
              'message'=> "채팅방정보조회성공"
              ));  
    }

     //DB에서 가져온 행을 JSON으로 변환 후 출력
     header('Content-Type: application/json; charset=utf8');
     // JSON 변환
     $json_array = json_encode(array("json_ChatRoomInfor" => $ar_chatRoomInfor), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
     // 클라이언트에 전달
     echo $json_array;
    
}
?>