$.on('/bonus', function() {
	
	function declOfNum(number, titles) {
		let cases = [2, 0, 1, 1, 1, 2];
		return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
	};
	
    $('.getBonus').click(function () {
        $.ajax({
            url: '/bonus/getBonus',
            type: 'post',
            data: {
                recapcha: $('#g-recaptcha-response').val()
            },
            success: function (data) {
                $.wnoty({
                    position: 'top-right',
                    type: data.type,
                    message: data.msg
                });
                grecaptcha.reset();
                return false;
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    });
	window.socket.on('bonus', function (data) {
        if ($.userId() == data.user_id) {
            var line = '';
            for (var i = 0; i < data.line.length; i++) line += '<div class="user" style="background: #495168;border-radius: 5px;"><div class="summ"><b>' + data.line[i].sum + '</b><p>' + declOfNum(data.line[i].sum, ['монета', 'монеты', 'монет']) + '</p></div></div>';
            
            $('#bonus_carousel').html(line);
            $('#bonus_carousel').css({
                transform: 'translate3d(-' + data.ml + 'px, 0px, 0px)',
                transition: 'transform 15500ms cubic-bezier(0, 0, 0, 1) -4ms'
            });
            setTimeout(function () {
                $('.content .slider .carousel.bonus .user:nth-child(81)').addClass('winner');
            }, 16500);
            setTimeout(function () {
                $('.cooldown').show(500);
            }, 19500);
        }
    });
}, []);