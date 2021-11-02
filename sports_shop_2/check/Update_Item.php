<?php
/* 팔려고 등록한 상품을 수정하는 php 파일 */


include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//2.수정하기 위해 입력한 아이템 정보 post로 받아오기
$item_num =$_POST['item_num'];//1.상품 번호
$item_name = $_POST['name']; //2.상품 이름
$item_category = $_POST['category'];//3.상품 카테고리
$item_category_detail = $_POST['category_detail'];//4.상품 세부카테고리
/* 4.상품 정보
   addslashes()문자열안에 '(따옴표)가 있을 때 따옴표에 \를 붙여주는 메서드 이다.
*/
$item_content = addslashes($_POST['content']); //5.상품 정보
$item_cost = $_POST['cost']; //6.상품 가격
$item_count =$_POST['count'];//7.상품 수량
//6.이미지를 수정한 경우/수정하지 않은 경우를 구분
//6-1)이미지를 수정한 경우
if ($_FILES['item_image']['name'] != "") {
 //등록한 아이템의 이미지 정보
 $img_name = $_FILES['item_image']['name'];
 $img_size = $_FILES['item_image']['size'];
 $tmp_name = $_FILES['item_image']['tmp_name'];
 $error = $_FILES['item_image']['error'];

 //7.이미지 경로 생성
if ($error === 0) {
  //7-1)사이즈 검사
  if ($img_size > 500000) {
    //뒤로가기
    '<script>  history.back(); </script>';
  }else {
    //7-2)파일 확장자 검사
    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);//확장자 저장
    $img_ex_lc = strtolower($img_ex);
    //파일 지원하는 확장자 형식 
    $allowed_exs = array("jpg", "jpeg", "png"); 
    //지원 형식과 맞는 이미지인 경우
      if (in_array($img_ex_lc, $allowed_exs)) {
      //7-3)등록한 이미지의 이름  
       $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
      //7-4)이미지 업로드 url 생성
      $img_upload_path = '../image_files/'.$new_img_name;

      //7-5)지정한 디렉토리 경로에 이미지 업로드(저장)
      move_uploaded_file($tmp_name, $img_upload_path);
      
    
      //8.db에 상품정보 수정
      /* 1.카테고리 2.세부 카테고리 3.이름 4.설명 5.이미지 6.가격 7.수량 */
    $sql_update="UPDATE Add_Item
     SET item_name_A = '$item_name', 
     content = '$item_content',
     category = '$item_category',
     category_detail = '$item_category_detail',
     img_url = '$new_img_name',
     cost = $item_cost,
     count = $item_count 
     WHERE item_num_A = $item_num ";

    $result_update = mysqli_query($con,$sql_update);

    if($result_update === true){ // 결과 확인 코드
        //8-1)수정 성공
        echo '<script> alert("수정성공");
        document.location.href="../page/Add_Item_List.php"; 
        </script>';
    }else {
        //8-2)수정 실패
        echo '<script> alert("수정실패: 데이터베이스 문제"  );
          history.back();
        </script>';
    }
    }else {
      // 이미지 타입 에러
      echo '<script> alert("수정실패: 이미지 타입이 잘못되었습니다."  );
          history.back();
         </script>';
    }
  }
}else {//이미지 크기 에러
  echo '<script> alert("수정실패: 이미지 크기가 큽니다.");
  history.back();
 </script>';
}
}else{
  //6-2)이미지만 수정하지 않는다.
   //9.db에 상품정보 수정
      /* 1.카테고리 2.이름 3.설명 4.가격 5.수량 */
      $sql_update="UPDATE Add_Item
      SET item_name_A = '$item_name', 
          content = '$item_content',
          category = '$item_category',
          category_detail = '$item_category_detail',
          cost = $item_cost,
          count = $item_count 
          WHERE item_num_A = $item_num ";
     
         $result_update = mysqli_query($con, $sql_update);
         if($result_update === true){ // 결과 확인 코드
             //8-1)수정 성공
             echo '<script> alert("수정성공");
             document.location.href="../page/Add_Item_List.php"; 
             </script>';
         }else {
             //8-2)수정 실패
             echo '<script> alert("수정실패: 데이터베이스 문제");
               history.back();
             </script>';
         }
}


?>