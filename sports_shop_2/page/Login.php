<!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css_file/login.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!-- 제이쿼리 라이브러리 -->
    <title>로그인</title>
  </head>
  <body>
    <!-- 전체 폼 -->
    <div class="container">
      <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
          <div class="card card-signin my-5">
            <!-- 쇼핑몰 이름 -->
            <h1
              class="shop_name text-center"
              style="margin: 10px; font-size: 100px"
            >
              로그인
            </h1>
            <div class="card-body">
              
              <!-- 로그인 폼 -->
              <form
                method="post"
                class="form-signin"
                name="form1"
                action="../check/Login_check.php"
              >
                <div class="form-label-group">
                  <!-- 1.이메일 입력 -->
                  <input
                    required
                    style="height: 200px; font-size: 50px"
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="Email address"
                    required
                    autofocus
                  />
                  <label for="inputEmail">
                    <h1>Email address</h1>
                  </label>
                </div>

                <div class="form-label-group">
                  <!-- 2.비밀번호 입력 -->
                  <input
                    required
                    style="height: 200px; font-size: 50px"
                    type="password"
                    id="pw"
                    name="pw"
                    class="form-control"
                    placeholder="Password"
                  />
                  <label for="inputPassword">
                    <h1>Password</h1>
                  </label>
                </div>

                <!-- 3.자동로그인 체크박스 -->
                <!-- <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember password</label>
              </div> -->

                <!-- 4.로그인 -->
                <button
                  class="btn btn-lg btn-primary btn-block text-uppercase"
                  type="submit"
                  onclick='btn_click("Login_check");'
                >
                  로그인
                </button>
                <!-- 5.회원가입  -->
                <button
                  class="btn btn-lg btn-primary btn-block text-uppercase"
                  type="submit"
                  onclick='btn_click("Agree");'
                >
                  회원가입
                </button>
                
                <!-- 6.비밀번호 찾기 -->
                <a href="Find_pw.php" style="text-align: center; padding:15px;" >
                <h1>Forget your Password?</h1>
                </a>
                

                <!--          <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fab fa-google mr-2"></i> Sign in with Google</button>
              <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fab fa-facebook-f mr-2"></i> Sign in with Facebook</button> -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<script>
  //클릭한 버튼에 따라서 페이지 이동
  function btn_click(str) {
    //1)로그인 페이지로 이동
    if (str == "Login_check") {
      form1.action = "../check/Login_check.php";
      //2)회원가입 약관 페이지로 이동
    } else if (str == "Agree") {
      //email, 비밀번호 입력 태그 비활성화
      //(email, 비밀번호가 필수입력 요소이기 때문에)
      Tag_disabled("email", true);
      Tag_disabled("pw", true);
      // 회원약관 페이지로 이동
      form1.action = "Agree.php";
    } else {
    }
  }

  // 태그 비활성화/활성화
  function Tag_disabled(Tag_id, boolean) {
    // 비활성화 하려는 태그의 id를 가져온다
    const target = document.getElementById(Tag_id);
    // 태그의 아이디로 해당 태그 비활성화/활성화
    target.disabled = boolean;
  }
</script>
