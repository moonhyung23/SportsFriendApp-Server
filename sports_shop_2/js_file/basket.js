/* 상품 배송관리 js파일 */

let basket = {
    totalCount: 0,
    totalPrice: 0,
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
        sum_cost_Tag.textContent = (newval * cost).formatNumber() + '원';
    },
};

// 숫자 3자리 콤마찍기
Number.prototype.formatNumber = function() {
    if (this == 0) return 0;
    let regex = /(^[+-]?\d+)(\d{3})/;
    let nstr = this + '';
    while (regex.test(nstr)) nstr = nstr.replace(regex, '$1' + ',' + '$2');
    return nstr;
};