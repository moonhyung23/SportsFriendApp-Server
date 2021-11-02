<!-- *** 장바구니에 상품을 추가하는 php 파일 *** -->

<!-- 많이 쓰는 js 함수 모음  -->
<script src="../js_file/Global.js"></script>
<?php 
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

 //2.세션 초기화
 session_start();
 settype($_SESSION['id'], 'integer');
 $id = $_SESSION['id'];


 /* 사용자가 선택한 값만 따로 저장
  -나머지는 상품번호를 이용해서 데이터를 등록한 상품 테이블에서 받아온다.
    1)상품 수량
    2)상품 사이즈
 */
  $item_number = $_POST['item_number'];//1)상품 번호
  $item_count = $_POST['item_count'];//2)상품 수량
  //3.상품 사이즈 농구공(undefined)이 아닌 경우만 post
  //농구공인 사이즈가 없음
  if($_POST['item_size'] != null){
    $item_size = $_POST['item_size'];//3)상품 사이즈
  }
  
  settype($item_number, 'integer');
  settype($item_count, 'integer');

/* 추가할 데이터
    -상품 번호
    -사용자 id
    -상품 사이즈
    -상품 수량
    -추가한 날짜
*/
$sql_insert = "INSERT INTO Basket (number, id, size, count, created) VALUES (
 $item_number, 
 $id,
'$item_size',
 $item_count,
 NOW()
)";
$result_insert = mysqli_query($con, $sql_insert);

if($result_insert === true){ 
   ?>
<!-- 성공 -->
<script>
history_back("장바구니에 상품이 추가되었습니다.");
</script>
<?php
}else {
  //결과가 실패일때 실행되는 코드
    echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
}

?>