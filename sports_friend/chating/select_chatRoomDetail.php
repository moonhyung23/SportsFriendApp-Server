<?php 
// 채팅 방 정보에서 참여한 친구 정보 조회하기
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    // 조회할 유저 idx
    $select_idx = $_POST['select_idx'];
 
    //문자열을 짤라서  배열로 변환  
    $ar_select_idx = explode('$', $select_idx);
    $sql_select = 'SELECT user_idx, user_nickname, user_img_url
    FROM USERS 
    WHERE user_idx IN (' . implode(',', array_map('intval', $ar_select_idx)) . ')';
  
    $result_select = mysqli_query($con, $sql_select);
    $ar_user = array();
        // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
        while($row = mysqli_fetch_assoc($result_select)){
             array_push($ar_user, 
              array(
                'user_idx' =>$row['user_idx'], 
                'user_nickname'=>$row['user_nickname'],
                'user_img_url'=>$row['user_img_url'],
                'message'=> "사용자정보조회성공"
                )); 
    }

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    
    // JSON 변환
    $json_array = json_encode(array("USER_JSON"=>$ar_user), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    
    // 클라이언트에 전달
    echo $json_array;
    
    
}

?>