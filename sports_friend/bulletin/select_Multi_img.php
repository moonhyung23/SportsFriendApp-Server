<?php 
// ! 모집 글 다중이미지 조회하기.

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

    $bltn_idx = $_POST['bltn_idx']; //1)모집 글 번호


    //모집글 다중이미지 url JSON 배열 조회 
    $sql_select = "SELECT * FROM Bulletin WHERE bltn_idx = $bltn_idx "; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);
    
    //다중 이미지 
    echo "{$row['bltn_img_url']}";
}


?>