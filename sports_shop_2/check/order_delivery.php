<!-- 배송처리 관련 php 파일 -->

<?php 
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";



/* POST로 온 배송 상태 처리번호를 받는다 
   -배송 상태 처리번호의 따라 POST로 받아오는 
   데이터가 다르다.
*/
$delivery_check = $_POST['delivery_check'];

/*1. 배송 상태 구분하기 및 삭제 */

/* 1-1)배송 시작($delivery_check == 1번) */
if($delivery_check == 1){

/* POST로온 JSON 배열 받기
  -주문번호(JSON)
  -운송장 번호(JSON) */
$delivery_Json = $_POST['delivery_Json']; //주문번호 json 배열 
$transPort_Json = $_POST['transport_Json']; //운송장  번호 json 배열

// 1-2)JSON => 배열로 변환할 배열을 선언
$delivery_num_ar = array();
$transport_num_ar = array();

//1-3)JSON => 배열 변환
$delivery_num_ar =  json_decode($delivery_Json); //주문번호 배열
$transport_num_ar =  json_decode($transPort_Json); //운송장 번호 배열


/* 1-4)배송상태 수정, 운송장 번호 추가
 - 0(주문 준비중) => 1(배송 중)
*/
for ($i = 0; $i < count($delivery_num_ar); $i++){
  // 1-5)주문내역 테이블에서 주문번호와 맞는 행의 
    //   -주문상태(배송 중) 컬럼 수정.
    //   -입력한 운송장 번호를 추가
  $sql_update="UPDATE OrderList
  SET delivery_num = 1,
      transport_num = '$transport_num_ar[$i]'
  WHERE order_num = '$delivery_num_ar[$i]' "; 
  $result_update = mysqli_query($con,$sql_update);
}

if($result_update === true){ // 결과 확인 코드
  echo '<script>
  alert("선택하신 상품의 배송이 시작되었습니다.");
  history.back();
  </script>';
}else {
  //결과가 실패일때 실행되는 코드
    echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
} 
  }



  /* 2.배송 취소 ($delivery_check == 0) */
  else if($delivery_check == 0){
    //주문번호 배열 선언
    $delivery_num_ar = array();
    //Post로 주문번호를 담고있는 Json배열을 받아온다
    $delivery_Json = $_POST['delivery_Json'];
    //Json배열 => 배열 변환
    $delivery_num_ar = json_decode($delivery_Json);
    //배열의 갯수만큼 반복
  for ($i = 0; $i < count($delivery_num_ar); $i++){
  /* 배열에 담긴 주문번호와 같은 행만 수정
     *** 수정할 컬럼 ***
     -운송장 번호(transport_num) 초기화
     -배송처리번호(delivery_num) 0번(주문 준비 중)
  */
    $sql_update = " UPDATE OrderList
      SET delivery_num = 0,
          transport_num = ''
      WHERE order_num = '$delivery_num_ar[$i]' "; 
      $result_update = mysqli_query($con, $sql_update);

  }
  // 결과 확인 코드
  if($result_update === true){ 
    echo '<script>
    alert("선택하신 상품의 배송이 취소되었습니다.");
    history.back();
    </script>';
  }else {
    //결과가 실패일때 실행되는 코드
      echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
  }  


  }
  
  /* 3.배송 완료 ($delivery_check == 2) */
  else if($delivery_check == 2){
      //Post로 주문번호를 담고있는 Json배열을 받아온다
      $delivery_Json = $_POST['delivery_Json'];
      //Json배열 => 배열 변환
      $delivery_num_ar = json_decode($delivery_Json);
  for ($i = 0; $i < count($delivery_num_ar); $i++){
      //주문번호와 맞는 행의 배송처리 번호를 2번(배송 완료)으로 변경
      $sql_update = " UPDATE OrderList
      SET delivery_num = 2
      WHERE order_num = '$delivery_num_ar[$i]' "; 
      $result_update = mysqli_query($con, $sql_update);
    // 결과 확인 코드
    if($result_update === true){ 
      echo '<script>
      alert("선택하신 상품이 배송완료 처리되었습니다.");
      history.back();
      </script>';
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_update."<br>mesage".mysqli_error($con)."<br>";
    } 

    }
  

  }
  /* 4.주문 내역 삭제 ($delivery_check == 3) */
  else if($delivery_check == 3){
     //Post로 주문번호를 담고있는 Json배열을 받아온다
     $delivery_Json = $_POST['delivery_Json'];
     //Json배열 => 배열 변환
     $delivery_num_ar = json_decode($delivery_Json);

     //주문번호 배열의 갯수만큼 반복(for)
     for ($i = 0; $i < count($delivery_num_ar); $i++){
    //주문번호와 같은 행을 주문내역에서 삭제
    $sql_delete = "DELETE from OrderList WHERE order_num = '$delivery_num_ar[$i]'";
    $result_delete = mysqli_query($con, $sql_delete);
    
    if($result_delete === true){ 
      echo '<script>
      alert("선택하신 상품이 주문내역에서 삭제되었습니다.");
      history.back();
      </script>';
    }else {
      //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_delete."<br>mesage".mysqli_error($con)."<br>";
    }
  }
    


    }






  



?>