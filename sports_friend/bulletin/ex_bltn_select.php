<?php
  //현재시간 생성
    $date = date("Y-m-d H:i:s");

// ! 게시 글 정보 조회 PHP 파일 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once "../dbcon.php";

     //모집 글 배열 
     $ar_bulletin = array(); 

    $page = $_POST["page"]; //페이지 번호 
    $limit = $_POST['limit']; //한 페이지에 보여줄 모집 글 개수

    $bulletin_count = $limit; 
    $bulletin_start = ($page -1) * $bulletin_count;
   
    //전체 모집 글의 개수 구하기
    $sql_select_all = "SELECT * FROM Bulletin"; 
    $result_select_all = mysqli_query($con, $sql_select_all);
    $row_cnt = mysqli_num_rows($result_select_all);
   
    //전체 모집 글의 개수와 모집 글의 시작번호가 같은 경우
    if($row_cnt == $bulletin_start){
        echo "모집글없음";
        exit();
    }

       $sql_select = "SELECT * FROM Bulletin  ORDER BY bltn_idx DESC LIMIT $bulletin_start, $bulletin_count"; 
       $result_select = mysqli_query($con, $sql_select);
       $row_cnt = mysqli_num_rows($result_select);
       

       //사용자의 관심운동과  컬럼이 같은 모집글의 개수 만큼 반복 
       while($row_bltn = mysqli_fetch_assoc($result_select)){
         global $ar_bulletin;
         //모집글 배열에 추가   
         array_push($ar_bulletin, 
             array(
               'user_idx' =>$row_bltn['user_idx'], 
               'bltn_idx'=>$row_bltn['bltn_idx'],
               'bltn_title'=>$row_bltn['bltn_title'],
               'bltn_content'=>$row_bltn['bltn_content'],
               'bltn_img_url'=>$row_bltn['bltn_img_url'],
               'bltn_exer'=>$row_bltn['bltn_exer'],
               'bltn_addr'=>$row_bltn['bltn_addr'],
               'bltn_flag'=>$row_bltn['bltn_flag'],
               'created_date'=>$row_bltn['created_date']
               )); 
         }

        //DB에서 가져온 행을 JSON으로 변환 후 출력
    header('Content-Type: application/json; charset=utf8');
    
    //JSON 변환
    $json_array = json_encode(array("ar_bulletin"=>$ar_bulletin), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    
    // 클라이언트에 전송
    echo $json_array;
        }

            
            
        
        
       
      
          
          
        
        
          
      
          
          
        