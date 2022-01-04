<?php 
// 클라이언트에서 받은 이미지를 url로 변환해주는 php 파일
// -채팅방에서 생성 시 이미지 url로 변환하기 위해서

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    //등록한 이미지 정보
     $error = $_FILES['image']['error'];
     $img_name = $_FILES['image']['name'];
     $img_size = $_FILES['image']['size'];
     $tmp_name = $_FILES['image']['tmp_name'];

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
        //이미지 경로 클라이언트에 보내기
        echo $new_img_name;
          }else{
           echo "실패";
          }
         }else{
          echo "파일없음";
         }
        }
    ?>