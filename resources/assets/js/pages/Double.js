$.on('/double', function() {
	window.double = {};
	
	$('.methods-value ul li a').on('click', function (event) {
		let value = parseFloat($('#amount').val()) || 0,
			all = $('.leftside .profile-block .balance .money').attr('data-balance'),
			thisMethod = $(this).attr('data-method'),
			thisValue = parseFloat($(this).attr('data-value'));

		switch (thisMethod) {
			case 'plus':
				value += thisValue;
				break;
			case 'divide':
				value = parseInt((value / thisValue).toFixed(0));
				break;
			case 'clear':
				value = '';
				break;
			case 'last':
				value = $.getCookie('last');
				break;
			case 'all':
				value = all;
				break;
			case 'multiply':
				value *= thisValue;
				break;
		}
		
		$('#amount').val(value);
		return false;
	});
	
	window.socket.on('roulette', (res) => {
		if(res.type == 'timer') 
		$('#rez-numbr').html((res.time > 9) ? '<i class="far fa-clock"></i> ' + res.time : '<i class="far fa-clock"></i> 0'+res.time); 
		if(res.type == 'slider')
		{
			$('#rez-numbr').html('<i class="far fa-clock"></i> Крутим');
			$('#reletteact').css({
				'transition' : 'transform '+res.slider.time+'ms ease',
				'transform' : 'rotate('+res.slider.rotate+'deg)'
			});
			setTimeout(() => {
				if(res.slider.color == 'red') {
					$('.info .number').show().addClass('red').text(res.slider.num);
				} else if(res.slider.color == 'green') {
					$('.info .number').show().addClass('green').text(res.slider.num);
				} else {
					$('.info .number').show().addClass('blue').text(res.slider.num);
				}
				$.audio('/assets/sounds/winner_chosen.mp3', 0.4);
			}, res.slider.time);
			$.audio('/assets/sounds/roulette-start-3.mp3', 0.4);
		}
		if(res.type == 'newGame') 
		{
			$('#hash').text(res.hash);
			$('.rates-top .bet').text(0);
			$('#roundId').text(res.id);
			$('.bets .bet').slideUp(200, () => {
				$('.bets .bet').remove();
			});
			$('.info .number').hide();
			$('#reletteact').css({
				'transition' : 'transform 0s linear',
				'transform' : 'rotate('+res.slider.rotate+'deg)'
			});
			$('#bank_red').text('0');
			$('#bank_green').text('0');
			$('#bank_black').text('0');
			$('.double-rel').hide();
			$('.double-timer').show();
			$('.info .number').removeClass('red');
			$('.info .number').removeClass('blue');
			$('.info .number').removeClass('green');
			$('#rez-numbr').html('<i class="far fa-clock"></i> ' + res.slider.time);
			$('.tooltip').tooltipster({
				side: 'bottom',
				theme: 'tooltipster-borderless'
			});
			$('.double-last').prepend('<a href="/fair/'+ res.history.hash +'"><div class="hist '+res.history.color+' tooltip-right" title="Победное число: '+res.history.num+'"></div></a>');
			$.audio('/assets/sounds/game-start.mp3', 0.4);
		}
		if(res.type == 'bets') {
		  return double.makeBets(res.bets, res.prices);
		}
	});

	double.makeBets = function(bets, prices)
	{
		var colors = [];
		for(var i in bets)
		{
			let bet = bets[i];
			if(typeof colors[bet.type] == 'undefined') colors[bet.type] = '';
			colors[bet.type] += '<div class="bet flex flex-between" data-userid="'+bet.user_id+'"><div class="left"><div class="ava"><div class="image" style="background: url('+bet.avatar+') no-repeat center center / 100%;"></div></div><div class="username">'+bet.username+'</div><div class="tickets"><span>внес</span></div><div class="amount">'+bet.value+' <i class="fas fa-coins"></i></div></div></div>'
		}

		for(var color in colors) 
		{
			$('.rates-content_'+ color).html(colors[color]);
			$('#bank_' + color).text((typeof prices[color] == 'undefined') ? '0' : prices[color]);
		}
		$.audio('/assets/sounds/bet-4.mp3', 0.4);
	}

	double.getMyBet = function(type, callback) {
		$.ajax({
			url : '/roulette/getBet',
			type : 'post',
			data : {
				type : type
			},
			success : (res) => {
				callback(res);
			},
			error : (err) => {
				console.log(err.responseText);
				callback(0);
			}  
		});
	}

	double.addBet = function() {
		let value = $('#amount').val();
		if(isNaN(value)) return $.wnoty({
			position : 'top-right',
			type: 'error',
			message: 'Неверно введена сумма ставки'
		});
		
		$.ajax({
			url : '/roulette/addBet',
			type : 'post',
			data : {
				bet : value,
				type : $(this).attr('data-bet-type')
			},
			success : (res) => {
				$.wnoty({
					position : 'top-right',
					type: (res.success) ? 'success' : 'error',
					message: res.msg
				});
				$('#amount').val('');
			},
			error : (err) => {
				$.wnoty({
					position : 'top-right',
					type: 'error',
					message: 'Ошибка при отправке данных на сервер'
				});
			}
		});
	}
	$('.betButton').on('click', double.addBet);
}, []);