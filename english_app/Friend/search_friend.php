<?php 

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();


if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{

    // 검색한 닉네임
    $f_nickname = $_POST['f_nickname'];
    $filter_nickname = mysqli_real_escape_string($con, $f_nickname);


    //1)테이블의 모든 행을 갖고온다.(최대 1000)
    $sql_select = "SELECT * FROM user where nickname LIKE '$filter_nickname%' LIMIT 1000"; 
    $result_select = mysqli_query($con, $sql_select);

    $user_array = array();

// 2)테이블 행이 0이 아닌 경우 
if(mysqli_affected_rows($con) > 0){
    // 3) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관배열)
    while($row = mysqli_fetch_assoc($result_select)){
         extract($row);
         array_push($user_array, 
          array(
            'f_nickname' =>$row['nickname'],
            'f_id' =>$row['id'] 
            )); 
    }
}else{
    echo "databases table 에 데이터 없음";
}  

//DB에서 가져온 행을 JSON으로 변환 후 출력
header('Content-Type: application/json; charset=utf8');
//1.JSON 변환
$json_array = json_encode(array("search_array"=>$user_array), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

// 2.출력
echo $json_array;
}






?>