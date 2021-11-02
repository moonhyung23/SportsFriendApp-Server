<?php
//db 연결
error_reporting(E_ALL); 
ini_set('display_errors',1); 
include('db_con.php');
$conn = dbconn();

// 안드로이드에서 로그인에 필요한 데이터를 받아온 경우 
if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
    // 안드로이드에서 보낸 데이터
    $id = $_POST['id'];

    //아이디 int 형변환
    settype($id, 'integer');
    // 내 id와 맞는 회원정보 행을 db에서 쿼리한다.
    $sql  ="SELECT * FROM user WHERE id = $id";
    $result = (mysqli_query($conn, $sql));
    $data = array();
    
    while($row = mysqli_fetch_array($result)){
        //배열의 마지막 값에 데이터를 추가(array_push)
        array_push($data, array(
            'nickname'=>$row["nickname"],
            'img_profile'=> $row['img_profile']
        ));
      }
      
    //  1. 데이터 안드로이드에 전달 실패
    // 배열에 데이터가 없을 때
   if(count($data) == 0){
       echo '실패';
    }else{//2.데이터 안드로이드에 전달 성공
        // json 형식으로 변환해서 안드로이드에 전달한다.
    header('Content-Type: application/json; charset=utf8');
    $json = json_encode(array("myinfor"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json; 
    }
}


?>