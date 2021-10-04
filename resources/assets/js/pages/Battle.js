import * as d3 from 'd3';
import 'd3-path';

$.on('/battle', function() {
	
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
	
	build(window.build);
	window.socket.on('battle.newBet', function(data) {
		var colors = [];
		for(var i in data.bets) {
			var bet = data.bets[i];
			if(typeof colors[bet.color] == 'undefined') colors[bet.color] = '';
			colors[bet.color] += '<div class="bet flex flex-between"><div class="left"><div class="ava"><div class="image" style="background: url('+ bet.avatar +') no-repeat center center / 100%;"></div></div><div class="username">'+ bet.username +'</div><div class="tickets"><span>внес</span></div><div class="amount">'+ bet.price +' <i class="fas fa-coins"></i></div></div></div>';
		}
		for(var color in colors) {
			$('#'+ color +'_list').html(colors[color]);
		}
		$("#blue_sum").html(data.bank[1] + ' <i class="fas fa-coins"></i>');
		$("#red_sum").html(data.bank[0] + ' <i class="fas fa-coins"></i>');
		$('#red_persent').text(data.chances[0] + '%');
		$('#blue_persent').text(data.chances[1] + '%');
		$('#red_factor').text('x'+data.factor[0]);
		$('#blue_factor').text('x'+data.factor[1]);
		$('#red_tickets').text(data.tickets[0]);
		$('#blue_tickets').text(data.tickets[1]);
		$.audio('/assets/sounds/bet-4.mp3', 0.4);
		build(data.chances[1] / 100);
	});
	window.socket.on('battle.timer', function(data) {
		var time = data.time;
		var pretime = data.pretime;
		$("#timer").html('<i class="far fa-clock"></i> ' + time);
		if(time <= 3) {
			$("#timer").css('color','#8a8ef9');
		}
		if(time <= time && time >= 3) $.audio('/assets/sounds/timer-tick-quiet.mp3', 0.4);
		if(time <= 3) $.audio('/assets/sounds/timer-tick-last-5-seconds.mp3', 0.4);
	});
	window.socket.on('battle.slider', function(data) {
		$("#circle").css('transition', 'transform 4s cubic-bezier(0.15, 0.15, 0, 1)');
		$("#circle").css('transform', 'rotate(' + (3600 + data.ticket * 0.36) + 'deg)');
		$("#timer").css('font-size', '1em');
		$("#timer").html('<i class="fas fa-play"></i>');
		$("#timer").css('color','#99aed7');
		$.audio('/assets/sounds/roulette-start-3.mp3', 0.4);
		setTimeout(function() {
			$("#history").prepend(' <a href="/fair/'+ data.game.hash +'"><div class="hist '+ data.game.winner_team +' tooltip-right" title="Проверить игру"></div></a>');
		}, 4000);
	});
	window.socket.on('battle.newGame', function(data) {
		$("#timer").css('-webkit-animation', 'blink 2s linear infinite');
		$("#timer").css('animation', 'blink 2s linear infinite');
		$("#timer").html('<i class="far fa-clock"></i> 15');
		$("#red_list").html('');
		$("#blue_list").html('');
		$("#circle").css('transition', '');
		$("#circle").css('transform', 'rotate(0deg)');
		$("#red_persent").html('50%');
		$("#blue_persent").html('50%');
		$("#red_tickets").html(500);
		$("#blue_tickets").html(501);
		$("#red_factor").html('x2');
		$("#blue_factor").html('x2');
		$("#blue_sum").html('0');
		$("#red_sum").html('0');
		$("#hash").html(data.hash);
		$("#roundId").text('#'+data.id);
		build(0.5);
		$.audio('/assets/sounds/game-start.mp3', 0.4);
	});

	$.bet = function(type) {
		$.ajax({
			url: '/battle/addBet',
			type: 'post',
			data: {
				type: type,
				sum: $('#amount').val()
			},
			success: function(data) {
				$.wnoty({
					position : 'top-right',
					type: data.type,
					message: data.msg
				});
			}
		})
	}

	function build(blue_cur) {
	  var blue = d3.arc()
		  .innerRadius(150)
		  .outerRadius(140)
		  .startAngle(0)
		  .endAngle(2 * Math.PI * blue_cur);
	  $("#blue").attr('d', blue());
	  var red = d3.arc()
		  .innerRadius(150)
		  .outerRadius(140)
		  .startAngle(2 * Math.PI * blue_cur)
		  .endAngle(2 * Math.PI);
	  $("#red").attr('d', red());
	}
}, []);