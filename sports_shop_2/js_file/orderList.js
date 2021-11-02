let delivery = {
    check_order: function() {
        loadJQuery();
        /* 체크 되어있는 태그만 확인 */
        document
            .querySelectorAll('input[name=order_check]:checked')
            .forEach(function(item) {
                let order = item.parent().parent().eq(7);
                item.setAttribute('innerHTML', '주문 준비 중');
            });
    },
};

function loadJQuery() {
    var oScript = document.createElement('script');
    oScript.type = 'text/javascript';
    oScript.charset = 'utf-8';
    oScript.src = 'http://code.jquery.com/jquery-1.6.2.min.js';
    document.getElementsByTagName('head')[0].appendChild(oScript);
}