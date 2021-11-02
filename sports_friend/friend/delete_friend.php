<?php 
// ! 친구 삭제 php 파일
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    //나의 회원정보 idx 번호
    $user_idx = $_POST['user_idx']; 
    //친구의 idx 번호
    $friend_idx = $_POST['friend_idx']; 

    //나와 현재 친구인지 조회한다 (상대방이 먼저 친구를 끊었을 경우를 확인) 예외1)
    $sql_select = "SELECT * FROM FRIEND 
    WHERE my_idx = $user_idx 
    AND friend_idx = $friend_idx
    "; 
    $result_select = mysqli_query($con, $sql_select);
    $row_cnt_friend = mysqli_num_rows($result_select);
    //나와 친구가 아닌 경우 
    if($row_cnt_friend == 0){
      echo "이미삭제된친구";  
      exit();
    }

    // ! 친구 삭제 시 총  2개의 로우를 삭제해야 함.
    // * 1) 나의 친구목록에서 삭제
    // * 2) 상대방의 친구목록에서 삭제 

    //1) 내 친구목록에서 친구 삭제
    $sql_delete_my="DELETE FROM FRIEND 
    WHERE my_idx = $user_idx
    AND friend_idx = $friend_idx";

    $result_delete_my = mysqli_query($con, $sql_delete_my);
    
      // 1-1)내 친구목로에서 삭제 성공
    if($result_delete_my === true){ 
        // 상대방의 친구목록에서 삭제 
        $sql_delete_friend="DELETE FROM FRIEND 
        WHERE my_idx = $friend_idx
        AND friend_idx = $user_idx";

        $result_delete_friend = mysqli_query($con, $sql_delete_friend);
        // 2-1)상대방 친구목록에서 삭제 성공   
        if($result_delete_friend === true){
            echo "친구삭제완료";  
            exit();    
           }
           else{
        //2-2) 상대방 친구목록에서 삭제 실패   
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";  
           }

    }
    else {
      //1-2) 내 친구목로에서 삭제 실패
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }


}

?>