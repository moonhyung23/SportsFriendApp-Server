//상품 정보 json배열
var json_data = '';

function purchase() {
    /* 입력한 구매정보  */
    //1)구매자이름
    let buyerName = document.querySelector('#new_receiver_name').value;
    //2)구매자 전화번호
    let phone_Num_low = document.querySelector('#phone_Low').value; //앞자리
    let phone_Num_middle = document.querySelector('#phone_Middle').value; //중간
    let phone_Num_high = document.querySelector('#phone_High').value; //뒷자리
    // parseInt를 하면 앞에 0이 사라진다. (그래서 추후에 0붙여 주어야 함.)
    let buyerPhone_Num_end = parseInt(
        phone_Num_low + phone_Num_middle + phone_Num_high
    );
    //3)구매자 주소
    let addr = document.querySelector('#member_addr').value; //앞주소
    let address_detail = document.querySelector('#address_detail').value; //상세주소
    //3-1)구매자 주소 합치기
    let buyer_addr = addr + address_detail;
    //4)구매자 우편번호
    let buyer_post_Num = document.querySelector('#member_post').value;
    //5)결제금액(최종 결제 금액) h1태그
    let item_cost = parseInt(document.querySelector('#item_cost').value);
    //6)요청사항
    let request_detail = document.querySelector('#request_detali').value;
    //7)구매자 이메일
    let buyer_eamil = document.querySelector('#email').value;
    //8)주문번호
    let redundancy_chk = generateRandomCode(8);
    //9)상품 수량
    let item_count = document.querySelector('#item_count').innerHTML;

    //1)구매자 이름 공백체크
    if (buyerName.length === 0) {
        alert('구매자를 입력하세요.');
        return;
    }

    //2)전화번호 공백체크
    //전화번호가 10자리 미만일 때
    //숫자로 형변환되면서 11자리가 10자리로 변함.
    if (buyerPhone_Num_end.toString().length < 10) {
        alert('전화번호를 입력해주세요.');
        return;
    }
    //3) 주소 공백 체크
    if (addr.length === 0) {
        alert('주소를 입력 해주세요');
        return;
    }

    //3)상세 주소 공백 체크
    if (address_detail.length === 0) {
        alert('상세 주소를 입력 해주세요');
        return;
    }

    IMP.request_pay({
            pg: 'inicis', // version 1.1.0부터 지원.
            pay_method: 'card', //결제 방식
            merchant_uid: 'merchant_' + new Date().getTime(), //결제 날짜
            name: '농구용품', //상품이름
            amount: item_cost, //결제 금액(수량 * 가격)
            buyer_email: buyer_eamil, //구매자  이메일
            buyer_name: buyerName, //구매자
            buyer_tel: buyerPhone_Num_end, //구매자 전화번호
            buyer_addr: buyer_addr, //구매자 주소
            buyer_postcode: buyer_post_Num, //구매자 우편번호
            m_redirect_url: 'https://www.yourdomain.com/payments/complete',
        },
        function(rsp) {
            /* 결제 성공 후 출력될 다이얼로그  */
            if (rsp.success) {
                var msg = '결제가 완료되었습니다.';
                msg += '고유ID : ' + rsp.imp_uid;
                msg += '상점 거래ID : ' + rsp.merchant_uid;
                msg += '결제 금액 : ' + rsp.paid_amount;
                msg += '카드 승인번호 : ' + rsp.apply_num;
                rsp.a;
                //POST 방식으로 데이터를 담고 결제완료 페이지 이동
                post_to_url('Pur_Com_basket.php', {
                    phone_Num: buyerPhone_Num_end, //1)핸드폰 번호
                    addr: addr, //2)주소(앞)
                    addr_detail: address_detail, //3)상세주소
                    post_num: buyer_post_Num, //4)우편번호
                    item_cost: item_cost, //5)결제금액
                    request_detail: request_detail, //6)배송메세지
                    redundancy_chk: redundancy_chk, //7)주문번호
                    buyer_Name: buyerName, //8)구매자
                    item_count: item_count, //9)상품 갯수
                    item_infor_json: JSON.stringify(json_data), //10)상품정보 json배열
                });
            } else {
                /*결제 실패 후 출력될 다이얼로그  */
                var msg = '결제에 실패하였습니다.';
                msg += '에러내용 : ' + rsp.error_msg;
            }
            alert(msg);
        }
    );
}

//주문번호를 만드는 메서드
function generateRandomCode(n) {
    let str = '';
    for (let i = 0; i < n; i++) {
        str += Math.floor(Math.random() * 10);
    }
    return str;
}

//php 변수를 자바스크립트로 갖고오는 함수.
function set_data(data) {
    this.json_data = data;
}