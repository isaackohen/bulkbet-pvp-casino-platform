$.on('/fair', function() {
	$(function() {
		$('.checkHash').click(function () {
        var hash = $('#hash').val();
        $.ajax({
            url: '/fair/check',
            type: 'post',
            data: {
                hash: hash
            },
            success: function (data) {
                if (data.success) {
                    $('#round').val(data.round);
                    $('#number').val(data.number);
                    $('.fair .col').slideDown();
                } else {
                    $('#round').val('');
                    $('#number').val('data.number');
                    $('.fair .col').slideUp();
                }
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
	});
}, []);