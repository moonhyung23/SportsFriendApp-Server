<?php 
// ! 모집 글 수정 PHP 파일
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";
   //SQL 인젝션 처리
   $edit_flag = $_POST['edit_flag'];

   //1)이미지를 수정한 경우  1번 
   if($edit_flag == 1){
  
    $filltered = array(
      //1)모집 글 인덱스 번호
      'bltn_idx' => mysqli_real_escape_string($con, $_POST['bltn_idx']),
      //2) 모집 글 제목
      'bltn_title' => mysqli_real_escape_string($con, $_POST['bltn_title']),
      //3) 모집 글 내용
      'bltn_content' => mysqli_real_escape_string($con, $_POST['bltn_content']),
      //4) 관심 운동
      'bltn_addr' => mysqli_real_escape_string($con, $_POST['bltn_addr']),
      //5) 원하는 지역
      'bltn_exer' => mysqli_real_escape_string($con, $_POST['bltn_exer']),
      //6)업로드 하는 이미지의 개수
      'image_cnt' => mysqli_real_escape_string($con, $_POST['image_cnt'])    
      ); 
   }else
   //2)이미지를 수정하지 않은 경우
   {
    $filltered = array(
      //1)모집 글 인덱스 번호
      'bltn_idx' => mysqli_real_escape_string($con, $_POST['bltn_idx']),
      //2) 모집 글 제목
      'bltn_title' => mysqli_real_escape_string($con, $_POST['bltn_title']),
      //3) 모집 글 내용
      'bltn_content' => mysqli_real_escape_string($con, $_POST['bltn_content']),
      //4) 관심 운동
      'bltn_addr' => mysqli_real_escape_string($con, $_POST['bltn_addr']),
      //5) 원하는 지역
      'bltn_exer' => mysqli_real_escape_string($con, $_POST['bltn_exer']),
      ); 
   }
  
   
    //1.업로드하는 이미지가 있는 경우에만
        if($edit_flag == 1){
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
               $img_upload_path = '../app_image/'.$new_img_name; //이미지 경로 
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
          //모집 글 정보 수정 (이미지 추가)
          $sql_update="UPDATE Bulletin
            SET bltn_title = '{$filltered['bltn_title']}',  
                bltn_content = '{$filltered['bltn_content']}',
                bltn_addr = '{$filltered['bltn_addr']}',
                bltn_exer = '{$filltered['bltn_exer']}',
                bltn_img_url = '$json_imgUri'
            WHERE bltn_idx = {$filltered['bltn_idx']} "; // where 조건 설정은 and, or, not, in 연산자 사용
      } 
       //2.업로드하는 이미지가 없는 경우 
      else{ 
    //모집 글 정보 수정 (이미지 X)
    $sql_update="UPDATE Bulletin
      SET bltn_title = '{$filltered['bltn_title']}',  
          bltn_content = '{$filltered['bltn_content']}',
          bltn_addr = '{$filltered['bltn_addr']}',
          bltn_exer = '{$filltered['bltn_exer']}'
      WHERE bltn_idx = {$filltered['bltn_idx']} "; // where 조건 설정은 and, or, not, in 연산자 사용
      }
      //모집 글 수정 쿼리문 
      $result_update = mysqli_query($con, $sql_update);
      if($result_update === true){ 
        //결과가 성공일때 실행되는 코드
        echo "수정성공";
    }else {
        //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
}

}
?>