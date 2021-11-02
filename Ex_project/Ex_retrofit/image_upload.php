<?php
header("Content-type:application/json");
require_once 'example_con.php';
// require_once '../';
// require_once 'http://3.37.253.243/Ex_project/Ex_Image/IMG-60fd1cfec9a180.69019269.png';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $size = $_POST['size'];

  for ($i = 0; $i < $size; $i++){
    $img_name = $_FILES[$i]['name'];
   //등록한 이미지 정보
   $img_size = $_FILES[$i]['size'];
   $tmp_name = $_FILES[$i]['tmp_name'];
   $error = $_FILES[$i]['error'];
  
//    $userId = $_POST['id'];
 /*   echo "$userId";
   exit(); */
   
//에러가 없는 경우에만 
   if ($error === 0) {
   /*  //사이즈 검사 
    if ($img_size > 1000000) {
        $em = "Sorry, your file is too large.";
          echo "17";
          exit();
        } */
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);//확장자 저장
        $img_ex_lc = strtolower($img_ex);
        //파일 지원하는 확장자 형식 
        $allowed_exs = array("jpg", "jpeg", "png"); 
    //지원 형식과 맞는 이미지인 경우
    if (in_array($img_ex_lc, $allowed_exs)) {
      //4-6)등록한 이미지의 이름  
       $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
        //4-6)이미지 업로드 url 생성
        $img_upload_path = '../Ex_Image/'.$new_img_name;
        //4-7)지정한 디렉토리 경로에 이미지 업로드(저장)
        if(move_uploaded_file($tmp_name, $img_upload_path)){
          echo "$new_img_name";
           }else{
            echo "실패";
           }
          }else{
           echo "파일없음";
          }
  }
}
}

// echo json_encode(array("id" => "$id" , "pwd" => "$pwd", "nick" => "$nick"));