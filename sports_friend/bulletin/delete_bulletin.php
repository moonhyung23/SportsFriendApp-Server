<?php 
// ! 모집 글 삭제 PHP 파일 
include_once "../dbcon.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $bltn_idx = $_POST['bltn_idx']; //모집 글 인덱스 번호

    //모집 글 인덱스 번호와 같은 행을 삭제
    $sql_delete="DELETE from Bulletin WHERE bltn_idx = $bltn_idx ";
    $result_delete = mysqli_query($con, $sql_delete);
    
    // 결과 확인 코드
    if($result_delete === true){ 
        echo "삭제성공";
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }
}
?>