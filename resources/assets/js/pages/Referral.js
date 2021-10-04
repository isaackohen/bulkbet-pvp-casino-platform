$.on('/referral', function() {
	$('.promoButton').click(function () {
        var code = $('.promoCode').val();
        $.ajax({
            url: '/ref/activate',
            type: 'post',
            data: {
                code: code
            },
            success: function (data) {
                $.wnoty({
                    position: 'top-right',
                    type: data.type,
                    message: data.msg
                });
                return false;
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    });
	
	$('.getMoney').click(function () {
        $.ajax({
            url: '/ref/getMoney',
            type: 'post',
            success: function (data) {
                $.wnoty({
                    position: 'top-right',
                    type: data.type,
                    message: data.msg
                });
                $('.getMoney').hide();
                $('.moneyRef .to-get').html('Доступно для получения: <span>0</span> <i class="fas fa-coins"></i>');
                return false;
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    });
}, []);