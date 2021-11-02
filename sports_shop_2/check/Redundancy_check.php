<!-- 회원 email 중복체크 -->
<?php 

include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";


	//회원가입화면에서 입력한 id를 uid변수에 저장
	$email = $_GET['email'];
	
    // 입력한 이메일과 같은 행이 있는지 찾는다.
    $sql = "SELECT * FROM User WHERE email ='$email'";
    $result = mysqli_query($con, $sql);

    //같은 행이 없는 경우(중복X)
   if(mysqli_num_rows($result) == 0){
    ?>
	<div style='font-size:50px; font-family:"malgun gothic";';>
    <?php echo $email; ?>은 사용가능한 아이디입니다.</div>
<?php 
   }
	/* 같은 행이 있는 경우(중복O) */
    else{
 ?>
    <div style='font-size:50px; font-family:"malgun gothic"; color:black;'>
    <?php echo $email; ?>은 중복된 아이디입니다.<div>
    <?php    
    }
?>
<button style="font-size: 50px; margin:15px;" value="닫기" onclick="window.close()">닫기</button>
