<?php

//db파일 추가
include "../Db.php";
//Ui 파일 추가
include  "../Common_Ui.php";

//세션 설정
session_start();
settype($_SESSION['id'], 'integer');
$id = $_SESSION['id'];

//2-1)상품 수정
if(isset($_POST['edit'])){
    $edit_num = $_POST['edit']; //상품 수정 체크 번호
    $item_num = $_POST['item_num']; // 수정하려는 상품 번호
}else{
    $edit_num = 0; //상품 수정 체크 번호
    $item_num = 0; // 수정하려는 상품 번호 
}

settype($edit_num, 'integer');
settype($item_num, 'integer');

//상품을 수정하려는 경우에만 동작
if($edit_num == 1){

//2-2)상품 번호로 수정하려는 상품의 정보를 갖고있는 행을 DB에서 조회한다.
$sql_select = "SELECT * FROM Add_Item WHERE item_num_A = $item_num "; 
$result_select = mysqli_query($con, $sql_select);
$row_edit = mysqli_fetch_assoc($result_select);
}

//2-2)상품 등록
if(isset($item_id)){
    //수정하려는 상품의 번호와 맞는 상품정보를 테이블에서 불러온다.
    $sql_item = "SELECT * FROM Add_item where item_num_A = $item_num";
    $result = mysqli_query($con,  $sql_item);
    $row = (mysqli_fetch_assoc($result));
    
    //상품 정보
    $item_name = $row['item_name_A']; 
    $item_content = $row['content'];//상품 정보
    $item_cost = $row['cost'];//상품 가격
    $item_num_A = $row['item_num_A'];//상품 번호
    $img_url = $row['img_url'];//상품 이미지 url
    $item_category = $row['category'];//상품 카테고리
    $item_count = $row['count'];//상품 갯수
    }
    /* 2.상품 수정/상품 등록이 구분되어 있다. */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css_file/add_edit_item.css?ver=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- 많이쓰는 함수 js파일 -->
    <script src="../js_file/Global.js"></script>;
    <!-- 제이쿼리 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>상품CRUD</title>
</head>

<body>
    <?php 
    
/* 0.상품정보 수정/등록 구분  */
// 0-1)상품정보 수정
  if($edit_num == 1){ 
    ?>
    <a href="Add_Item_List.php" style="text-align:center;">
        <h1 style="margin: 15px;  font-size: 40px;">뒤로가기
        </h1>
    </a>
    <!-- 삼품정보 수정 페이지로 이동 -->
    <form class="edit_infor" style="text-align: center;" method="post" action="../check/Update_Item.php"
        enctype="multipart/form-data">
        <div style="margin: auto; width:50%">
            <input class="btn btn-success" type="submit" name="submit" value="상품 수정"
                style="padding: 5px; width: 100%; font-size:25px;" id="btn_edititem" role="button" /></input>
        </div>
        <!-- 제품의 번호를 hidden타입을 통해 input에 담는다 -->
        <input type="hidden" name="item_num" value="<?=$item_num?>" /></input>
        <?php
        }
        //  0-2)상품정보 등록
        else{?>
        <a href="Add_Item_List.php" style="text-align:center;">
            <h1 style="margin: 15px;  font-size: 40px; margin:auto;">뒤로가기</h1>
        </a>
        <!-- 삼품정보 등록 페이지로 이동 -->
        <form class="edit_infor" style="text-align: center; " method="post" action="../check/Add_Item_check.php"
            enctype="multipart/form-data">
            <div style="margin: auto; width:50%">
                <input class="btn btn-success" type="submit" name="submit" value="상품 등록"
                    style="padding:5px; font-size:25px; width: 100%; " id="btn_additem" role="button" />
            </div>
            </input>
            <?php }
        ?>
            <!-- 1.카테고리 -->
            <!-- 수정시에는 입력했던 아이템 정보를 입력한다. -->
            <!-- 1-1)아이템 수정 -->
            <?php if($edit_num == 1){  ?>
            <li class="input-group input-group-lg" style="width:40%; margin: auto; ">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">카테고리</span>
                </div>
                <select style="font-size:25px;" required name="category" class="custom-select my-1 mr-sm-2"
                    aria-label="Default select example">
                    <!-- 이전에 입력된 카테고리 -->
                    <option selected value="<?=$row_edit['category']?>"> <?=$row_edit['category']?> </option>
                    <option value="농구화">농구화</option>
                    <option value="농구공">농구공</option>
                    <option value="보호대">보호대</option>
                </select>
                <?php 
                        }else{ 
                        ?>
            </li>
            <!--1-2)아이템 등록 -->
            <li class="input-group input-group-lg" style="width:50%; margin: auto; ">
                <div class="input-group-prepend"> <span class="input-group-text" id="inputGroup-sizing-lg">카테고리</span>
                </div>
                <select style="font-size:25px;" required name="category" class="custom-select my-1 mr-sm-2"
                    aria-label="Default select example">
                    <option selected> 카테고리 선택 </option>
                    <option value="농구화">농구화</option>
                    <option value="농구공">농구공</option>
                    <option value="보호대">보호대</option>
                </select>
                <?php 
                        }  
                        ?>
            </li>
            <?php if($edit_num == 1){ ?>
            <!-- 2.세부 카테고리 
            2-1)아이템 수정
            -->
            <li class="input-group input-group-lg" style="width:40%; margin: auto; ">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">세부 카테고리</span>
                </div>
                <select style="font-size:25px;" required name="category_detail" class="custom-select my-1 mr-sm-2"
                    aria-label="Default select example">
                    <!-- 이전에 입력된 카테고리 -->
                    <option selected value="<?=$row_edit['category_detail']?>"> <?=$row_edit['category_detail']?>
                    </option>
                    <option value="무릎보호대">무릎보호대</option>
                    <option value="발목보호대">발목보호대</option>
                    <option value="팔꿈치보호대">팔꿈치보호대</option>
                    <option value="어깨보호대">어깨보호대</option>
                </select>
            </li>
            <?php }else{ ?>

            <!-- 2-2)아이템 등록 -->
            <li class="input-group input-group-lg" style="width:40%; margin: auto; ">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">세부 카테고리</span>
                </div>
                <select style="font-size:25px;" required name="category_detail" class="custom-select my-1 mr-sm-2"
                    aria-label="Default select example">
                    <!-- 이전에 입력된 카테고리 -->
                    <option selected value="세부 카테고리"> 세부 카테고리 </option>
                    <option value="무릎보호대">무릎보호대</option>
                    <option value="발목보호대">발목보호대</option>
                    <option value="팔꿈치보호대">팔꿈치보호대</option>
                    <option value="어깨보호대">어깨보호대</option>
                </select>
            </li>
            <?php
                    }?>

            <!-- 3.상품이름 -->
            <li class="input-group input-group-lg" style="width:40%; margin:auto;">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">이름</span>
                </div>

                <!-- 3-1)아이템 수정 -->
                <?php if($edit_num == 1){  
    ?>
                <input maxlength="30" placeholder="최대 30자" type="text" name="name" class="form-control"
                    value="<?=$row_edit['item_name_A']?>" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                    required />
                <?php }else{ 
    ?>
                <!--3-2) 아이템 등록 -->
                <input maxlength="30" placeholder="최대 30자" type="text" name="name" class="form-control" value=""
                    aria-label="Large" aria-describedby="inputGroup-sizing-sm" required />
                <?php } ?>


            </li>

            <!--4.상품설명 -->
            <li class="input-group input-group-lg" style="width:40%; margin: auto;" ;>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">상품 설명</span>
                </div>

                <!--4-1) 아이템 수정 -->
                <?php if($edit_num == 1){  ?>
                <!-- TextArea는 value가 없어서 태그사이에 내용을 적어줌 -->
                <textarea name="content" id="text-area" required cols="30" rows="6" wrap="hard" class="form-control"
                    aria-label="Large" style="font-size: 20pt;" aria-describedby="inputGroup-sizing-sm">
                        <?=$row_edit['content']?>
                        </textarea>


                <!--4-2) 아이템 등록 -->
                <?php }else{ ?>
                <textarea name="content" id="text-area" required cols="30" rows="6" wrap="hard" class="form-control"
                    aria-label="Large" style="font-size: 20pt;" aria-describedby="inputGroup-sizing-sm"></textarea>
                <?php } ?>
            </li>



            <!-- 5.가격 -->
            <li class="input-group input-group-lg" style="width:40%; margin: auto;" ;>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">가격</span>
                </div>

                <!-- 5-1) 아이템 수정 -->
                <?php if($edit_num == 1){ 
                       ?>
                <input type="number" maxlength="10" oninput="maxLengthCheck(this)" name="cost" class="form-control"
                    value="<?=$row_edit['cost']?>" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                    required />
                <?php }else{
                       ?>
                <!-- 5-2) 아이템 등록 -->
                <input type="number" maxlength="10" oninput="maxLengthCheck(this)" name="cost" class="form-control"
                    value="" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required />
                <?php } 
                     ?>


                <h1 style="margin-left: 5px; font-size: 25pt; ">원</h1>
            </li>

            <!--6.재고수량 -->
            <li class="input-group input-group-lg" style="width:40%; margin: auto;" ;>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-lg">수량</span>
                </div>

                <!-- 6-1) 아이템 수정 -->
                <?php if($edit_num == 1){ 
    ?>
                <!-- 이전 수량표시  -->
                <input type="number" maxlength="10" oninput="maxLengthCheck(this)" name="count" class="form-control"
                    value="<?=$row_edit['count']?>" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                    required />
                <h1 style="margin-left: 5px; font-size: 25pt; ">개</h1>
                <?php }else{
     ?>
                <!--6-2) 아이템 등록 -->
                <input type="number" maxlength="10" oninput="maxLengthCheck(this)" name="count" class="form-control"
                    value="" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required />
                <h1 style="margin-left: 5px; font-size: 25pt; ">개</h1>
                <?php }
   ?>
            </li>

            <!-- 7.상품 이미지 등록 -->
            <h1 style="font-size: 20pt; margin-top:20px; text-align:center;">상품 이미지 등록</h1>
            <h1 style="font-size: 16pt; text-align:center; ">(처음 등록한 사진이 썸네일 사진으로 표시됩니다)</h1>

            <div class="custom-file" id="btn_additem">
                <div>
                    <!-- 등록/수정 하려는  이미지 등록  -->
                    <input type="file" value="../image_files/<?=$row_edit['img_url']?>" name="item_image"
                        class="custom-file-input" id="image" />
                    <label class="custom-file-label" for="customFile">Choose file</label>

                </div>

            </div>

            <!-- 상품 이미지 미리보기 공간 -->
            <div id="image_container">
                <?php 
        // 상품 정보 이미지를 수정하는 경우 등록했던 상품의 이미지를 미리 표시해준다.
        // 7.상품을 수정/등록을 구분한다
        if(isset($edit_num)){
            ?>
                <!-- 7-1)상품 수정(이전에 등록된 상품 이미지) -->
                <img style="width: 307px; height: 307px;" class="img-thumbnail"
                    src="../image_files/<?=$row_edit['img_url']?>" id="preview-image">
                <?php 
        }else{
            ?>
                <!--7-2)상품 등록(등록할 상품 이미지) -->
                <img style="width: 307px; height: 307px;" class="img-thumbnail" id="preview-image" src="">
                <?php 
        }
            ?>
            </div>
        </form>
</body>

</html>

<!-- 자바 스크립트 코드 -->
<script>
//1)이미지 미리보기를 해주는 메서드
function readImage(input) {
    // 인풋 태그에 파일이 있는 경우
    if (input.files && input.files[0]) {
        // 이미지 파일인지 검사 (생략)
        // FileReader 인스턴스 생성
        const reader = new FileReader()
        // 이미지가 로드가 된 경우
        reader.onload = e => {
            const previewImage = document.getElementById("preview-image")
            previewImage.src = e.target.result
        }
        // reader가 이미지 읽도록 하기
        reader.readAsDataURL(input.files[0])
    }
}
// input file에 change 이벤트 부여
const inputImage = document.getElementById("image")
inputImage.addEventListener("change", e => {
    readImage(e.target)
})
</script>