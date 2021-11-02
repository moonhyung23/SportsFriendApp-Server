/* 많이 사용하는 함수 모음 */

/*1. 페이지 뒤로가기 */
function history_back(message) {
    alert(message);
    history.back();
}

function history_back2() {
    history.back();
}

/*2. 페이지 이동 후 메세지 남기기 */

function move_page(message, link) {
    alert(message);
    document.location.href = link;
}

/* 3.페이지 이동 */
function move_page_link(link) {
    document.location.href = link;
}

//3. 태그 비활성화/활성화
function Tag_disabled(Tag_id, boolean) {
    // 비활성화 하려는 태그의 id를 가져온다
    const target = document.getElementById(Tag_id);
    // 태그의 아이디로 해당 태그 비활성화/활성화
    target.disabled = boolean;
}

//4.Post방식으로 데이터 넘겨주기
//-함수의 입력값에 배열을 넣어서 배열에 post로 보낼 값(key : value)을 담는다.
//함수 예시) post_to_url("/sports_shop/web_page/additempage.php", {'edit': 1,'id':<?=$id?>})
function post_to_url(path, params, method) {
    //1)get, post 설정
    method = method || "post";
    //2)post를 보내기 위한 form 태그 생성
    var form = document.createElement("form");
    //3)form 태그 속성 설정
    //  -method 방식 설정
    //  -action: 경로 설정
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    //4)post로 보낼 데이터 배열의 갯수 만큼 반복
    for (var key in params) {
        //5)값을 담을 input태그 생성
        var hiddenField = document.createElement("input");
        //6)인풋 태그 속성 설정
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key); //키
        hiddenField.setAttribute("value", params[key]); //value
        //form 태그에 input태그를 넣는다.
        form.appendChild(hiddenField);
    }
    //7)전체 boby에 form태그를 넣는다.
    document.body.appendChild(form);
    //8)post로 값 보내기
    form.submit();
}

/* 5. yes or No  다이얼로그*/
function alert_yes_No(message, yes_link, No_link) {
    //1)yes
    if (confirm(message) === true) {
        //페이지 이동
        document.location.href = yes_link;
    } //2)No
    else {
        document.location.href = No_link;
        return;
    }
}

/* 6. 삭제 확인 다이얼로그*/
function delete_check(message) {
    //1)아니요
    if (confirm(message) === false) {
        return;
    }
}

/*  7. 주소검색 api  (html 헤드에 script 코드 등록 필수)*/
/* post: 우편번호가 담긴 태그 id
   addr: 주소가 담긴 태그 id
<!-- daum 주소검색 api cdn-->
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
*/
function findAddr(post_num_tagId, addr_tagId) {
    var width = 1200; //팝업의 너비
    var height = 1000; //팝업의 높이

    new daum.Postcode({
        width: width, //생성자에 크기 값을 명시적으로 지정해야 합니다.
        height: height,
        oncomplete: function(data) {
            console.log(data);
            /* 1.검색한 주소를 담는다 */
            // 1-1)검색한 도로명 주소를 변수에 담는다.
            var roadAddr = data.roadAddress;
            // 1-2)검색한 지번 주소를 변수에 담는다.
            var jibunAddr = data.jibunAddress;

            /* 2. 검색한 주소를 태그에 입력한다. */
            // 2-1)우편번호 input태그에 입력
            var input_post_num = document.getElementById(post_num_tagId);
            /* 이전에 입력했던 주소가 있는 경우 초기화 */
            input_post_num.value = "";
            input_post_num.value = data.zonecode;

            // 2-2)도로명 주소가 있는 경우
            if (roadAddr !== "") {
                //도로명 주소 input태그에 입력
                var input_road_addr = (document.getElementById(addr_tagId).value =
                    roadAddr);
                /* 이전에 입력했던 주소가 있는 경우 초기화 */
                input_road_addr.value = "";
                input_road_addr.value = roadAddr;
            }

            //2-3)지번 주소가 있는 경우
            else if (jibunAddr !== "") {
                // 지번 주소 input태그에 입력
                var input_jibun_addr = (document.getElementById(addr_tagId).value =
                    jibunAddr);
                /* 이전에 입력했던 주소가 있는 경우 초기화 */
                input_jibun_addr.value = "";
                input_jibun_addr.value = roadAddr;
            }
        },
    }).open({
        left: window.screen.width / 2 - width / 2,
        top: window.screen.height / 2 - height / 2,
    });
}

//8.랜덤 난수 생성 n: 난수의 개수
function generateRandomCode(n) {
    let str = "";
    for (let i = 0; i < n; i++) {
        str += Math.floor(Math.random() * 10);
    }
    return str;
}

//9.php 변수를 자바스크립트로 갖고오는 함수.
// 자바스크립트 정의할 변수 var data = "갖고올변수";
function set_data(data) {
    this.json_data = data;
}

//10.숫자 길이 체크
//태그에서 정한 maxLength만큼 입력할 수 있다.
function maxLengthCheck(object) {
    if (object.value.length > object.maxLength) {
        object.value = object.value.slice(0, object.maxLength);
    }
}

/* 랜덤 8자리 난수 생성 */
// $num = sprintf('%08d', rand(00000000, 99999999));