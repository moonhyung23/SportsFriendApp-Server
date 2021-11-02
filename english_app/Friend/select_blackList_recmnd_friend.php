<?php 
//1.db 연결
//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

$id = $_POST['my_id'];
//integer 형변환
settype($id, 'integer');

/* 블랙리스트 정보 조회 */
$sql_select = "SELECT * FROM user where id = $id LIMIT 1000"; 
$result_select = mysqli_query($con, $sql_select);

if(mysqli_affected_rows($con) > 0){
   $row = mysqli_fetch_assoc($result_select);
    echo  $row['blackList'];
}

}



?>