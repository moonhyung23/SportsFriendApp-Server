<?php 
// ! 채팅 방 목록 조회
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    
    //나의 회원정보 idx 
    $user_idx = $_POST['user_idx']; 


    //내가 초대된 모든 채팅방 정보를 조회해야 한다.

    //1)테이블의 모든 행을 갖고온다.(채팅 방 정보)
    //-최근에 수정된 순으로 조회
    $sql_select = "SELECT * FROM ChatRoom 
    ORDER BY room_edit_date DESC"; 
    $result_select = mysqli_query($con, $sql_select);

    $ar_ChatRoom = array();
        while($row = mysqli_fetch_assoc($result_select)){
            
            
            //채팅 방 참여자의 인덱스 번호를 배열로 변환한다.
            $ar_attend_idx = explode('$', $row['attend_idx']); 
            //@가 마지막에 있어서 필요없는 마지막 인덱스를 삭제한다
            //배열의 개수 만큼 반복
            for ($i = 0; $i < count($ar_attend_idx); $i++){
            //나의 idx번호와 같은 idx번호를 찾는다
            if($ar_attend_idx[$i] == $user_idx){
                //내가 참여한 채팅방의 방 번호를 변수에 저장
                $room_uuid = $row['room_uuid'];
                //방 번호를 통해서 마지막으로 입력한 채팅 조회
                $sql_select_chat = "SELECT * FROM Chat 
                WHERE chat_room_uuid = '$room_uuid' AND
                viewType != 3
                ORDER BY chat_id DESC
                LIMIT 1"; 

                //마지막 채팅 내용 조회
                $result_select_chat = mysqli_query($con, $sql_select_chat);
                $row_chat = mysqli_fetch_assoc($result_select_chat);
                
                
                //같은 idx번호가 있는 경우 해당 로우 추가
                array_push($ar_ChatRoom, 
                array(
                'attend_idx' => $row['attend_idx'], 
                'room_idx'=> $row['room_uuid'],
                'room_title'=> $row['room_title'],
                'room_person_cnt'=> $row['room_person_cnt'],
                'room_created_date'=> $row['room_created_date'],
                'room_host_idx'=> $row['room_host_idx'],
                'last_chat_content'=> $row_chat['chat_content'], //가장 최근에 입력된 채팅
                'room_edit_date'=> $row['room_edit_date'],
                'room_img_url'=> $row['room_img_url'],
                'message'=> "채팅방목록조회성공"
                ));  
            }
            }
    }
        //DB에서 가져온 행을 JSON으로 변환 후 출력
        header('Content-Type: application/json; charset=utf8');
        // JSON 변환
        $json_array = json_encode(array("json_ChatRoom" => $ar_ChatRoom), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        // 클라이언트에 전달
        echo $json_array;
}
?>