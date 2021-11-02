<?php 

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$con = dbconn();


if(($_SERVER['REQUEST_METHOD'] == 'POST' ))
{
    /* 나와 친구가 아닌 경우를 표시해주는 번호
     * 1번: 친구 
     * 0번: 친구 x
    */
    $check = 0; 
    $my_f_id_ar = array();//내 친구 리스트
    $all_user_ar = array();//전체 사용자 리스트
    $other_user_ar = array(); //나와 친구가 아닌 사용자 리스트
    $other_friend_ar = array();  //나와 친구가 아닌 사용자의 친구 리스트 
    $other_user_ar_last = array();//나와 친구가 아닌 사용자의 정보(최종)


    //1.나의 아이디를 Post로 갖고온다.
    $my_id = $_POST['my_id'];
    settype($my_id, 'integer');
    /* 2. 나의 친구 배열를 조회한다.*/

    //2-1)전체 친구 테이블을 조회한다
    $sql_select = "SELECT * FROM friend LIMIT 1000"; 
    $result_select = mysqli_query($con, $sql_select);
    //2-2)나의 친구인 행만 배열에 추가
    if(mysqli_affected_rows($con) > 0){
        while($row = mysqli_fetch_assoc($result_select)){
            if($row['my_id'] == $my_id){
             array_push($my_f_id_ar, 
              array(
                'my_id' =>$row['my_id'], 
                'friend_id'=>$row['friend_id']
                )); 
           }
        }
    }
    /* 3.나와 친구가 아닌 사용자의 배열를 조회 */

    //3-1)전체 사용자 테이블을 조회
    $sql_select = "SELECT * FROM user";
    $result_select = mysqli_query($con, $sql_select);
    if(mysqli_affected_rows($con) > 0){
        while($row = mysqli_fetch_assoc($result_select)){
             array_push($all_user_ar, 
              array(
                'id' =>$row['id']
                )); 
        }
    }
    //3-2)전체 사용자 배열($all_user_ar) 나의 친구 배열($my_f_id_ar)을 비교
    //3-3)전체 사용자 배열 조회
    for ($i=0; $i < count($all_user_ar); $i++) { 
        //3-4)전체 사용자 중 나의 아이디만 제외
        if($all_user_ar[$i]['id'] != $my_id){
        //3-5)나의 친구 배열 조회 
    for ($j=0; $j < count($my_f_id_ar); $j++) { 
        //3-6)나와 친구인 경우 check =1
        if($all_user_ar[$i]['id'] === $my_f_id_ar[$j]['friend_id']  ){
            //체크        
            $check = 1;
            }
            /* 나의 친구인 경우 check = 1
             * 나의 친구가 아닌 경우 check = 0;
            */
    }

       //3-7)나의 친구가 아닌 경우  check = 0
       //3-8)나와 친구가 아닌 사용자의 id 배열에 추가
       if($check == 0){
        array_push($other_user_ar, 
      array(
        'your_id' =>$all_user_ar[$i]['id']
        )); 
    }
    //3-9) check 초기화!!(하지 않으면 처음부터 친구인 경우가 됨 !!!중요!!!)
    $check = 0;
}
    }

    //4. 나와 친구가 아닌 사용자의 친구목록 조회(id 이용)
    //4-1)사용자(나와 친구 x)의 배열 조회
    for ($i=0; $i < count($other_user_ar); $i++) { 
        $friend_id_ar = array(); //함께아는 친구의 id를 모아놓은 배열

       $your_id = $other_user_ar[$i]['your_id'];
       settype($your_id, 'integer');
       //4-2)사용자(나와 친구x)의 친구목록 조회(id 이용)
       //내 아이디(your_id) == my_id!!!
       $sql_select = "SELECT * FROM friend where my_id = $your_id LIMIT 1000"; 
       $result_select = mysqli_query($con, $sql_select);
       //4-3)사용자(나와 친구x)의 친구가 있는 경우만
    if(mysqli_num_rows($result_select) > 0){
        //4-4)사용자(나와 친구x)의 사용자 id와 사용자의 친구 id 배열에 추가
        while($row = mysqli_fetch_assoc($result_select)){
             array_push($other_friend_ar, 
              array(
                'your_id' =>$row['my_id'], 
                'friend_id'=>$row['friend_id'],
                )); 
        }

    //5.함께아는 친구의 수를 구한다.
    //5-1)나의 친구목록 조회
    for ($j=0; $j < count($my_f_id_ar); $j++) { 
        
    //5-2)사용자(나와 친구x)의 친구목록 조회
        for ($k=0; $k < count($other_friend_ar); $k++) { 
       settype($my_f_id_ar[$j]['friend_id'], 'integer');
       settype($other_friend_ar[$k]['friend_id'], 'integer');
       $friend_id_me = $my_f_id_ar[$j]['friend_id'];
       $friend_id_you = $other_friend_ar[$k]['friend_id'];

       //5-3)내 아이디와 == 사용자의 친구 id가 같은 경우 
        if($friend_id_me == $friend_id_you){
            //함께아는 친구 수: 1증가
            $f_num ++;
            array_push($friend_id_ar, 
              array(
                  //함께아는 친구의 아이디를 추가
                'other_f_id' => $friend_id_me
                )); 
        }
    }
    }
    }//if문 종료


    /* 함께하는 친구의 수 가 있을 때만 배열에 
     * 사용자(나와 친구 x)정보 추가*/

    if($f_num != 0 ){
    //6.사용자(나와 친구x)의 정보를 배열에 저장 후
    //json형식으로 배열을 변환해서 클라이언트에게 응답 
    //6-1)사용자id(your_id)로 사용자 정보를 가져온다
    $sql_select = "SELECT * FROM user where id = $your_id LIMIT 1000"; 
    $result_select = mysqli_query($con, $sql_select);

    //6-2)사용자 정보 배열에 저장
    if(mysqli_num_rows($result_select) > 0){
        $row = mysqli_fetch_assoc($result_select);
             array_push($other_user_ar_last, 
              array(
                'your_id' =>$row['id'], 
                'your_nickname'=>$row['nickname'],
                'your_friend_num'=> $f_num,
                'your_img_profile' => $row['img_profile'],
                'other_f_id' => $friend_id_ar //함께아는 친구의 아이디를 모아놓은 배열
                )); 
    }
    }
    
    //함께아는 친구 수 초기화 
    $f_num = 0;
    //사용자의(나와 친구x) 친구 배열 초기화
    $other_friend_ar = array();
    }//for문 종료


    //6-3)사용자 정보 배열을 json형식으로 변환
    header('Content-Type: application/json; charset=utf8');
    $json_array = json_encode(array("other_friend"=>$other_user_ar_last), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    //6-4)클라이언트에 전송
    echo $json_array;
}



?>