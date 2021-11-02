
<!-- 상품을 삭제하는 php 파일 -->
<?php 

include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//클릭한 상품의 번호를 갖고온다.
$item_num = $_GET['item_num'];
settype($item_num, 'integer');

//상품 번호와 맞는 행을 db에서 삭제
$sql = "delete from Add_Item where item_num_A = $item_num";
mysqli_query($con, $sql);
$result = mysqli_query($con, $sql);

//1)삭제 실패
if($result == false){
    echo '삭제 실패';
    error_log(mysqli_error($con));
}
//2)삭제 성공
else if($result == true){
    /* 뒤로가기 */
 echo '<script> alert("삭제 성공") 
 history.back(); 
 </script>';
echo "{$item_num}";

}
?>

