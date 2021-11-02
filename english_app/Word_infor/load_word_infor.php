 <?php 
//  나의 단어 정보를 불러오는 php 파일

//db 연결
include "/usr/local/apache2.4/htdocs/english_app/db_con.php";
$conn = dbconn();


if(($_SERVER['REQUEST_METHOD'] == 'POST' )){
    //나의 영어단어장 번호를 안드로이드에서 갖고온다.
   $all_id = $_POST['all_id'];
    //json 형식으로 변형할 배열
    $data = array();

    // int 형변환
    settype($all_id, 'integer');
    
    //나의 영어단어 정보 조회
    //전체 테이블 행의 갯수를 구하기 위해서
    $sql_myword_select = "select * from word";
    $result_myword = mysqli_query($conn, $sql_myword_select);
    //전체 영어단어의 행의 갯수만큼 반복! 
    while($row_word = mysqli_fetch_array($result_myword)){
        // all_id와 같은 값이 행만 추가
        // json 형식으로 변환
        if($all_id == $row_word['id']){
            extract($row_word);
            //json 형식으로 변형할 배열에 저장
            array_push($data,
            array('all_id' => $row_word['id'], //전체 단어장 번호
                  'word_name' => $row_word['name'], //단어 이름
                  'word_mean' => $row_word['mean'] //단어 뜻
        ));
        }
    } 

    /* json 형식으로 배열 변환 */
    header('Content-Type: application/json; charset=utf8');
    // 내 uid로된 단어장 정보를 json형식으로 변환 
    $json = json_encode(array("word"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // 안드로이드에 전송
    echo $json;

}

 




 ?>