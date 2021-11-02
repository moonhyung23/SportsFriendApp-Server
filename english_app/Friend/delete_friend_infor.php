<?php

if(($_SERVER['REQUEST_METHOD'] == 'POST' )){

    //1.db연결
    include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
    $con = dbconn();

    //2.안드로이드에서 데이터 받아오기
    $my_id = $_POST['my_id'];
    $f_id = $_POST['f_id'];
    //integer 형변환
    settype($f_id, 'integer');
    settype($my_id, 'integer');

    /*  중요!: friend 테이블에서 2번 삭제해야 한다 
     * 1)my_id => my_id  (내 기준)
     * 2)friend_id => my_id  (상대방 기준)
    */

    //3.삭제 sql문 작성(친구 정보 삭제)
    // 3-1) 내 기준 
    $sql_delete="DELETE from friend WHERE my_id = $my_id and friend_id = $f_id";
    $result_delete = mysqli_query($con,$sql_delete);
    // 3-2) 상대방 기준
    $sql_delete="DELETE from friend WHERE my_id = $f_id and friend_id = $my_id";
    $result_delete = mysqli_query($con,$sql_delete);

    // 4. 결과 전송
    if($result_delete === true){ // 결과 확인 코드
        echo "친구삭제성공";
        //결과가 성공일때 실행되는 코드
                                
    }else {
        echo "친구삭제실패";
    }

}


?>