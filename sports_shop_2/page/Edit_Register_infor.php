<!-- 회원정보 수정 페이지 -->
<script>

  // 중복된 id를 확인하는 메서드
  function checkid() {
    /* 해당하는 요소의 ID를 선택 후 변수에 담는다.
     * 변수에 값이 있는 경우
     */

    //  1)입력한 이메일
    var email = document.getElementById("email").value;
    // 2)입력한 이메일이 있을 때
    if (email) {
      //3)get방식으로 입력한 이메일을 중복체크 php파일에 전달
      url = "../check/Redundancy_check.php?email=" + email;
      var Width = 1200;
      var Height = 800;
      var popupX = window.screen.width / 2 - Width / 2;
      var popupY = window.screen.height / 2 - Height / 2;
      window.open(
        url,
        "chkid",
        "status=no, height=1000, width=1500, left=" + popupX + ", top=" + popupY
      );
    } else {
      alert("아이디를 입력하세요");
    }
  }

</script>

<?php 

  //db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

    //2.세션 설정
    session_start();
    settype($_SESSION['id'], 'integer');
    $id = $_SESSION['id'];

    // id와 맞는 사용자 정보를 조회한다.
    $sql_select = "SELECT * FROM User WHERE id = $id"; 
    $result_select = mysqli_query($con, $sql_select);
    $row = mysqli_fetch_assoc($result_select);

    /* 배송지 정보(json)을 배열로 변환
    'post_num' => 우편번호
    'address' => 앞주소
    'address_detail' => 상세주소
    */
    $addr_ar = array();
    $addr_ar = json_decode($row['addr'], true);

    $post_num =  $addr_ar['addr']['post_num']; //우편번호
    $addr =  $addr_ar['addr']['address']; //앞주소
    $addr_detail =  $addr_ar['addr']['address_detail']; //상세주소
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 자주 사용하는 자바스크립트 메서드 파일 -->
    <script src="../js_file/Global.js"></script>
    <!-- 부트스트랩5 링크 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- daum 주소검색 api -->
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
     <!-- font awesome -->
     <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css"rel="stylesheet"/>
    <title>회원정보 수정</title>
</head>
<body>
    <article class="container">
      <div class="page-header">
        <div  class="col-md-6 col-md-offset-3">
          <h3 style="font-size: 60px; margin: 50px;">회원정보 수정</h3>
        </div>
      </div>
      <div class="col-sm-6 col-md-offset-3">
        <!-- 회원정보 변경 폼
        -Edit_Register_infor_Chk.php로 이동
         -->
        <form
          method="post"
          action="../check/Edit_Register_infor_Chk.php"
          name="memform"
          style="width: 700px"
          role="form"
        >
          <div class="form-group">
            <label style="font-size: 60px" for="inputName">성명</label>
            <!--1. 성명 -->
            <input
              required
              name="name"
              style="font-size: 60px; width: 1000px"
              type="text"
              class="form-control"
              id="inputName"
              value="<?=$row['name']?>"
              placeholder="이름을 입력해 주세요"
            />
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="InputEmail">이메일 주소</label>
            <!--2. 이메일  -->
            <input
              required
              name="input_email"
              style="font-size: 60px; width: 1000px"
              type="email"
              class="form-control"
              value="<?=$row['email']?>"
              id="input_email"
              placeholder="이메일 주소를 입력해주세요"
            />
            <!-- 2-1)회원 이메일 (기존 이메일)--> 
            <input
              required
              name="email"
              type="hidden"
              value="<?=$row['email']?>"
            />
            
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="inputPassword">비밀번호</label>
            <!--3. 비밀번호 -->
            <input
              required
              name="pw"
              style="font-size: 60px; width: 1000px"
              type="password"
              class="form-control"
              id="inputPassword"
              placeholder="비밀번호를 입력해주세요"
            />
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="inputPasswordCheck"
              >비밀번호 확인</label
            >
            <!--4. 비밀번호 확인 -->
            <input
              required
              name="pw2"
              style="font-size: 60px; width: 1000px"
              type="password"
              class="form-control"
              id="inputPasswordCheck"
              placeholder="비밀번호 확인을 위해 다시한번 입력 해 주세요"
            />
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="inputMobile">휴대폰 번호</label>
            <!--5. 휴대폰번호 -->
            <input
              required
              name="phone_num"
              style="font-size: 60px; width: 1000px"
              type="tel"
              class="form-control"
              value="<?=$row['phone_num']?>"
              id="inputMobile"
              placeholder="-를 제외하고 입력해 주세요"
            />
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="inputtelNO">주소</label>
      <!--   배송지 정보(json)을 배열 key: value
            'address' => 앞주소
            'post_num' => 우편번호
            'address_detail' => 상세주소
             -->
               <!--6. 주소 -->
            <!-- 6-1)우편번호 -->
            <input
              readonly onclick="findAddr()"
              id="member_post"
              required
              name="post_num"
              style="font-size: 40px; width: 1000px"
              type="tel"
              value="<?=$post_num?>"
              class="form-control"
              id="inputtelNO"
              placeholder="주소찾기(클릭)"
            />
            <!-- 6-2)주소 -->
            <input
              readonly onclick="findAddr()"
              id="member_addr"
              required
              name="address"
              style="font-size: 40px; width: 1000px"
              type="tel"
              class="form-control"
              value="<?=$addr?>"
              id="inputtelNO"
              placeholder="주소를 입력해 주세요"
            />
            <!-- 6-3)상세주소 -->
            <input
              required
              name="address_detail"
              style="font-size: 40px; width: 1000px"
              type="tel"
              value="<?=$addr_detail?>"
              class="form-control"
              id="inputtelNO"
              placeholder="상세주소"
            />
          </div>
          <div
            style="font-size: 60px; margin:20px; width: 1000px;"
            class="form-group text-center"
          >
            <button
              style="width: 500px; height: 100px; font-size: 50px"
              type="submit"
              id="join-submit"
              class="btn btn-primary"
            >
              정보 수정<i class="fa fa-check spaceLeft"></i>
            </button>
            <button
              style="width: 500px; height: 100px; font-size: 50px"
              type="submit"
              class="btn btn-warning"
            >
              취소<i class="fa fa-times spaceLeft"></i>
            </button>
          </div>
        </form>
      </div>
    </article>
  </body>
</html>

