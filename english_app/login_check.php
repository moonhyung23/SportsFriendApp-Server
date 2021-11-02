<?php 
//db 연결
error_reporting(E_ALL); 
ini_set('display_errors',1); 
include('db_con.php');
$conn = dbconn();

// 안드로이드에서 로그인에 필요한 데이터를 받아온 경우 
if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
//로그인 화면에서 입력한 id와 패스워드를 post로 갖고온다.
$input_uid = $_POST['uid'];
$input_pw = $_POST['pw'];
//json 형식으로 변환시킬 배열
$data = array();


    //입력한 id와 같은 id가 있는 행을 가져온다
    $sql  ="SELECT * FROM user WHERE uid = '$input_uid'";
    $result = (mysqli_query($conn, $sql));
    $array = mysqli_fetch_array($result);
    //갖고온 행의 해시화된 암호를 변수에 저장한다.
    $hash_pwd = $array['pw'];
    //해시 암호와 입력한 암호를 비교한다.
    if(password_verify($input_pw, $hash_pwd)){
        //로그인 성공!
        
        //배열에 내 회원정보를 추가 
        array_push($data, array(
        'id'=>$array["id"],
        'pass'=>'성공'
    ));

        //2.데이터 안드로이드에 전달 성공
        // json 형식으로 변환해서 안드로이드에 전달한다.
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("Login"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json; 

    }else{//로그인 실패
        echo '실패';
    }
}



?>  