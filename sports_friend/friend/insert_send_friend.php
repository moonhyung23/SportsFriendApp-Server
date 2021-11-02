<?php

    // ! 친구 신청 보내기 php 파일 
    // 친구 보낸/받은 목록 테이블에 보낸사람 idx에 추가
    if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
        include_once "../dbcon.php";

        // 1)친구 신청을 보내는 사람의 idx 번호 (나의 idx번호)
        $my_idx = $_POST['my_idx']; 
        // 2)친구 신청을 받는 사람의 idx 번호
        $receive_idx = $_POST['user_idx'];


    // * 1)이미 등록된 친구에게 친구 신청을 보낸 경우

    //나의 친구목록에서 친구신청을 받은 사람의 idx 번호를 조회한다
    $sql_select = "SELECT * FROM FRIEND 
    WHERE my_idx = $my_idx 
    AND friend_idx = $receive_idx";

    $result = mysqli_query($con, $sql_select);
    $num_friend = mysqli_num_rows($result);

    //친구신청을 받는 사람의 idx번호가 있는 경우
    if($num_friend != 0){
        echo '등록된친구';
        exit();
    }


    // * 2) 친구신청을 받은 사람이 이미 친구 신청을 보낸 경우 (중복2)
    //ex) 유저 A가 유저 B에게 친구 신청을 보냈는데 유저 B가 이미 친구 신청을 보냈음. 

    //유저 B의 보낸 친구 신청 목록에서 유저 A의  idx번호를 조회한다
    $sql_select = "SELECT * FROM F_Send_Receive 
    WHERE send_idx = $receive_idx
    AND receive_idx = $my_idx";
    $result = mysqli_query($con, $sql_select);
    //친구 신청을 유저 A에게 보낸적이 있는지를 확인한다
    $num = mysqli_num_rows($result);
    //보낸적이 있는 경우 
    if($num != 0){
        echo '이미받음';
        exit();
    }

     // * 3)본인 닉네임 중복검사(중복3)  
     //본인 한테 친구신청을 보낸 경우
     $sql_select_user = "SELECT * FROM F_Send_Receive 
     WHERE send_idx = $my_idx
     AND receive_idx = $my_idx";
     $result_user = mysqli_query($con, $sql_select_user);
     $row_cnt_uesr = mysqli_num_rows($result_user);
     
     if($row_cnt_uesr != 0){
        echo '본인한테보냄';
        exit();
    }
    
    // * 4) 친구신청을 받은 사람이 이미 친구 신청을 보낸 경우 (중복4)
    $sql_redundancy = "SELECT * FROM F_Send_Receive 
    WHERE send_idx = $my_idx 
    AND receive_idx = $receive_idx";

    $result_redundancy = mysqli_query($con, $sql_redundancy);
    $row_cnt_redudancy = mysqli_num_rows($result_redundancy);
    
    if($row_cnt_redudancy != 0){
        echo '이미보냄';
        exit();
    }

    //현재시간 생성
    $date = date("Y-m-d H:i:s");
    
    //친구 신청 보낸 사람 idx 친구 신청 받은 사람 idx 테이블에 추가
    $sql_insert = "INSERT INTO F_Send_Receive (send_idx, receive_idx, created_date)
        VALUES (
        $my_idx, 
        $receive_idx, 
        '$date'
    )";
        
    $result_insert = mysqli_query($con, $sql_insert);

    if($result_insert === true){ 
        //결과가 성공일때 실행되는 코드
        echo '친구신청성공';
    }else {
    //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
    }

    }
    ?>