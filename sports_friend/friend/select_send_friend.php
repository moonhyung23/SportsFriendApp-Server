<?php 
// ! 내가 보낸 친구 신청 목록 조회 php 파일
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";

    //1)나의 회원 idx 번호
    $my_idx = $_POST['user_idx'];

    //내가 보낸 친구신청 로우를 조회
    $sql_select_send = "SELECT * FROM F_Send_Receive WHERE send_idx = $my_idx ";
    $result_select_send = mysqli_query($con, $sql_select_send);
    
    $ar_send_friend = array();
    while($row_send = mysqli_fetch_assoc($result_select_send)){
        //친구 신청을 받은 사람의 회원정보를 조회한다.
        $sql_select_user = "SELECT * FROM USERS WHERE user_idx = {$row_send['receive_idx']}";  
        $result_select_user = mysqli_query($con, $sql_select_user);
        $row_user = mysqli_fetch_assoc($result_select_user);  

        //*  주소정보Json -> 배열로 변환 후 
        // 배열의 요소 String변수에 저장
        $user_addr = json_decode($row_user['user_addr']);
        $live_addr =$user_addr[0]; //1번: 현재거주지역
        $interest_addr =$user_addr[1]; //2번: 관심지역

            array_push($ar_send_friend, 
            array(
            'user_nickname' =>$row_user['user_nickname'], //닉네임
            'user_addr'=>$user_addr[0], //거주동네
            'user_img_url'=>$row_user['user_img_url'], //프로필사진
            'created_date'=>$row_send['created_date'], //친구신청 보낸 날짜
            'auto_idx'=>$row_send['auto_idx'], //table idx 번호
            'receive_idx'=>$row_send['receive_idx'], //받은 사람 idx
            'message'=> "보낸친구신청목록조회성공" //메세지
            )); 
    }
    
    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    // JSON 변환
    $json_array = json_encode(array("json_friend"=>$ar_send_friend), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    
    // 클라이언트에 전달
    echo $json_array;
    
}


?>