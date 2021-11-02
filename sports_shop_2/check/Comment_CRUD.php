<?php 
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];

    /* Post로 데이터 가져오기  */
    $crud_check = $_POST['crud_check']; //1.댓글 처리번호
    settype($crud_check, 'integer');
     /* curd_check에 따라서 번호가 달라짐
        0번: 댓글 추가 
        1번: 댓글 삭제
    */

    //0번: 추가
    //추가할 때만 댓글 번호를 post로 갖고오지 않는다. 
    if($crud_check == 0){  
    $content = $_POST['content']; //1.댓글 내용
    $bulletin_num = $_POST['bulletin_num']; //2.문의 글 번호
    }
    //1번: 삭제
    else if($crud_check == 1){
    $bulletin_num = $_POST['bulletin_num']; //2.문의 글 번호
    $comment_num = $_POST['comment_num']; //3. 댓글 번호
    settype($comment_num, 'integer');
    }
    
    settype($bulletin_num, 'integer'); 
    


    /* 댓글 작성자의 사용자 정보 조회하기 */
    //1)테이블의 모든 행을 갖고온다.
    $sql_select = "SELECT * FROM User WHERE id = $id "; 
    $result_select = mysqli_query($con, $sql_select);
    $row_user = mysqli_fetch_assoc($result_select);
    $uid = explode("@",  $row_user['email']); 
    

    /* curd_check에 따라서 번호가 달라짐
        0번: 댓글 추가 
        1번: 댓글 삭제
        2번: 댓글 수정
    */
    if($crud_check == 0){ //0 => 댓글 추가
        $sql_insert = "INSERT INTO Comment (id, writter_uid, bulletin_num, content, created) VALUES (
        $id, 
    '$uid[0]', 
        $bulletin_num,
    '$content', 
        NOW()
    )";
    $result = mysqli_query($con, $sql_insert);
    }
    else if($crud_check == 1){ //1 => 댓글 삭제
        $sql_delete="DELETE from Comment 
        WHERE comment_num = $comment_num";
        $result = mysqli_query($con, $sql_delete);
    }

        if($result === false){ 
        //결과가 실패일때 실행되는 코드
        echo "<br>Error".$sql_insert."<br>mesage".mysqli_error($con)."<br>";
        }

?>