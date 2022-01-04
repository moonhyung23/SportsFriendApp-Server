<?php 
// 클라이언트에서 받은 이미지를 url로 변환해주는 php 파일
// -채팅방에서 이미지 전송 시 사용

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    include_once "../dbcon.php";

    //SQL 인젝션 처리
    $filltered = array(
        //1) 업로드하는 이미지 개수
        'image_cnt' => mysqli_real_escape_string($con, $_POST['image_cnt'])
        );

         //1.업로드하는 이미지가 있는 경우에만
    if($filltered['image_cnt'] != 0){
  //서버에 저장한 이미지 경로 배열
  $ar_imageUri = array();

   //클라이언트에서 가져온 이미지를 담는 배열
  for($i=0; $i < $filltered['image_cnt']; $i++) {

     //등록한 이미지 정보
     $error = $_FILES['image'.$i]['error'];
     $img_name = $_FILES['image'.$i]['name'];
     $img_size = $_FILES['image'.$i]['size'];
     $tmp_name = $_FILES['image'.$i]['tmp_name'];
   

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
        // 클라이언트에 전달
        echo $json_imgUri;
    }else{ 
        echo "보낸이미지 없음";
    }
}

?>