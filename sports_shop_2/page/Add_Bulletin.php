<?php 
//1.db연결
//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";


//세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];


/* 본인의 유저정보 가져오기 */
$sql_select = "SELECT * FROM User WHERE id = $id "; 
$result_select = mysqli_query($con, $sql_select);
$row_user = mysqli_fetch_assoc($result_select);
/* 아이디만 표시 */
$uid =  explode("@", $row_user['email']);



/* 문의 글 수정 
 -Get으로 갖고온 문의 글의 번호가 있을 때만 (수정하는 경우에만)
 -기존에 입력했던 문의 글 정보를 조회한다.
 */
if(isset($_GET['number'])){
    //수정할 문의 글의 번호를 Get으로 가져온다
    $number = $_GET['number'];
    settype($number, 'integer');
     //게시글의 번호와 같은 문의 글(행) 정보를 갖고온다
     $sql_select = "SELECT * FROM Bulletin WHERE number = $number "; 
     $result_select = mysqli_query($con, $sql_select);
     $row_bulletin = mysqli_fetch_assoc($result_select);
    }
    
/* 문의글 작성 */
    else{
    //게시글의 번호와 같은 문의 글(행) 정보를 갖고온다
    $sql_select = "SELECT * FROM Bulletin WHERE number = -1 "; 
    $result_select = mysqli_query($con, $sql_select);
    $row_bulletin = mysqli_fetch_assoc($result_select);
    }
    
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- 부트스트랩5 링크 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous" />
    <!-- css link -->
    <link rel="stylesheet" href="../css_file/add_bulletin.css?ver=1" />
    <title>문의 글 등록/수정</title>
    <script>
        function pw_check() {
            /* 비밀번호 input태그를 갖고온다 */
            let pw = $("#pw");
            /* 체크된 값을 갖고온다 */
            let checkbox = $("#secret_check").prop("checked");
            if (checkbox) {
            // 체크가 되어있으면 활성화
            pw.attr('disabled', false);
            // 비밀번호 입력 필수 O    
            pw.attr('required', true);
            } else {
                // 체크되지 않았으면 비활성화
                pw.attr('disabled', true);
                //비밀번호 입력 필수 X
                pw.attr('required', false);
                // 비밀번호 값 초기화
                pw.val("");
            }
        }
      

    </script>

    <style>
        body {
            padding-top: 80px;
            padding-bottom: 30px;
        }
    </style>
</head>

<body>
    <article>
        <div class="container" role="main">
            <!-- 페이지 타이틀 -->
            <h1 style="font-size: 50px">문의 글 </h1>
            <form class="form_bulletin" action="../check/Bulletin_CRUD.php" name="form" id="form" role="form" method="post">
                <!-- 작성자의 id -->
                <input type="hidden" name="id" value="<?=$id?>">
                <?php 
            // 수정하는 경우에만 게시글 번호 input 태그 생성
            // GET으로 문의 글의 번호를 갖고왔을 때 => 수정 할 때 
            if(isset($_GET['number'])){ ?>
                <input type="hidden" name="number" value="<?=$number?>">
                <?php }
            ?>

                <!-- 문의 글 수정 시 기존에 입력했던 문의 글 정보를 입력한다.  -->
                <!--1. 제목 입력 -->
                <div class="mb-3">
                    <label for="title">제목</label>
                    <input maxlength="30" required type="text" class="form-control" name="title" value="<?=$row_bulletin['title']?>" id="title" 
                    placeholder="제목을 입력해 주세요 (최대 30자)" />
                </div>
                    <input type="hidden" class="form-control" name="writter" value="<?=$uid[0]?>" id="reg_id" />
                <!--3. 내용입력 -->
                <div class="mb-3">
                    <label for="content">내용</label>
                    <textarea id="text-area" required class="form-control" rows="5" name="content" id="content" placeholder="내용을 입력해 주세요"><?=$row_bulletin['content']?></textarea>
                </div>

                <div style="font-size: 40px; text-align:end" >
                        <!-- 글자 수 보여주는 text -->
                        <span id="count">0</span>/ <!-- 현재 글자 수  -->
                        <span id="max-count">0</span> <!-- 최대 글자 수  -->
                        </div>

                <!--4. 비밀글 체크 -->
                <div class="form-check">
                    <input onclick="pw_check();" checked class="form-check-input" id="secret_check" type="checkbox" value="" />
                    <label class="form-check-label" for="flexCheckDefault">
              비밀글
            </label>
                </div>
                <!-- 5.비밀번호 입력 -->
                <div class="mb-3">
                    <label for="tag">비밀번호</label>
                    <input type="password" maxlength="4" class="form-control" name="pw" value="<?=$row_bulletin['pw']?>" id="pw" 
                    placeholder="비밀번호를 입력해 주세요 (최대 4자리)" />
                </div>

                <?php 
                /* GET으로 받아온 문의 글 번호의 유무를 통해 수정 삭제를 구분한다 */
                //  1)글 수정(Get으로 받아온 문의 글 번호 없음)
                if(isset($_GET['number'])){ ?>
                 <div class="div_button">
                    <button type="submit" name="crud_check" value="수정" class="btn btn-sm btn-primary" id="btnSave">
                            수정</button>
                </div>
               <?php }
                //  2)글 작성
                else{ ?>
               <div class="div_button">
                    <button type="submit" name="crud_check" value="작성" class="btn btn-sm btn-primary" id="btnSave">
                            작성</button>
              </div>
                <?php }
                ?>
            </form>
        </div>
    </article>
</body>

</html>
        <script>    
        //입력하는 곳에 keyup, 함수  이벤트를 추가해주고 초기 값을 세팅한다.
        document.getElementById('text-area').addEventListener('keyup', checkByte )

        //글자수를 확인해주는 span태그(현재 글자 수 )
        var countSpan = document.getElementById('count');
        var message = '';
        //최대 허용 바이트
        var MAX_MESSAGE_BYTE = 600;
        //최대 글자 수 
        document.getElementById('max-count').innerHTML = MAX_MESSAGE_BYTE.toString();


        function count(message){
            var totalByte = 0;
            //메세지의 길이만큼 반복
            for (var index = 0;  index < message.length; index ++){
                //하나의 글자 수를 charByteCode로 변환 
                var currentByte = message.charCodeAt(index);
                //한글은 charCode값이 128 이상이므로 +2
                //영어는 +1 
                (currentByte > 128) ? totalByte += 2 : totalByte++;
            }
            return totalByte;
        }
            json

        //총 byte를 확인 후 제한한 byte보다 작으면 그대로 count입력하고 message값을 저장한다.
        //만약 크다면 alert 창을 뛰우면서 이전에 저장한 message값을 입력하고 바이트를 다시 계산하여 입력한다
        function checkByte(){
            //전체 바이트 수를 구한다
            const totalByte = count(event.target.value);

            //전체 바이트가 제한한 바이트 보다 작은 경우 
            if(totalByte <= MAX_MESSAGE_BYTE){
                //현재 글자 수를 알려주는 곳에 입력
                countSpan.innerText = totalByte.toString();
                message = event.target.value;
            }
            else{
                //큰 경우
            alert(MAX_MESSAGE_BYTE + "바이트까지 전송가능합니다.");
            countSpan.innerText = count(message).toString();
            event.target.value = message;        
            }
        }

        </script>

