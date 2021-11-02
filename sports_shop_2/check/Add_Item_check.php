<!-- 항상 맨위에 입력해야 한다. -->
<script src="../js_file/Global.js"></script>

<?php
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

$img_url_ar = array(); //이미지 경로를 모아놓은 배열

//2.세션 설정
session_start();
$id = $_SESSION['id'];
settype($id, 'integer');

//3.입력한 아이템 정보 post로 받아오기
$item_name = $_POST['name']; //상품 이름
$item_category = $_POST['category'];//상품 카테고리
//보호대를 등록한 경우에만 세부 카테고리 가져오기
if(isset($_POST['category_detail'])){
  $item_category_detail = $_POST['category_detail'];//상품 세부 카테고리
}else{
  $item_category_detail = ""; 
}
/* 상품 정보
   addslashes()문자열안에 '(따옴표)가 있을 때 따옴표에 \를 붙여주는 메서드 이다.
*/
$item_content = addslashes($_POST['content']); 
$item_cost = $_POST['cost']; //상품 가격
$item_count =$_POST['count'];//상품 수량





//4.등록한 이미지의 지정한 디렉토리 경로에 업로드(저장)
//4-1)등록한 이미지가 있을 때만
if (isset($_POST['submit']) && isset($_FILES['item_image'])) {
  //4-2)등록한 이미지 정보
   $img_name = $_FILES['item_image']['name'];
   $img_size = $_FILES['item_image']['size'];
   $tmp_name = $_FILES['item_image']['tmp_name'];
   $error = $_FILES['item_image']['error'];
   //4-3)이미지 에러 검사  
if ($error === 0) {
  //4-4)사이즈 검사
  if ($img_size > 500000) {
    $em = "Sorry, your file is too large.";
      header("Location: ../page/Add_Edit_Item.php?error=$em");
  }else {
    //4-5)파일 확장자 검사
    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);//확장자 저장
    $img_ex_lc = strtolower($img_ex);
    //파일 지원하는 확장자 형식 
    $allowed_exs = array("jpg", "jpeg", "png"); 
    //지원 형식과 맞는 이미지인 경우
      if (in_array($img_ex_lc, $allowed_exs)) {
      //4-6)등록한 이미지의 이름  
       $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
      //4-6)이미지 업로드 url 생성
      $img_upload_path = '../image_files/'.$new_img_name;

      //4-7)지정한 디렉토리 경로에 이미지 업로드(저장)
      move_uploaded_file($tmp_name, $img_upload_path);
      
      /*** 컬럼 정보
       check_num_A 상품의 배송 상태 체크
       0: 배송 전
       id: 관리자의 id */

      //5.상품정보 db에 저장
      //이미지 경로($img_upload_path)가 아닌 
      //이미지 이름($new_img_nmae)을 저장해야 한다.
      $sql_insert = "INSERT INTO Add_Item 
      (id, 
      item_name_A, 
      content, 
      category, 
      img_url, 
      cost,  
      count, 
      check_num_A,
      category_detail, 
      created
      ) 
      values(
              $id,        
             '$item_name',
             '$item_content',
             '$item_category',
             '$new_img_name',
              $item_cost,
              $item_count,
              0,
              '$item_category_detail',
              NOW()
              )";

    $result = mysqli_query($con, $sql_insert);
  
    //5-1)상품정보 db에 저장 성공
        if($result == true){
         ?> <script>
/* 상품 등록완료 다이얼로그 
            1)yes => 추가 등록 => 상품 등록페이지로 이동
            2)No =>  등록 취소 => 등록한 상품목록 페이지로 이동
         */
alert_yes_No("상품이 등록되었습니다. 추가로 등록하시겠습니까?",
    "../page/Add_Edit_Item.php",
    "../page/Add_Item_List.php"
);
</script>
<?php
        }else{ ?>
//5-2)상품정보 db에 저장 실패
<script>
history_back("상품 등록 실패");
</script>
<?php
         }
    }else {
      //5-3)이미지 타입 에러
      ?>
<script>
history_back("이미지 타입 에러.");
</script>
<?php }
  }
    }else {//5-4)이미지 크기 에러
    ?>
<script>
history_back("이미지 크기 에러.");
</script>
<?php
    }
    }


//이미지 경로를 모아놓은 배열을 json 형식으로 변형 해주는 메서드
function json_encode_img_ar($img_ar){
  //DB에서 가져온 행을 JSON으로 변환 후 출력
  header('Content-Type: application/json; charset=utf8');
  //1.JSON 변환
  $json_array = json_encode(array("배열키"=>$img_ar), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    return $json_array;
}
?>