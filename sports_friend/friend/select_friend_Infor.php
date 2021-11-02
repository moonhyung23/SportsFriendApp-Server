<?php 
    // ! 나의 친구 신청 정보 조회 php 파일

if(($_SERVER['REQUEST_METHOD'] == 'POST')){
    include_once "../dbcon.php";
    // 페이징에 필요한 데이터 (PHP코드)
    $page = $_POST["page"]; //페이지 번호 
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수
    //나의 회원정보 idx 번호
    $user_idx = $_POST['user_idx']; 
    
    //페이징 시작 번호 
    $page_start = ($page -1) * $limit;
    // *전체 페이징 할 로우 개수 조회
    $sql_select = "SELECT * FROM FRIEND WHERE my_idx = $user_idx" ;
    $result_select = mysqli_query($con, $sql_select);
    //전체 페이징할 로우 개수
    $paging_cnt_all = mysqli_num_rows($result_select);


    //조회 할 친구가 없는 경우
    if($paging_cnt_all == 0){
        echo "친구없음";
        exit();
    }

    // * 페이징할 데이터가 없는 경우
    //페이징 시작번호 >= 전체 페이징할 개수
    if($page_start >= $paging_cnt_all){
        echo "페이징할데이터없음";
        exit();
    }


    //서로 일치하는 컬럼이 있는 행만 조회 
    // * JOIN 조건
    // 1) 친구 신청 목록 테이블(FRIEND) 사용자 정보 테이블(USERS) Join
    // 2) friend_idx컬럼과 user_idx 컬럼이 같은 것을 조회
    // 3) WHERE -> my_idx 컬럼이 나의 회원정보 컬럼($user_idx)과 같은 것을 조회
    $sql_select = "SELECT * 
    FROM FRIEND  AS A
    INNER JOIN USERS AS B
    ON A.friend_idx = B.user_idx
    WHERE A.my_idx = $user_idx
    ORDER BY f_auto_idx ASC
    LIMIT $page_start, $limit";  
    $result_select = mysqli_query($con, $sql_select);
    
    $ar_friend = array();
        // 2) 테이블 행의 갯수만큼 반복(fetch_assoc: 연관ar_friend)
        while($row_friend = mysqli_fetch_assoc($result_select)){

              //*  주소정보Json -> 배열로 변환 후 
        // 배열의 요소 String변수에 저장
        $user_addr = json_decode($row_friend['user_addr']);
        
            array_push($ar_friend, 
            array(
            'user_nickname' =>$row_friend['user_nickname'], //닉네임
            'user_addr'=>$user_addr[0], //거주동네
            'user_img_url'=>$row_friend['user_img_url'], //프로필사진
            'created_date'=>$row_friend['created_date'], //친구신청 보낸 날짜
            'auto_idx'=>$row_friend['f_auto_idx'], //table idx 번호
            'friend_idx'=>$row_friend['friend_idx'], //친구의 idx 번호
            'all_friend_cnt' => $paging_cnt_all, //전체 등록된 친구의 개수
            'message'=> "내친구정보조회성공" //메세지
            ));  
    }

    //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    
    // JSON 변환
    $json_array = json_encode(array("json_friend"=>$ar_friend), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    
    // 클라이언트에 전달
    echo $json_array;
    
}


?>