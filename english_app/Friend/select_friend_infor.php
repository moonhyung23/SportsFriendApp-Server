<?php 
//나의 친구 정보를 표시하는 php 파일

//1.db 연결
//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

//2. 나의 id를 안드로이드에서 갖고온다.
    $my_id = $_POST['my_id'];
    //integer 형변환
    settype($my_id, 'integer');

//3.나의 my_id 와 같은 행을 friend 테이블에서 갖고온다
//1)테이블의 모든 행을 갖고온다.(최대 1000)
$sql_select = "SELECT * FROM friend WHERE my_id = $my_id LIMIT 1000"; 
$result_select = mysqli_query($con, $sql_select);

$f_user_array = array();

if(mysqli_affected_rows($con) > 0){
    while($row = mysqli_fetch_assoc($result_select)){
    //4. friend_id 컬럼의 값을 갖고온다.
    $friend_id = $row['friend_id'];
    settype($friend_id, 'integer');
    //5. user 테이블에서 friend_id와 같은 행을 갖고온다.
    $sql_select = "SELECT * FROM user WHERE id = $friend_id";
    $result = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_array($result);
    //6. 행의 nickname, img_url을 배열에 저장
         array_push($f_user_array, 
          array(
            'f_id' => $row['id'],
            'f_nickname' =>$row['nickname'], 
            'f_img_profile'=>$row['img_profile']
            )); 
    }
}else{
    echo "databases table 에 데이터 없음";
}

//7.JSON 으로 변환
header('Content-Type: application/json; charset=utf8');
$json_array = json_encode(array("friend_array"=>$f_user_array), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
echo $json_array;


}

?>