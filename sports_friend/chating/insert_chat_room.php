<?php 
// ! 채팅 방 추가
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    // 1)나의 인덱스 번호
    $user_idx = $_POST['user_idx']; 
    // 2)초대한 친구 인덱스 번호 (스플릿 해야 함)
    $invite_idx_split = $_POST['invite_idx_split'];
    // 3)초대한 친구  닉네임 (스플릿 해야 함) 
    $invite_nickname_split = $_POST['invite_nickname_split'];


    //문자열을 짤라서  배열로 변환  
    $ar_invite_idx = explode('@', $invite_idx_split);
    //마지막 배열 인덱스 삭제
    array_pop($ar_invite_idx);
    //배열의 개수 구하기 (채팅방에 참가한 사람 수 )
    $invite_idx_count = count($ar_invite_idx);

    //채팅방 작성자의 닉네임을 조회한다.
    $sql_select = "SELECT * FROM USERS WHERE user_idx = $user_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);
    //초대한 친구 닉네임에 작성자의 닉네임 합치기
    $room_nickname = $invite_nickname_split.$row['user_nickname'];

      //현재시간 생성
        $date = date("Y-m-d H:i:s");
      
      // DB테이블에 저장
        $sql_insert = "INSERT INTO ChatRoom (attend_idx, room_title, room_person_cnt, room_host_idx,  room_created_date)
         VALUES (
        '$invite_idx_split', 
        '$room_nickname', 
         $invite_idx_count, 
        '$user_idx', 
        '$date'
        )";
          
    $result_insert = mysqli_query($con, $sql_insert);
    
    if($result_insert === true){ 
        //결과가 성공일때 실행되는 코드
        echo "채팅방추가성공";
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
    }
}
?>