<!-- *** 자바스크립트 코드 *** -->
<script>

//페이지이동
function move_page_link(link) {
    document.location.href = link;
}


/* 주소검색 api */
function findAddr(){
  var width = 1500; //팝업의 너비
  var height = 1200; //팝업의 높이

	new daum.Postcode({
    //주소 팝업의 길이와 높이를 지정
        width: width, 
        height: height,
        oncomplete: function(data) {
        	console.log(data);
            // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
            // 도로명 주소의 노출 규칙에 따라 주소를 표시한다.
            // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
            
            /* 검색한 도로명 주소를 변수에 담는다. */
            var roadAddr = data.roadAddress; 
            /* 검색한 지번 주소를 변수에 담는다. */
            var jibunAddr = data.jibunAddress; 
            /* 우편번호 input태그에 입력 */
            document.getElementById('member_post').value = data.zonecode;
            /* 1)도로명 주소가 있는 경우 */
            if(roadAddr !== ''){
            /* 도로명 주소 input태그에 입력 */
                document.getElementById("member_addr").value = roadAddr;
            }
            /* 2)지번 주소가 있는 경우 */ 
            else if(jibunAddr !== ''){
            /* 지번 주소 input태그에 입력 */
                document.getElementById("member_addr").value = jibunAddr;
            }
        }
    }).open({
      /* 주소팝업의 위치를 입력 */
      left: window.screen.width / 2 - width / 2,
      top: window.screen.height / 2 - height / 2,
    });
}


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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>회원가입</title>
   
    <!-- daum 주소검색 api -->
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <!-- Bootstrap -->
    <link href="../plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- font awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css"rel="stylesheet"/>
    <!-- Custom style -->
    <link
      rel="stylesheet"
      href="../plugin/bootstrap/css/style.css"
      media="screen"
      title="no title"
      charset="utf-8"
    />
    <!-- 부트스트랩 -->
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>

  <body>
    <article class="container">
      <div class="page-header">
        <div class="col-md-6 col-md-offset-3">
          <h3 style="font-size: 60px; margin: 50px">회원가입</h3>
        </div>
      </div>
      <div class="col-sm-6 col-md-offset-3">
        <!-- 회원가입 폼 -->
        <form
          method="post"
          action="../check/Register_check.php"
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
              placeholder="이름을 입력해 주세요"
            />
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="InputEmail">이메일 주소</label>
            <!--2. 이메일 주소 -->
            <input
              required
              name="email"
              style="font-size: 60px; width: 1000px"
              type="email"
              class="form-control"
              id="email"
              placeholder="이메일 주소를 입력해주세요"
            />
            <!-- 이메일 중복검사 -->
            <input
              style="
                margin-top: 10px;
                width: 200px;
                height: 80px;
                font-size: 30px;
              "
              class="btn btn-primary"
              type="button"
              value="중복검사"
              onclick="checkid();"
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
              placeholder="비밀번호 입력"
            />
            <h1  style="margin-top:30px; width: 1000px;">비밀번호는 영문, 숫자, 특수문자를 혼합하여 최소 5자리 ~ 최대 20자리 이내로 입력해주세요.</h1>
            
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
              placeholder="비밀번호 재확인"
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
              id="inputMobile"
              placeholder="-를 제외하고 입력해 주세요"
            />
          </div>
          <div class="form-group">
            <label style="font-size: 60px" for="inputtelNO">주소</label>
           
            <!--6. 주소 -->
            <!-- 6-1)우편번호 -->
            <input
              readonly onclick="findAddr()"
              id="member_post"
              required
              name="post_num"
              style="font-size: 40px; width: 1000px"
              type="tel"
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
              id="inputtelNO"
              placeholder="주소를 입력해 주세요"
            />
            <!-- 6-3)상세주소 -->
            <input
              required
              name="address_detail"
              style="font-size: 40px; width: 1000px"
              type="tel"
              class="form-control"
              id="inputtelNO"
              placeholder="상세주소"
            />
            
          </div>

          <div
            style="font-size: 60px; width: 1000px"
            class="form-group text-center"
          >
            <button
              style="width: 500px; height: 100px; font-size: 50px"
              type="submit"
              id="join-submit"
              class="btn btn-primary"
            >
              회원가입<i class="fa fa-check spaceLeft"></i>
            </button>
            <button
              style="width: 500px; height: 100px; font-size: 50px"
              type="button"
              onclick='move_page_link("Main.php")'
              class="btn btn-warning"
            >
              가입취소<i class="fa fa-times spaceLeft"></i>
            </button>
          </div>
        </form>
      </div>
    </article>
  </body>
</html>


