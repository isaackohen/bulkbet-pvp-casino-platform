$(document).ready(function() {
	$.post('/admin/getBalance', function(data) {
		$('#fkBal').text(data);
	});
    var socket = io.connect(':49310');
	socket.on('real_online', function(data) {
		console.log(data);
        $('#online').text(data); 
    });
	socket.on('jackpot.newBet', function(data) {
        var chances = '';
        for(var i = 0; i < data.chances.length; i++) {
			var room = "'"+ data.chances[i].room +"'";
            chances += '<tr><td><img src="' + data.chances[i].avatar + '" width="35px" class="img-circle"></td>';
            chances += '<td class="clip">' + data.chances[i].username + '</td>';
            chances += '<td>' + data.chances[i].chance + '%</td>';
            chances += '<td><button class="btn btn-primary btn-outline btn-xs" onclick="gotRoulette('+ room +',' + data.chances[i].id + ')">подкрутить</button></td></tr>';
        }
        
        $('#chance_'+data.room).html(chances);
    });
	
	socket.on('jackpot.newGame', function(data) {
        $('#chance_'+data.room).html('');
    }); 
	socket.on('chat', function (data) {
		msg = JSON.parse(data);
		var chat = $('.chat-content');
		var messages = msg.messages;
		chat.find('.chatapp-chat-nicescroll-bar').append(
				'<li class="friend">' +
				'<div class="friend-msg-wrap">' +
				'<img class="user-img img-circle block pull-left" src="' + msg.avatar + '" alt="user">' +
				'<div class="msg pull-left">' +
				'<p>' + messages + '</p>' +
				'<div class="msg-per-detail text-right">' +
				'<span class="msg-time txt-light">' + msg.username + '</span>' +
				'</div>' +
				'</div>' +
				'<div class="clearfix"></div>' +
				'</div>' +
				'</li>');
		$('.chatapp-chat-nicescroll-bar').scrollTop(1e7);
		if($('.friend').length >= 20) $('.friend:nth-child(1)').remove();
	});
	socket.on('roulette', function(data) {
		if(data.type == 'newGame') {
			$('#dlred').text('0');
			$('#dlgrenn').text('0');
			$('#dlblack').text('0');
		}
		if(data.type == 'admin') {
			if(data.prices.red) $('#dlred').text(data.prices.red);
			if(data.prices.green) $('#dlgrenn').text(data.prices.green);
			if(data.prices.black) $('#dlblack').text(data.prices.black);
		}
	});
	function sendMessage() {
        var message = $('#chatmess').val();
        var user_id = $('#users').val();
        $.ajax({
            url: '/admin/chatSend',
            type: 'POST',
            data: {
                type: 'push',
				user_id: user_id,
                message: message
            },
            success: function(data) {
				$('#chatmess').val('');
            }
        });
    }
	$('#chatmess').keypress(function(e) {
        if (e.keyCode == 13) {
            sendMessage();
            return false;
        }
    });
    $('#chatsend').click(function() {
        sendMessage();
        return false;
    });
    $('#url').keyup(function() {
        var url = $('#url').val();
        console.log($('#url').val());
        $.ajax({
            type: 'post',
            url: '/admin/getVKUser',
            data: {url: url},
            success: function(data){
                if(url) {
                    $('#prof').show();
                    $('#name').val(data[0].first_name+' '+data[0].last_name);
                    $('#vkId').val(data[0].id);
                    $('#ava').attr("src", data[0].photo_max);
                    console.log(data);
                } else {
                    $('#prof').hide();
                }
            }
        });
    });
	$('.gotBattle').on('click', function() {
		var color = $(this).data('color');
		$.ajax({
			url: '/admin/gotBattle',
			type: "POST",
			data: {
				color: color
			},
			success: function(data) {
                $.toast({
                    position: 'top-right',
                    text: data.msg,
                    icon: data.type
                });
				return;
			}
		});
	});
	$('.gotDouble').on('click', function() {
		var color = $(this).data('color');
		$.ajax({
			url: '/admin/gotDouble',
			type: "POST",
			data: {
				color: color
			},
			success: function(data) {
                $.toast({
                    position: 'top-right',
                    text: data.msg,
                    icon: data.type
                });
				return;
			}
		});
	});
	$('.betJackpot').on('click', function() {
		var user = $('#users_jackpot').val();
		var sum = $('#sum_jackpot').val();
		var room = $('#room_jackpot').val();
		$.ajax({
			url: '/admin/betJackpot',
			type: "POST",
			data: {
				user: user,
				sum: sum,
				room: room
			},
			success: function(data) {
                $.toast({
                    position: 'top-right',
                    text: data.msg,
                    icon: data.type
                });
				return;
			}
		});
	});
	$('.betDouble').on('click', function() {
		var user = $('#users_double').val();
		var sum = $('#sum_double').val();
		var color = $('#color_double').val();
		$.ajax({
			url: '/admin/betDouble',
			type: "POST",
			data: {
				user: user,
				sum: sum,
				color: color
			},
			success: function(data) {
                $.toast({
                    position: 'top-right',
                    text: data.msg,
                    icon: data.type
                });
				return;
			}
		});
	});
	$('.betDice').on('click', function() {
		var user = $('#users_dice').val();
		var sum = $('#sum_dice').val();
		var perc = $('#perc_dice').val();
		var range = $('#range').val();
		$.ajax({
			url: '/admin/betDice',
			type: "POST",
			data: {
				user: user,
				sum: sum,
				perc: perc,
				range: range
			},
			success: function(data) {
                $.toast({
                    position: 'top-right',
                    text: data.msg,
                    icon: data.type
                });
				return;
			}
		});
	});
});
$('#balance').on('change keydown paste input', function() {
    var val = $(this).val();
    var rub = val;
    if(!val) rub = 0;
    $('#rub').val(rub + ' руб.');
});
// рулетка подкрутка
function gotRoulette(room, user_id) {
	console.log(room);
    $.ajax({
        url: '/admin/gotJackpot',
        type: "POST",
        data: {
            'type': 'push',
            'room': room,
            'user_id': user_id
        },
        success: function(data) {
            $.toast({
                position: 'top-right',
                text: data.msg,
                icon: data.type
            });
            return;
        }
    });
}