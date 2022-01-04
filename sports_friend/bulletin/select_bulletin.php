  <?php 

  // ! 게시 글 정보 조회 PHP 파일 
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
  include_once "../dbcon.php";
    //모집 글 배열 
    $ar_bulletin = array();
    
    // Post로 데이터를 갖고온다.

    //모집 글 조회 번호
    //1번 -> 거주지역, 관심지역 조회
    //2번 -> 거주지역  조회
    //3번 -> 관심지역  조회
  $select_flag = $_POST['select_flag']; //1)모집 글 조회 번호
  $live_addr = $_POST['live_addr']; //2)거주지역

  // 관심지역이 있는 경우에만 Post받기
  if(!empty($_POST['interest_addr'])){
  $interest_addr = $_POST['interest_addr']; //3)관심지역 
  }

  //1번 -> 거주지역 + 관심지역 모집 글 조회
  if($select_flag == 1){
  $sql_select = "SELECT * FROM Bulletin 
  WHERE bltn_addr = '$live_addr'
  OR bltn_addr = '$interest_addr'
  ORDER BY bltn_idx DESC";
  // 사용자가 선택한 동네에 해당하는 모집글 조회
  // 최근에 작성한 순으로 조회
  // 페이징해서 한번에 10개씩 조회
  $result_select = mysqli_query($con, $sql_select);
  //거주지역 + 관심지역에  모집 글 전체 조회
  select_Bltn($result_select, "전체지역모집글조회");
  }

  //2번 -> 거주지역 
  else if($select_flag == 2 ){
  $sql_select = "SELECT * FROM Bulletin 
  WHERE bltn_addr = '$live_addr'
  ORDER BY bltn_idx DESC";
  $result_select = mysqli_query($con, $sql_select); 
  //거주지역 모집 글 조회
  select_Bltn($result_select, "거주지역모집글조회");
  }

  //3번 -> 관심지역 모집 글 조회
  else if($select_flag == 3 ){
  $sql_select = "SELECT * FROM Bulletin 
  WHERE bltn_addr = '$interest_addr'
  ORDER BY bltn_idx DESC";
  $result_select = mysqli_query($con, $sql_select);
  //관심지역 모집 글 조회
  select_Bltn($result_select, "관심지역모집글조회");
  }

  //DB에서 가져온 행을 JSON으로 변환 후 출력
  header('Content-Type: application/json; charset=utf8');

  //JSON 변환
  $json_array = json_encode(array("ar_bulletin"=>$ar_bulletin), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

  // 클라이언트에 전송
  echo $json_array;
  }

  //모집글의 조회 번호에 따라서 모집글을 조회하는 메서드
  //모집 글 조회 번호
  //1번 -> 동네1, 동네2 조회
  //2번 -> 동네1 조회
  //3번 -> 동네2 조회
  function select_Bltn($result_select, $message)
  {
  global $con;

  //사용자의 관심운동과  컬럼이 같은 모집글의 개수 만큼 반복 
  while($row_bltn = mysqli_fetch_assoc($result_select)){
  //모집글의 댓글 개수를 조회한다
  $sql_select_cnt = "SELECT * FROM COMMENTS WHERE bltn_idx = {$row_bltn['bltn_idx']} "; 
  $result_select_cnt = mysqli_query($con, $sql_select_cnt);
  //모집글의 댓글 개수
  $comment_cnt = mysqli_num_rows($result_select_cnt);

  global $ar_bulletin;
  //모집글 배열에 추가   
  array_push($ar_bulletin, 
      array(
        'user_idx' =>$row_bltn['user_idx'], 
        'bltn_idx'=>$row_bltn['bltn_idx'],
        'bltn_title'=>$row_bltn['bltn_title'],
        'bltn_content'=>$row_bltn['bltn_content'],
        'bltn_img_url'=>$row_bltn['bltn_img_url'],
        'bltn_exer'=>$row_bltn['bltn_exer'],
        'bltn_addr'=>$row_bltn['bltn_addr'],
        'bltn_flag'=>$row_bltn['bltn_flag'],
        'created_date'=>$row_bltn['created_date'],
        'comment_cnt'=>$comment_cnt,
        'message' => $message
        )); 
      }
  }
  ?>