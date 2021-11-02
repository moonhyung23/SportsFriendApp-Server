<?php 
/* 함께 아는 친구 정보 조회 */

//1.db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
$f_infor_ar = array(); //함께아는 친구의 정보 배열

$other_f_id_ar = $_POST['other_f_id_ar'];
//1.함께아는 친구 id json문자열을 배열로 변환 
$array_data = json_decode($other_f_id_ar, true);


//2.함께아는 친구의 정보를 배열에 저장한다.
for ($i = 0; $i < count($array_data); $i++){
$f_id = $array_data[$i]['other_f_id'];
//integer 형변환
settype($f_id, 'integer');

//2-1)함께아는 친구의 id로 정보를 조회
$sql_select = "SELECT * FROM user where id = $f_id LIMIT 1000"; 
$result_select = mysqli_query($con, $sql_select);

//2-3)정보를 배열에 저장
if(mysqli_num_rows($result_select) > 0){
    while($row = mysqli_fetch_assoc($result_select)){
         array_push($f_infor_ar, 
          array(
            'f_id' =>$row['id'], 
            'f_nickname'=>$row['nickname'],
            'f_img_profile'=>$row['img_profile']
            )); 
       }
}
}
//2-4)배열을 json형식으로 변환
header('Content-Type: application/json; charset=utf8');
$json_array = json_encode(array("f_infor"=>$f_infor_ar), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

//2-5)클라이언트에 응답
echo $json_array;
}

?>
