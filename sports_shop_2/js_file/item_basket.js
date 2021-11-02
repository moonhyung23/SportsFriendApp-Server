/* 상품 장바구니 js파일 */

let basket = {
    totalCount: 0,
    totalPrice: 0,

    //1.상품 수량 변경
    changePNum: function(pos) {
        //체크된 박스를 가져온다.
        let checkbox = $('input:checkbox[name=check_basket]:checked');
        //체크된 박스가 없으면 중지
        if (checkbox.length == 0) {
            alert('구입하시려는 상품을 체크해주세요');
            return;
        }

        //상품의 현재수량을 표시해주는 input태그를 갖고온다
        var item = document.getElementById(pos);
        //상품 현재 수량 (input태그에서 상품의 현재수량을 갖고온다)
        var p_num = parseInt(item.value);
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

        //상품 전체 수량
        item.value = newval;

        // 상품 가격(1개당) 가격
        var price =
            item.parentElement.parentElement.parentElement.parentElement.cells[2]
            .children[0].innerHTML;

        // 하나의 상품 합계(가격 * 수량)
        item.parentElement.parentElement.parentElement.parentElement.cells[4].children[0].innerHTML =
            //가격(1개당) * 수량
            price * newval;

        /* 장바구니 상품의 전체 가격을 계산. */
        this.reCalc();
        /* 상품의 전체 가격과 전체 수량을 갱신 */
        this.updateUI();
    },

    /* 2.장바구니 전체 상품 가격 재계산
                                                                                                                      -삭제하거나 수량 변경시 변경되는 가격을 계산
                                                                                                                      */
    reCalc: function() {
        //이전에 설정된 가격 초기화
        this.totalCount = 0;
        this.totalPrice = 0;

        var all_price = 0;
        var all_count = 0;
        //체크된 박스를 가져온다.
        let checkbox = $('input:checkbox[name=check_basket]:checked');

        //체크된 박스가 없으면 중지
        if (checkbox.length == 0) {
            return;
        }
        //체크된 갯수만큼 반복(for문)
        checkbox.each(function(i) {
            //체크된 하나의 상품의 총 갯수
            var tr = checkbox.parent().parent().eq(i).children();
            //체크된 상품 하나의  총 수량
            let item_count = tr.eq(3).children().children().children().eq(0).val();
            //상품 가격 1개당
            let item_cost = tr.eq(2).children().text();
            //상품 하나의  가격을 구한다(수량 * 가격)
            all_price += parseInt(item_cost) * parseInt(item_count);
            all_count += parseInt(item_count);
        });
        this.totalPrice = all_price;
        this.totalCount = all_count;
    },
    /* 3.장바구니 전체 상품 갯수, 합계 금액 UI 갱신     
                                                                                                                        -reCalc함수에서 계산한 값을 입력해줌      
                                                                                                                        */
    updateUI: function() {
        document.querySelector('#sum_p_num').textContent =
            '상품 갯수: ' + this.totalCount.formatNumber() + '개';
        document.querySelector('#sum_p_price').textContent =
            '상품 금액: ' + this.totalPrice.formatNumber() + '원';
    },

    /* 4.상품 1개만 삭제하는 함수  */
    delItem: function(number) {
        //클릭한 장바구니에서 상품 삭제
        event.target.parentElement.parentElement.remove();
        //삭제할 상품의 번호 서버에 전달하기
        $.ajax({
            url: '../check/Delete_basket_item.php', //이동할 url
            type: 'post', //보내는 방식
            data: {
                basket_num: number, //삭제할 상품 번호
            },
            success: function(response) {
                alert('삭제가 완료되었습니다.');
                location.reload(); //바로 새로고침.
                this.reCalc();
                this.updateUI();
            },
        });
    },

    /* 5.체크된 상품 가격 및 수량 계산 */
    item_check: function() {
        if ($('#check_basket').is(':checked')) {}
        this.reCalc();
        this.updateUI();
    },

    /* 5.입력해서 수량 변경하는 함수 */
    input_change_count: function(TagId) {
        /* 1.클릭한 버튼 Tag, 수량을 확인하는 input태그를 갖고온다 */
        //-클릭한 버튼 Tag
        let btn_count = event.currentTarget.querySelector('#h1_count');
        //-input Tag
        let ipt_count = document.getElementById(TagId);
        //1)태그의 텍스트가 => "입력해서 수량 변경"인 경우

        //2)태그의 텍스트가 => "수량 변경"인 경우
        const myRegExp = /^[0-9]+$/;

        /* 2-3)예외처리 */
        // -숫자가 입력되지 않은 경우
        if (myRegExp.test(ipt_count.value) === false) {
            alert('잘못 입력하셨습니다 숫자를 입력해주세요.');
            ipt_count.value = 1;
            return;
        }
        // -너무 큰 숫자(100 이상)가 입력된 경우
        if (ipt_count.value > 1000) {
            alert('최대 한도를 초과하셨습니다.(최대한도: 1000개)');
            ipt_count.value = 1;
            return;
        }

        // -잘못된 숫자가 입력된 경우
        if (ipt_count.value == 0) {
            alert('잘못 입력하셨습니다.');
            ipt_count.value = 1;
            return;
        }

        //2-4) 상품 가격(1개당) 가격
        var price =
            ipt_count.parentElement.parentElement.parentElement.parentElement.cells[2]
            .children[0].innerHTML;

        //2-5) 하나의 상품 합계(가격 * 수량)
        ipt_count.parentElement.parentElement.parentElement.parentElement.cells[4].children[0].innerHTML =
            //가격(1개당) * 상품 수량
            price * ipt_count.value;

        //2-6)상품 가격 및 수량 다시 계산
        this.reCalc();
        this.updateUI();
    },
    //6.상품 주문하기
    item_order: function() {
        //상품 가격 및 수량 다시 계산
        this.reCalc();
        this.updateUI();

        // 장바구니에 담긴 상품을 담을 배열
        var basket_ar_parent = [];
        //체크된 체크 박스를 갖고온다
        let checkbox = $('input:checkbox[name=check_basket]:checked');
        //체크된 상품을 검사한다
        if (checkbox.length == 0) {
            alert('상품을 선택해주세요.');
            return;
        }
        //체크된 박스의 갯수만큼 반복(for문)
        checkbox.each(function(i) {
            /* i가 필요한 이유 체크된 행이 3개면 그 행을 하나하나씩 선택해줌. 
                                                                                                                                                                                                                                                                                                     체크된 로우 => 로우의 전체 컬럼 => 컬럼
                                                                                                                                                                                                                                                                                                     */

            //  체크된 박스의 로우를 갖고온다(i = 0 => 체크된 첫번째 로우)
            let tr = checkbox.parent().parent().eq(i);
            // 로우의 전체 컬럼을 갖고온다
            let all_td = tr.children();
            //1)체크된 상품의 수량
            let item_count = all_td
                .eq(3)
                .children()
                .children()
                .children()
                .eq(0)
                .val();
            //2)체크된 상품 한개의 가격
            let item_cost = all_td.eq(2).children().eq(0).text();
            // -전체가격 = 한개의 가격 * 수량 (입력 하는 도중에 선택한 상품을 주문 할 수 있기 때문에)
            let item_all_cost = parseInt(item_cost) * parseInt(item_count);

            //3)체크된 상품의 번호
            let item_number = all_td.eq(6).val();
            //4)상품정보(이름 + 사이즈)
            let item_name = all_td.eq(1).text();
            // 5)상품 이미지
            let item_image = all_td.eq(1).children().eq(0).attr('src');
            //5-1)상품의 이름만 갖고온다.
            let image_name = item_image.replace('../image_files/', '');
            //6)장바구니 상품번호
            let basket_num = all_td.eq(7).children().eq(0).val();

            /* 7)상품 정보 배열 생성(자식)
             */
            let basket_ar_child = [
                item_count,
                item_all_cost,
                item_number,
                item_name,
                image_name,
                basket_num,
            ];
            //상품정보를 배열에 저장(부모)
            basket_ar_parent.push(basket_ar_child);
        });

        let jsonEncode = JSON.stringify(basket_ar_parent);
        post_to_url('Purchase_basket.php', {
            item_infor_json: jsonEncode,
        });
    },
};

// 7.숫자 3자리 콤마찍기
Number.prototype.formatNumber = function() {
    if (this == 0) return 0;
    let regex = /(^[+-]?\d+)(\d{3})/;
    let nstr = this + '';
    while (regex.test(nstr)) nstr = nstr.replace(regex, '$1' + ',' + '$2');
    return nstr;
};