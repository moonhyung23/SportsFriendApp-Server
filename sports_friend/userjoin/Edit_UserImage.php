<?php
// ! 회원 이미지 변경 php 파일
include_once "../dbcon.php";

// *1.이미지 변경
if($_SERVER['REQUEST_METHOD'] == 'POST'){
 //등록한 이미지 정보
 $img_name = $_FILES['upload_image']['name'];
 $img_size = $_FILES['upload_image']['size'];
 $tmp_name = $_FILES['upload_image']['tmp_name'];
 $error = $_FILES['upload_image']['error'];
// *회원정보 인덱스
 $userIdx = $_POST['user_idx'];
    
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
    //4-6)이미지 업로드 경로
    $img_upload_path = '../app_image/'.$new_img_name;
    //4-7)지정한 디렉토리 경로에 이미지 업로드(저장)
    if(move_uploaded_file($tmp_name, $img_upload_path)){
        // db의 데이터를 변경하는 코드 // 쉼표로 , 여러개의 값을 동시에 변경할 수 있다.
          $sql_update="UPDATE USERS
          SET user_img_url = '$new_img_name'
          WHERE user_idx = $userIdx"; // where 조건 설정은 and, or, not, in 연산자 사용
          $result_update = mysqli_query($con, $sql_update);
          
          if($result_update === true){ // 결과 확인 코드
            echo "$userIdx";
          }else {
            //결과가 실패일때 실행되는 코드
              echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
          }
    }else{
        echo "실패";
       }
      }
 }
}

?>