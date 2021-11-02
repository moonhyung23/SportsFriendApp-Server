<script src="../js_file/Global.js"></script>
<?php
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";
/*  상품 삭제 처리번호 
    */
    //장바구니 상품번호 (auto_increment) 
    $basket_num = $_POST['basket_num'];
    settype($basket_num, 'integer');
    
    // 상품 번호와 맞는 아이템 삭제
    $sql_delete="DELETE from Basket WHERE basket_num = $basket_num";
    $result_delete = mysqli_query($con, $sql_delete);
    echo 1;
  