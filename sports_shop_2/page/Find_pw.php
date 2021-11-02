<!-- 비밀번호 찾기 php 파일 
    이름, 이메일 주소를 입력함.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css 파일 -->
    <link rel="stylesheet" href="../css_file/login.css?ver=1" />
    <!-- 부트스트랩 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <title>비밀번호 찾기</title>
</head>
<body>
    <!-- 전체 폼 -->
    <div class="container">
      <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
          <div class="card card-signin my-5" style="height: 800px;">
            <h1
              class="shop_name text-center"
              style="margin: 10px; font-size: 100px"
            >
              비밀번호 찾기
            </h1>
            <div class="card-body">
              
              <!-- 비밀번호 찾기 폼 
                -Sendmail.php 파일로 이동
            -->
              <form
                method="post"
                class="form-signin"
                name="form1"
                action="../check/Sendmail.php"
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
                    placeholder="이메일을 입력해주세요"
                    required
                    autofocus
                  />
                  <label for="inputEmail">
                    <h1>Email</h1>
                  </label>
                </div>

                <div class="form-label-group">
                  <!-- 2.이름 입력 -->
                  <input
                    required
                    style="height: 200px; font-size: 50px"
                    type="text"
                    id="pw"
                    name="name"
                    placeholder="이름을 입력해주세요"
                    class="form-control"
                  />
                  <label for="inputPassword">
                    <h1>Name</h1>
                  </label>
                </div>

                <!-- 3.비밀번호 찾기 -->
                <button
                  class="btn btn-lg btn-primary btn-block text-uppercase"
                  type="submit"
                  >
                  비밀번호 찾기
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>