<!-- 공통적으로 사용하는 ui들을 모아놓은 php 파일 -->

<script>
/* 5. 회원탈퇴 yes or No 다이얼로그*/
function delete_register_chk() {
    //1)yes
        //회원탈퇴 
        document.location.href = "../page/Delete_Reason.php";
}
</script>


<?php 
/* 1.상단 로그인 관련 메뉴  */
function Login_Menu($id, $name){
    //3-1)비로그인
if($id == null) {
    $login = '<ul class="navbar_myinfor">
    <li><a href="Login.php"> <h1> 로그인 </h1></a></li>
    <li><a href="Agree.php"> <h1> 회원가입 </h1></a></li>
    <li><a href=""></a></li>
    <li><a href=""></a></li>
    </ul>';
?>
<?php
//3-2)로그인
}else{
    //관리자 계정
    if($id == 1){
        $login = '
        <!-- 로그인한 사용자의 이름을 표시 -->
            <div class="navbar_myinfor">
            <h1 style="font-size: 60px; margin-right:300px;" >'."$name".'님</h1>
            </div>
        <ul class="navbar_myinfor">
        <li><a href="../check/Logout_check.php"><h1> 로그아웃 </h1></a></li>
        <li><a href="Basket.php"><h1> 장바구니 </h1></a></li>
        <li><a href="Mypage.php"><h1> 관리자페이지 </h1> </a></li>
    </ul>';
    }
    // 회원계정
    else{
        $login = '
        <!-- 로그인한 사용자의 이름을 표시 -->
        <div class="navbar_myinfor">
        <h1 style="font-size: 60px; margin-right:300px;" >'."$name".'님</h1>
        </div>
        <ul class="navbar_myinfor">
        <li><a href="../check/Logout_check.php"><h1> 로그아웃 </h1></a></li>
        <li><a href="Basket.php"><h1> 장바구니 </h1></a></li>
        <li><a href="Mypage.php"><h1> My Page </h1> </a></li>
    </ul>';
    }
  //html 코드를 사용하기 위해서 php를 닫는다.
}


return $login;
} 


/* 2.상단 카테고리 네비게이션 메뉴 */
function Category_menu(){
?>
<!--3. 네비게이션 메뉴 -->
<ul class="navbar_menu ">
    <li><a href="Main.php?category=전체상품">전체상품</a></li>
    <li><a href="Main.php?category=농구화">농구화</a></li>
    <!-- <li><a href="">의류</a></li> -->
    <li><a href="Main.php?category=농구공">농구공</a></li>
    <!-- <li><a href="">스포츠 양말</a></li> -->
    <li><a href="Main.php?category=보호대">보호대</a></li>
</ul>
<?php
}

// 사이드메뉴
function Side_Menu($id){
/* 관리자 계정인 경우(id ==1) 
  -상품 등록 가능*/
if($id == 1) {
    $sidemenu = '
    <li class="list-group-item list-group-item-secondary" ><a href="Add_Edit_Item.php">상품등록</a></li>
    <li class="list-group-item list-group-item-secondary" ><a href="Add_Item_List.php">상품등록내역</a></li>
    <li class="list-group-item list-group-item-secondary" ><a href="OrderList.php">고객 주문내역</a></li>
    <li class="list-group-item list-group-item-secondary" ><a href="Bulletin.php">문의게시판</a></li>
    <li class="list-group-item list-group-item-secondary" ><a href="Chk_Pw_Register.php">회원정보 수정</a></li>
';
?>

<?php
//2-2)관리자 계정이 아닌 경우
// * 상품 등록 불가
}else{
    $sidemenu = '
    <li class="list-group-item list-group-item-secondary" ><a href="OrderList_Cus.php">나의 주문내역</a></li>
    <li class="list-group-item list-group-item-secondary" ><a href="Bulletin.php">문의게시판</a></li>
    <li class="list-group-item list-group-item-secondary" ><a href="Chk_Pw_Register.php">회원정보 수정</a></li>
    <li class="list-group-item list-group-item-secondary" > <a href="#" onclick="delete_register_chk();">회원탈퇴</a></li>
    ';
  //html 코드를 사용하기 위해서 php를 닫는다.
}
   return $sidemenu; 
}

//사이즈 html 형식을 만드는 함수.
function size_form($category_detail){
    if($category_detail == "무릎보호대"){
        // 무릎보호대 용 사이즈 
        $category_detail = ' <form class="form-inline">
        <select id="item_size" style="height: 80px; font-size:30px;" class="custom-select my-1 mr-sm-2"
            id="inlineFormCustomSelectPref">
            <option selected>
                <h1>사이즈선택</h1>
            </option>
            <option value="0">
                <h1>0</h1>
            </option>
            <option value="1">
                <h1>1</h1>
            </option>
            <option value="2">
                <h1>2</h1>
            </option>
            <option value="3">
                <h1>3</h1>
            </option>
            <option value="3C">
                <h1>3C</h1>
            </option>
            <option value="4">
                <h1>4</h1>
            </option>
            <option value="4C">
                <h1>4C</h1>
            </option>
            <option value="5">
                <h1>5</h1>
            </option>
            <option value="5C">
                <h1>5C</h1>
            </option>
            <option value="6">
                <h1>6</h1>
            </option>
            <option value="6C">
                <h1>6C</h1>
            </option>
            <option value="7">
                <h1>7</h1>
            </option>
            <option value="7C">
                <h1>7C</h1>
            </option>
        </select>
    </form>';
    }else{ 
        // 나머지 보호대 사이즈(팔꿈치, 어꺠, 발목)
        $category_detail = '<form class="form-inline">
        <select id="item_size" style="height: 80px; font-size:30px;" class="custom-select my-1 mr-sm-2"
            id="inlineFormCustomSelectPref">
            <option selected>
                <h1>사이즈선택</h1>
            </option>
            <option value="1">
                <h1>1</h1>
            </option>
            <option value="2">
                <h1>2</h1>
            </option>
            <option value="3">
                <h1>3</h1>
            </option>
            <option value="4">
                <h1>4</h1>
            </option>
            <option value="5">
                <h1>5</h1>
            </option>
            <option value="6">
                <h1>6</h1>
            </option>
        </select>
    </form>  ';
    }
   
return $category_detail;
}

?>