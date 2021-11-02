<?php 
// ! 받은 친구 신청 수락 php 파일
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
    // 1)친구 신청을 받은 사람의 idx 번호 (나의 idx번호)
    $my_idx = $_POST['user_idx']; 
    // 2)친구 신청을 보낸 사람의 idx 번호 
    $send_idx = $_POST['send_idx']; 
    // 3)수락하려는 친구 신청 아이템뷰의 Table 인덱스
    $table_idx = $_POST['table_idx'];

    //수락하려는 친구 신청정보 로우가 테이블에 있는지 확인
    $sql_select = "SELECT * FROM F_Send_Receive WHERE auto_idx = $table_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    $row_cnt = mysqli_num_rows($result_select);

    //수락하려는 친구 신청정보 로우가 없는 경우
    if($row_cnt == 0){
        echo "친구신청로우없음";
        exit();
    }
    
    //현재시간 생성
    $date = date("Y-m-d H:i:s");
      
    // * FRIEND 테이블에 저장 
    // 나의 친구목록, 상대방의 친구목록 (2개로우) 저장 

    //1)받은사람  친구목록에 저장 (나)
    $sql_insert_receive = "INSERT INTO FRIEND (my_idx, friend_idx, created_date)
        VALUES (
    $my_idx, 
    $send_idx,
    '$date'
    )";
    $result_insert_receive = mysqli_query($con, $sql_insert_receive);
    
    //2)보낸사람  친구목록에 저장 (상대방)
    if($result_insert_receive === true){ 
    $sql_insert_send = "INSERT INTO FRIEND (my_idx, friend_idx, created_date)
        VALUES (
    $send_idx, 
    $my_idx,
    '$date'
    )";
    $result_insert_send = mysqli_query($con, $sql_insert_send);
    //보낸 사람 친구 목록에 저장 성공
    if($result_insert_send === true){

        // * 친구 신청목록 테이블에서 등록한 친구 로우 삭제
        //친구 신청이 되면 신청목록에서 지운다.
        $sql_delete="DELETE from F_Send_Receive WHERE auto_idx = $table_idx";
        $result_delete = mysqli_query($con, $sql_delete);
        
        if($result_delete === true){ 
            //결과가 성공일때 실행되는 코드
            echo "친구저장성공";
            exit();                         
        }else {
            //결과가 실패일때 실행되는 코드
            echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
        }
        

        }else{
            //보낸 사람 친구목록에 저장 실패
            echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>"; 
            }
        }
        else {
            //받은 사람 친구목록에 저장 실패
            echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
        }
      

}
?>