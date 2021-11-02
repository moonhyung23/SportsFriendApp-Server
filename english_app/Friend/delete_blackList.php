<?php 

include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){


    $my_id = $_POST['my_id'];
    //integer 형변환
    settype($my_id, 'integer');
    $receive_id = $_POST['receive_id'];

    //내 정보를 갖고온다.
$sql_select = "SELECT * FROM user where id = $my_id LIMIT 1000"; 
$result_select = mysqli_query($con, $sql_select);

// 2)테이블 행이 0이 아닌 경우 
if(mysqli_num_rows($result_select) > 0){
    $row = mysqli_fetch_assoc($result_select);
$blackList = $row['blackList'];
//스플릿으로 문자열 자른 후 배열에 저장
$split_ar = explode(", ", $blackList);

//배열에서 $receive_id와 같은 인덱스 삭제
for ($i = 0; $i < count($split_ar); $i++){
    if($split_ar[$i] == $receive_id){
    //해당 배열 인덱스 삭제
        unset($split_ar[$i]);
    }
}//for문 종료

//배열 인덱스 재정렬  
//unset으로 삭제해서 인덱스가 깨져있음.
$i = 0;  
foreach($split_ar as $key=>$val)  
{  
   //이전 배열의 키 삭제     
    unset($split_ar[$key]);  

    //새 배열 키로 변경
    $new_key = $i; 
    $split_ar[$new_key] = $val;  
  
    $i++;  
}  



//배열에 요소를 모두 출력해서 문자열에 담는다(블랙리스트)
for ($i = 0; $i < count($split_ar); $i++){
    //형식 맞추기
    //처음 문자열에 저장하는 경우
    if($i == 0){
        $blackList_str = $split_ar[$i];
    }else if($i != 0){
        //이전 값
        $blackList_str = $blackList_str.", ".$split_ar[$i];
    }
}

//문자열을 (블랙리스트) 테이블에 수정
$sql_update="UPDATE user
SET blackList = '$blackList_str'
WHERE id = $my_id ";
$result_update = mysqli_query($con, $sql_update);

if($result_update === true){ // 결과 확인 코드
  echo '삭제성공_블랙리스트';
}else {
  echo '삭제실패_블랙리스트';
}

}//if문 종료(mysqli_num_rows)

}


?>