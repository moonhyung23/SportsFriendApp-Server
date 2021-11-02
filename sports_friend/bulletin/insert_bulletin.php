<?php 
// * 운동 친구 모집 글 추가 php 파일
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    include_once "../dbcon.php";

    // * POST로 모집 글 정보 받아오기. 
    //SQL 인젝션 처리
  $filltered = array(
    //1)작성자 인덱스 번호
    'user_idx' => mysqli_real_escape_string($con, $_POST['user_idx']),
    //2) 모집 글 제목
    'bltn_title' => mysqli_real_escape_string($con, $_POST['bltn_title']),
    //3) 모집 글 내용
    'bltn_content' => mysqli_real_escape_string($con, $_POST['bltn_content']),
    //4) 관심 운동
    'bltn_exer' => mysqli_real_escape_string($con, $_POST['bltn_exer']),
    //5) 원하는 지역
    'bltn_addr' => mysqli_real_escape_string($con, $_POST['bltn_addr']),
    //6) 업로드하는 이미지 개수
    'image_cnt' => mysqli_real_escape_string($con, $_POST['image_cnt'])
    );

    // echo "{$filltered['image_cnt']}";

    //1.업로드하는 이미지가 있는 경우에만
    if($filltered['image_cnt'] != 0){
      //서버에 저장한 이미지 경로 배열
      $ar_imageUri = array();
     
      $ar_image = array();
       for($i=0; $i < $filltered['image_cnt']; $i++) {
       // 7)업로드 해야하는 이미지 받기
        $ar_image[] = $_FILES['image'.$i];
             //배열안에 배열 넣기 
                //등록한 이미지 정보
          $img_name = $_FILES['image'.$i]['name'];
          $img_size = $_FILES['image'.$i]['size'];
          $tmp_name = $_FILES['image'.$i]['tmp_name'];
          $error = $_FILES['image'.$i]['error'];


         $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);//확장자 저장
         $img_ex_lc = strtolower($img_ex);
         //파일 지원하는 확장자 형식 
         $allowed_exs = array("jpg", "jpeg", "png"); 
        //지원 형식과 맞는 이미지인 경우
        if (in_array($img_ex_lc, $allowed_exs)) {
          //4-6)등록한 이미지의 이름  
           $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
            //4-6)이미지 업로드 url 생성
           $img_upload_path = '../app_image/'.$new_img_name;
            //4-7)지정한 디렉토리 경로에 이미지 업로드(저장)
            if(move_uploaded_file($tmp_name, $img_upload_path)){
            //Json으로 변환할 2차원 배열 생성
            array_push($ar_imageUri, 
            array(
              'image'.$i => $new_img_name, 
              ));
              // echo "$new_img_name";
               }else{
                echo "실패";
               }
              }else{
               echo "파일없음";
              }
    }//for문 종료

    header('Content-Type: application/json; charset=utf8');
    //1. (서버)이미지 경로 배열 => JSON형식으로 변환  
    $json_imgUri = json_encode(array("json_imgUri"=>$ar_imageUri), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
      //모지 글 정보 DB테이블에 저장
      $sql_insert = "INSERT INTO Bulletin (user_idx, bltn_title, bltn_content, bltn_exer, bltn_addr, bltn_img_url,  bltn_flag, created_date)
      VALUES (
    '{$filltered['user_idx']}', 
    '{$filltered['bltn_title']}', 
    '{$filltered['bltn_content']}', 
    '{$filltered['bltn_exer']}', 
    '{$filltered['bltn_addr']}', 
    '$json_imgUri',
    0,
      NOW()
    )";
  } 
  else{
     //2.업로드하는 이미지가 없는 경우
    //모지 글 정보 DB테이블에 저장
    $sql_insert = "INSERT INTO Bulletin (user_idx, bltn_title, bltn_content, bltn_exer, bltn_addr, bltn_flag, created_date)
    VALUES (
  '{$filltered['user_idx']}', 
  '{$filltered['bltn_title']}', 
  '{$filltered['bltn_content']}', 
  '{$filltered['bltn_exer']}', 
  '{$filltered['bltn_addr']}', 
  0,
    NOW()
  )";
  }

  //DB 테이블에 저장 요청
    $result_insert = mysqli_query($con, $sql_insert);
    if($result_insert === true){ 
        //결과가 성공일때 실행되는 코드
        echo "저장성공";
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
    }
      
}


?>