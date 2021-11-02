<?php 

include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    $my_id = $_POST['my_id'];
    //integer 형변환
    settype($my_id, 'integer');
    //추가할 아이디(블랙 리스트)
    $delete_id = $_POST['delete_id'];

    //이전에 추가했던 블랙 리스트 조회
    $sql_select = "SELECT * FROM user where id = $my_id LIMIT 1000"; 
    $result_select = mysqli_query($con, $sql_select);
    
    // 2)테이블 행이 0이 아닌 경우 
    if(mysqli_num_rows($result_select) > 0){
      $row = mysqli_fetch_assoc($result_select);
      //이전에 저장된 블랙리스트의 id
      $delete_id_bf = $row['blackList'];
        if($delete_id_bf == "" && $delete_id_bf == null){
            //1)이전에 저장된 값이 없을 때
            $black_List = $delete_id;

        }else{//2)이전에 저장된 값이 있을 때
            $black_List =  $delete_id_bf.", ". $delete_id; 
        }
    
    // db의 데이터를 변경하는 코드 // 쉼표로 , 여러개의 값을 동시에 변경할 수 있다.
    $sql_update="UPDATE user
    SET blackList = '$black_List'
    WHERE id = $my_id "; 

    $result_update = mysqli_query($con, $sql_update);
    
    if($result_update === true){ // 결과 확인 코드
        '삭제성공';
    }else {
        echo '삭제실패';
    }
    
}
  
    
      

}

?>