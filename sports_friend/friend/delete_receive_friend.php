<?php 
// ! 받은 친구 신청 취소 php 파일
if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    include_once "../dbcon.php";
   $table_idx = $_POST['table_idx'];
  
   //취소하려는 받은 친구 신청 로우가 있는지 확인
   $sql_select = "SELECT * FROM F_Send_Receive WHERE auto_idx = $table_idx "; 
   $result_select = mysqli_query($con, $sql_select);
   $select_cnt = mysqli_num_rows($result_select);
  
   //이미 보낸 사람이 친구 신청을 취소함 예외1)
   if($select_cnt == 0){
      echo "이미취소됨";
      exit(); 
   }

    //받은 친구 신청 취소
    $sql_delete="DELETE from F_Send_Receive WHERE auto_idx = $table_idx";
    $result_delete = mysqli_query($con, $sql_delete);
    
    if($result_delete === true){ 
       echo "받은친구신청거절성공";
    }
    else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }
}

?>