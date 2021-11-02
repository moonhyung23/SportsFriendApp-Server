<?php 
// ! 보낸 친구 신청 목록 삭제 php 파일



if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

    include_once "../dbcon.php";

    //보낸 친구신청 아이템의 idx 번호
    $table_idx = $_POST['table_idx'];

    //친구 신청을 보냈는지 확인 
    $sql_select = "SELECT * FROM F_Send_Receive WHERE auto_idx = $table_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    $select_cnt = mysqli_num_rows($result_select);
   
    //이미 친구 신청을 취소함 예외1)
    if($select_cnt == 0){
       echo "이미취소됨";
       exit(); 
    }

    //친구 신청 삭제 
    $sql_delete="DELETE from F_Send_Receive WHERE auto_idx = $table_idx";
    $result_delete = mysqli_query($con, $sql_delete);
    
    if($result_delete === true){ 
       echo "친구신청취소성공";
    }
    else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }





}

?>