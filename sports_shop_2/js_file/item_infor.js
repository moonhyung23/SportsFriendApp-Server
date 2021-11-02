// 1)상품 구매 정보 페이지로 이동하는 함수
function move_purchase_page(id) {
    //로그인 상태를 확인한다
    if (id == 0) {
        alert('로그인을 해주세요');
        return;
    }

    /* 태그를 통해서 value를 가져온다. */
    //h1 태그는 innerhtml, input,select태그는 value를 사용
    //1)상품이름
    var item_name = document.querySelector('#item_name').innerHTML;
    //2)상품 가격(수량: 1개)
    var item_cost = document.querySelector('#item_cost').value;
    //4)상품 최종 수량
    var count = document.querySelector('#p_num1').value;
    //3)선택한 사이즈
    //-농구공은 사이즈가 없음.
    if (document.querySelector('#item_size').value != null) {
        var size = document.querySelector('#item_size').value;
    }
    //5)상품 최종 가격( 상품가격 * 수량)
    var cost = document.querySelector('#sum_p_price').innerHTML;
    //6)상품 이미지 경로
    //이미지 경로에서 이미지 이름만 가져온다
    var img_url = document.querySelector('#item_img').src;
    var img_name = img_url.replace(/^.*\//, '');
    //7)상품 번호
    var item_num = document.querySelector('#item_num').value;
    /* 사이즈를 고르지 않은 경우 return */

    if (size == '사이즈선택') {
        alert('사이즈를 선택해주세요.');
        return;
    }

    /* 상품정보를 post로 전달하는 메서드  */
    post_to_url_Purchase('Purchase.php', {
        //1)상품 이름
        item_name: item_name,
        //2)상품 가격
        item_cost: item_cost,
        //3)상품 최종 수량
        item_count: count,
        //4)선택한 사이즈
        item_size: size,
        //5)상품 최종 가격
        item_cost_end: cost,
        //6)상품 이미지
        img_url: img_name,
        //7)상품 번호
        item_num: item_num,
    });
}

//2)post방식으로 상품정보 전달하는 메서드
function post_to_url_Purchase(path, params, method) {
    method = method || 'post';
    var form = document.createElement('form');
    //전달방식
    form.setAttribute('method', method);
    //이동할 url
    form.setAttribute('action', path);
    //배열에 저장된 데이터의 갯수만큼 반복
    for (var key in params) {
        //데이터를 담을 input 태그 생성
        var hiddenField = document.createElement('input');
        hiddenField.setAttribute('type', 'hidden');
        hiddenField.setAttribute('name', key); //키
        //연관 배열 사용 ['']
        hiddenField.setAttribute('value', params[key]); //value
        //form에 input 태그 담기
        form.appendChild(hiddenField);
    }
    //form태그 body태그에 담기
    document.body.appendChild(form);
    //post로 데이터 보내기
    form.submit();
}

let basket = {
    totalCount: 0,
    totalPrice: 0,
    //체크한 장바구니 상품 비우기
    delCheckedItem: function() {
        document
            .querySelectorAll('input[name=buy]:checked')
            .forEach(function(item) {
                item.parentElement.parentElement.parentElement.remove();
            });
        //AJAX 서버 업데이트 전송

        //전송 처리 결과가 성공이면
        this.reCalc();
        this.updateUI();
    },
    //장바구니 전체 비우기
    delAllItem: function() {
        document.querySelectorAll('.row.data').forEach(function(item) {
            item.remove();
        });
        //AJAX 서버 업데이트 전송

        //전송 처리 결과가 성공이면
        this.totalCount = 0;
        this.totalPrice = 0;
        this.reCalc();
        this.updateUI();
    },

    //개별 수량 변경
    changePNum: function(pos) {
        //상품의 현재수량을 표시해주는 input태그를 갖고온다
        var item = document.querySelector('input[name=p_num' + pos + ']');
        //상품 현재 수량 (input태그에서 상품의 현재수량을 갖고온다)
        var p_num = parseInt(item.getAttribute('value'));
        //상품 수량 (최종)
        var newval = event.target.classList.contains('up') ?
            p_num + 1 :
            event.target.classList.contains('down') ?
            p_num - 1 :
            event.target.value;

        /* 상품 수량이 1미만이거나 99초과일 때 작동X */
        if (parseInt(newval) < 1 || parseInt(newval) > 99) {
            return false;
        }

        //상품 수량을 입력한다.
        item.setAttribute('value', newval);

        // 상품 가격(1개당) (h1태그)
        var cost_Tag = document.querySelector('#item_cost');
        // 상품의 가격을 갖고온다
        var cost = cost_Tag.getAttribute('value');

        // 상품 가격 * 수량 => 최종 상품 가격

        // 전체 가격 h1태그
        var sum_cost_Tag = document.querySelector('#sum_p_price');

        //h1태그에 전체가격(가격 * 수량) 입력
        sum_cost_Tag.textContent = newval * cost + '원';
    },
};

// 3)숫자 3자리 콤마찍기
Number.prototype.formatNumber = function() {
    if (this == 0) return 0;
    let regex = /(^[+-]?\d+)(\d{3})/;
    let nstr = this + '';
    while (regex.test(nstr)) nstr = nstr.replace(regex, '$1' + ',' + '$2');
    return nstr;
};

// 4)공백 체크
function checkSpace(str) {
    if (str.search(/\s/) != -1) {
        return true;
    } else {
        return false;
    }
}

/* 5)장바구니에 상품을 추가하는 메서드 */
function add_basket_item(id, category) {
    //로그인 상태를 확인한다
    if (id == 0) {
        alert('로그인을 해주세요');
        return;
    }

    //1)상품 사이즈
    //농구공이 아닌 경우만 상품의 사이즈를 갖고온다.
    //이유: 농구공은 사이즈가 없음.
    if (category != '농구공') {
        var item_size = document.querySelector('#item_size').value;
    }
    let item_count = document.querySelector('#p_num1').value; //2)상품 수량
    let item_number = document.querySelector('#item_num').value; //3)상품 번호

    // 신발 사이즈를 선택 검사
    if (item_size == '사이즈선택') {
        alert('사이즈를 선택해주세요');
        return;
    }

    /* Post방식으로 상품 정보를 보낸다 (장바구니 상품 추가 페이지에)
                                -상품 사이즈
                                -상품 수량
                                -상품 번호
                                    */

    post_to_url_Purchase('../check/Add_basket_item.php', {
        item_size: item_size,
        item_count: item_count,
        item_number: item_number,
    });
}