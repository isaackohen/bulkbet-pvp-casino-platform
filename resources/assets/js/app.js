
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('jquery-pjax');
require('./routes');

import NProgress from 'nprogress';

const container = '.pageContent';
let cachedResources = [];
let loadedContents = null;

$.on = function(route, callback, cssUrls = []) {
    $(document).on(`page:${route.substr(1)}`, function() {
        $.loadCSS(cssUrls, callback);
    });
};

const initializeRoute = function() {
    let route = $.routes()[`/${$.currentRoute()}`];
    if(route === undefined) {
        $.loadCSS([], () => {});
        console.error(`/${$.currentRoute()} is not routed`);
        NProgress.done();
    } else {
        $.loadScripts(route, function () {
            $(document).trigger(`page:${$.currentRoute()}`);

            let pathname = window.location.pathname.substr(1);
            if(pathname !== $.currentRoute()) $(document).trigger(`page:${window.location.pathname.substr(1)}`);
        });
    }
	
	$.each($('*[data-page-trigger]'), function(i, e) {
        let match = false;
        $.each(JSON.parse(`[${$(e).attr('data-page-trigger').replaceAll('\'', '"')}]`), function(aI, aE) {
            if(window.location.pathname === aE) match = true;
        });
        $(e).toggleClass($(e).attr('data-toggle-class'), match);
    });
	
};

$(document).pjax('a:not(.disable-pjax)', container);

window.redirect = function(page) {
    $.pjax({url: page, container: container})
};

$(document).on('pjax:start', function() {
    NProgress.start();
});

$(document).on('pjax:beforeReplace', function(e, contents) {
    $(container).css({'opacity': 0});
    loadedContents = contents;
});

$(document).on('pjax:end', function() {
    $('[data-async-css]').remove();
    initializeRoute();
	var close = document.querySelectorAll('[data-close="alert"]');
	for (var i = 0; i < close.length; i++) {
		close[i].onclick = function(){
			var div = this.parentElement;
			div.style.opacity = '0';
			setTimeout(function(){div.style.display = 'none';}, 400);
		}
	};
});

$(document).on('pjax:timeout', function(event) {
    event.preventDefault();
});

$.loadScripts = function(urls, callback) {
    let notLoaded = [];
    for(let i = 0; i < urls.length; i++) $.cacheResource(urls[i], function() {
        notLoaded.push(urls[i]);
    });

    if(notLoaded.length > 0) {
		$(".preloading-wrapper").fadeIn();
        let index = 0;
        const next = function() {
            $.getScript(notLoaded[index], index !== notLoaded.length - 1 ? function() {
                index++;
                next();
            } : callback);
        };
        next();
    } else {
		callback();
	}
};

$.loadCSS = function(urls, callback) {
    let loaded = 0;
    const finish = function() {
        if(loadedContents != null) $(container).html(loadedContents);
		$(".preloading-wrapper").fadeOut(0);
        $(container).animate({opacity: 1}, 350, callback);
        NProgress.done();
		$('.tooltip').tooltipster({
			side: 'bottom',
			theme: 'tooltipster-borderless'
		});
		$('.tooltip-right').tooltipster({
			side: 'right',
			theme: 'tooltipster-borderless'
		});
        $(document).trigger('page:ready');
    };

    const stylesheetLoadCallback = function() {
        loaded++;
        if(loaded >= urls.length) setTimeout(finish, 100);
    };

    if(urls.length === 0) finish();
    $.map(urls, function(url) {
        loadStyleSheet(url, stylesheetLoadCallback);
    });
};

function loadStyleSheet(path, fn, scope) {
    const head = document.getElementsByTagName('head')[0], link = document.createElement('link');
    link.setAttribute('href', path);
    link.setAttribute('rel', 'stylesheet');
    link.setAttribute('type', 'text/css');
    link.setAttribute('data-async-css', 'true');

    let sheet, cssRules;
    if ('sheet' in link) {
        sheet = 'sheet';
        cssRules = 'cssRules';
    } else {
        sheet = 'styleSheet';
        cssRules = 'rules';
    }

    let interval_id = setInterval( function() {
        try {
            if (link[sheet] && link[sheet][cssRules].length) {
                clearInterval(interval_id);
                clearTimeout(timeout_id);
                fn.call(scope || window, true, link);
            }
        } catch(e) {} finally {}
    }, 10);
    let timeout_id = setTimeout( function() {
        clearInterval(interval_id);
        clearTimeout(timeout_id);
        head.removeChild(link);
        fn.call(scope || window, false, link);
        console.error(path + ' loading error');
    }, 15000);
    head.appendChild(link);
    return link;
};

$.cacheResource = function(key, callback) {
    if(cachedResources.includes(key)) return;
    cachedResources.push(key);
    console.log(`${key} is loaded`);
    return callback();
};

$.currentRoute = function() {
    let page = window.location.pathname;
    const format = function(skip) {
        return page.count('/') > skip ? page.substr(skip === 1 ? 1 : page.indexOf('/'+page.split('/')[skip]), page.lastIndexOf('/') - 1 ) : page.substr(1);
    };
    return format(1);
};

String.prototype.replaceAll = String.prototype.replaceAll || function(string, replaced) {
    return this.replace(new RegExp(string, 'g'), replaced);
};

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.substring(1);
};

String.prototype.count = function(find) {
    return this.split(find).length - 1;
};

$.audio = function(audio, vol) {
    if (sounds == 'off') {}
	else{
		var newgames = new Audio();
		newgames.src = audio;
		newgames.volume = vol;
		newgames.play();
    }
};

$.isGuest = function() {
    return window.Laravel.userId == null;
};

$.userId = function() {
    return window.Laravel.userId;
};

$.userAccess = function() {
    return window.Laravel.access;
};

$.urlParam = function(name) {
    const results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) return null;
    return decodeURI(results[1]) || 0;
};

$.setBalance = function(amount) {
    $('.money').text(amount.toFixed(3).replace(/.$/,''));
	$('#money').val(amount.toFixed(3).replace(/.$/,''));
};

$(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
        $('.leftside').addClass('fixed');
        $('.chat').addClass('fixed');
    } else {
        $('.leftside').removeClass('fixed');
        $('.chat').removeClass('fixed');
    }
});

var sounds = $.getCookie('sounds')
$(document).ready(function (e) {
	$(document).trigger('pjax:start');
	$('#sounds').hide();
	$('#soundsOn').hide();
		
    if (sounds == 'off') {
        $('#sounds').show();
        $('#soundsOn').hide();
    }else{
        $('#sounds').hide();
        $('#soundsOn').show();
    }
	
    $(document).on('click', '#sounds', function(e) {
        $('#sounds').hide();
        $('#soundsOn').show();
        sounds = 'on';
		$.setCookie('sounds', 'on');
    });

    $(document).on('click', '#soundsOn', function(e) {
        $('#sounds').show();
        $('#soundsOn').hide();
        sounds = 'off';
		$.setCookie('sounds', 'off');
    });
    
    window.socket.on('roulette', (res) => {
        if(res.type == 'newGame'){
            $('#getPriceDouble').html('0 <i class="fas fa-coins"></i>');  
        }
        if(res.type == 'bets') {
          $('#getPriceDouble').html(res.allBank + ' <i class="fas fa-coins"></i>');  
        }
     });
    
    window.socket.on('battle.newBet', function(data) {
		$('#getPriceBattle').html(data.allBank + ' <i class="fas fa-coins"></i>');
    });
    
    window.socket.on('battle.newGame', function(data) { 
        $('#getPriceBattle').html('0 <i class="fas fa-coins"></i>');
    });
    
    window.socket.on('jackpot.newBet', function(data) { 
        $('#getPriceJackpot').html(data.allBank + ' <i class="fas fa-coins"></i>');
    });
    
    window.socket.on('jackpot.newGame', function(data) {
        $('#getPriceJackpot').html(data.allBank + ' <i class="fas fa-coins"></i>');
    });
    
    window.socket.on('new.flip', function (data) {
        $('#getPriceCoin').html(data.allBank + ' <i class="fas fa-coins"></i>');
    });
    
    window.socket.on('end.flip', function (data) {
        $('#getPriceCoin').html(data.allBank + ' <i class="fas fa-coins"></i>');
    });
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.close').click(function (e) {
        $('.popup, .overlay, body').removeClass('active');
        return false;
    });
    
    chatOn();
    resize();
	
    $('#openChat').click(function(){
        if ($('body').is('.chat-mobile')) {
            $('body').removeClass('chat-mobile');
            $('.chat .open > span').text('<');
            $('body').addClass('chat-closed');
        } else {
            $('body').addClass('chat-mobile');
            $('.chat .open > span').text('>');
            $('body').removeClass('chat-closed');
        }
        if($.getCookie('chat') === 'open') {
			$.setCookie('chat', 'closed');
        } else {
			$.setCookie('chat', 'open');
        }
    });
    
    $('#menuOpen').click(function(){
        if ($('body').is('.menu-open')) {
            $('body').removeClass('menu-open');
            $('#menuOpen span').text('>');
        } else {
            $('body').addClass('menu-open');
            $('#menuOpen span').text('<');
        }
        if($.getCookie('chat') === 'open') {
			$.setCookie('chat', 'closed');
            $('body').addClass('chat-closed');
            $('body').removeClass('chat-mobile');
        }
    });
    
    $(window).resize(function(){
       resize(); 
    });
    
    function resize() {
        if(window.innerWidth <= 610) {
            $('body').removeClass('chat-mobile');
            $('body').addClass('chat-closed');
            $.setCookie('chat', 'closed');
        }
        $('.chat .messages .scroll').scrollTop(1e7);
    };
    
    
    function chatOn() {
		if($.getCookie('chat') === 'closed') {
			$('body').addClass('chat-closed');
			$('.chat .open > span').text('<');
			$('body').removeClass('chat-mobile');
		} else {
			$('body').removeClass('chat-closed');
			$('.chat .open > span').text('>');
			$('body').addClass('chat-mobile');
		}
    };
    
    $('.overlay').click(function (e) {
        var target = e.target || e.srcElement;
        if (!target.className.search('overlay')) {
            $('.overlay, .popup, body').removeClass('active');
        }
    });
    
    $('[rel=popup]').click(function (e) {
        showPopup($(this).attr('data-popup'));
        return false
    });
	
	var close = document.querySelectorAll('[data-close="alert"]');
	for (var i = 0; i < close.length; i++) {
		close[i].onclick = function(){
			var div = this.parentElement;
			div.style.opacity = '0';
			setTimeout(function(){div.style.display = 'none';}, 400);
		}
	};
    
    $('.list-pay .item').click(function (e) {
        if (!$(this).is('.active')) {
            $(this).parent().find('.item').removeClass('active');
            $(this).addClass('active');
            checkSystem();
            calcSum();
        }
    });
    
    $('#value').on('change keydown paste input', function () {
        calcSum();
    });

    function calcSum() {
        if ($('.list-pay .active').data('type') == 'qiwi') {
            var perc = 4;
            var com = 1;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '% + ' + com + 'руб.');
        } else if ($('.list-pay .active').data('type') == 'yandex') {
            var perc = 5;
            var com = 0;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '%');
        } else if ($('.list-pay .active').data('type') == 'webmoney') {
            var perc = 6;
            var com = 0;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '%');
        } else if ($('.list-pay .active').data('type') == 'visa') {
            var perc = 4;
            var com = 50;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '% + ' + com + 'руб.');
        }
        var val = $('#value').val();
        var comission = Math.round(val - (val / 100 * perc + com));
        if (!val) comission = 0;
        if (comission <= 1) comission = 0;
        $('#valwithcom').html(comission + ' руб.');
    };
	
    $('#chh').click(function () {
        $('#chh').attr('checked', 'checked');
        if ($(this).prop('checked') == true) {
            $('#withdraw').removeAttr('disabled');
        } else {
            $('#withdraw').attr('disabled', 'false');
            $('#chh').removeAttr('checked');
        }
    });

    function checkSystem() {
        if ($('.list-pay .active').data('type') == 'qiwi') {
            var perc = 4;
            var com = 1;
            var val = 105;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
                var val = 1000;
            }
            var comission = val + (val / 100 * perc) + (com * 10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'Мин. сумма: ' + comission + ' монет');
            $('#wallet').attr('placeholder', '7900xxxxxxx');
            $('#com').html(perc + '% + ' + com + 'руб.');
        } else if ($('.list-pay .active').data('type') == 'yandex') {
            var perc = 0;
            var com = 0;
            var val = 100;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
                var val = 100;
            }
            var comission = val + (val / 100 * perc) + (com * 10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'Мин. сумма: ' + comission + ' монет');
            $('#wallet').attr('placeholder', '41001хххххххххх');
            $('#com').html(perc + '%');
        } else if ($('.list-pay .active').data('type') == 'webmoney') {
            var perc = 6;
            var com = 0;
            var val = 100;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
                var val = 100;
            }
            var comission = val + (val / 100 * perc) + (com * 10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'Мин. сумма: ' + comission + ' монет');
            $('#wallet').attr('placeholder', 'R536xxxxxxxxx');
            $('#com').html(perc + '%');
        } else if ($('.list-pay .active').data('type') == 'visa') {
            var perc = 4;
            var com = 50;
            var val = 10000;
            if ($.userAccess() == 'youtuber') {
                var perc = 0;
                var com = 0;
                var val = 10000;
            }
            var comission = val + (val / 100 * perc) + (com * 10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'Мин. сумма: ' + comission + ' монет');
            $('#wallet').attr('placeholder', '4700xxxxxxxxxxxx');
            $('#com').html(perc + '% + ' + com + 'руб.');
        }
    };
	
    $('#wallet').keydown(function (event) {
        if (event.shiftKey === true) return false;
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
            (event.keyCode == 65 && event.ctrlKey === true) ||
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            return;
        } else {
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105) && (event.keyCode < 65 || event.keyCode > 90)) {
                event.preventDefault();
            }
        }
    });
    $('#withdraw').click(function () {
        var system = $('.list-pay .active').attr('data-type');
        var value = $('#value').val();
        var wallet = $('#wallet').val();
        if (!$('#chh').attr('checked')) {
            $.wnoty({
                position: 'top-right',
                type: 'error',
                message: 'Вы не подтвердили правильность введенных даных'
            });
            return false;
        }
		
		$.ajax({
            url: '/withdraw',
            type: 'post',
            data: {
                system: system,
                value: value,
                wallet: wallet
            },
            success: function (data) {
                $('.popup, .overlay, body').removeClass('active');
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

    $('.chat .scroll').scrollTop(1e7);
	
    window.socket.on('message', function (msg) {
        if ($.userId() == msg.user) {
            $.wnoty({
                position: 'top-right',
                type: msg.type,
                message: msg.msg
            });
        }
    });
    window.socket.on('updateBalance', function (data) {
        if ($.userId() == data.id) $('.money').text(data.balance);
        if ($.userId() == data.id) $('#money').val(data.balance);
    });
	
    window.socket.on('online', function (data) {
        $('.on').text(data);
    });
    window.socket.on('chat', function (data) {
        let msg = JSON.parse(data);
        var chat = $('.messages');
        /* ban panel */
        if ($.userAccess() == 'admin') {
			if (msg.access != 'admin' || msg.access != 'moder') {
				var mute = `<a class="delete tooltip" title="Замутить" onclick="$.mute('` + msg.user_id + `','`+ msg.username +`')"><i class="fas fa-ban"></i></a>`;
			}
            var panel = '<a class="delete tooltip" title="Удалить" onclick="$.chatdelet(' + msg.time2 + ')"><i class="fas fa-trash-alt"></i></a>';
        } else {
            var mute = '';
            var panel = '';
        }
		if($.userId() != null){
			var transfer = `<a class="delete tooltip" title="Перевод" onclick="$.transfer('` + msg.user_id + `','`+ msg.username +`')"><i class="fas fa-gift"></i></a>`;
		} else {
			var transfer = '';
		}
        var name = msg.username;
        if (msg.access == 'admin') {
            name = '<span style="color:#ffd400;">[АМС] ' + msg.username + '</span>';
        }
        if (msg.access == 'moder') {
            name = '<span style="color:#70afe6;">[М] ' + msg.username + '</span>';
        }
        if (msg.access == 'youtuber') {
            name = '<span style="color:#dc7979;">[YT] ' + msg.username + '</span>';
        } 
        var messages = msg.messages;
        chat.find('.scroll').append(
            
            '<div class="msg flex flex-between" id="chatm_' + msg.time2 + '">' +
                '<div class="ava"><div class="image" style="background: url(' + msg.avatar + ') no-repeat center center / 100%;"></div></div>' +
                '<div class="r"><div class="top flex flex-between"><span>' + name + '</span><div class="data"><b>' + msg.user_id + '</b></div>'+ panel + mute + transfer +'</div><div class="mess">' + messages + '</div>' +
                '</div>'+
            '</div>');
            
        $('.chat .messages .scroll').scrollTop(1e7);
         $.audio('/assets/sounds/chat-message-add.mp3', 0.4);
        if ($('.chat .msg').length >= 20) $('.chat .msg:nth-child(1)').remove();
    });
	
    window.socket.on('chatdel', function (data) {
        let info = JSON.parse(data);
        $('#chatm_' + info.time2).remove();
    });
	
    window.socket.on('clear', function (data) {
        $('.chat .scroll').html('');
    });
	
    $('.chat-input').bind("enterKey", function (e) {
        var input = $(this);
        var msg = input.val();
        if (msg != '') {
            $.post('/chat', {
                messages: msg
            }, function (data) {
                if (data) {
                    if (data.status == 'success') {
                        input.val('');
                    } else {
                        input.val('');
                    }
                    $.wnoty({
                        position: 'top-right',
                        type: data.status,
                        message: data.message
                    });
                } else
                    input.val('');
            });
        }
    });
	
	
    $('.chat-input').keyup(function (e) {
        if (e.keyCode == 13) {
            $(this).trigger("enterKey");
        }
    });
	
    $('.btn-send').on('click', function (event) {
        $('.chat-input').trigger("enterKey");
    });
	
	$(document).on('click', '#send_mute', function(e) {
		var msg = '/ban ' + $('#mute_id').val() + ' ' + $('#mute_time').val();
		$.post('/chat', {
			messages: msg
		}, function (data) {
			if (data) {
				$.wnoty({
					position: 'top-right',
					type: data.status,
					message: data.message
				});
			}
			$('.close').click();
		});
    });
	
	$(document).on('click', '#send_transfer', function(e) {
		var msg = '/send ' + $('#recipient_id').val() + ' ' + $('#transfer_sum').val();
		$.post('/chat', {
			messages: msg
		}, function (data) {
			if (data) {
				$.wnoty({
					position: 'top-right',
					type: data.status,
					message: data.message
				});
			}
			$('.close').click();
		});
    });
	
});

function showPopup(el) {
    if ($('.popup').is('.active')) {
        $('.popup').removeClass('active');
    }
    $('.overlay, body, .popup.' + el).addClass('active');
};

$.chatdelet = function(id) {
    $.post('/chatdel', {
        messages: id
    }, function (data) {
        if (data) {
            $.wnoty({
                position: 'top-right',
                type: data.status,
                message: data.message
            });
        }
    });
};

$.transfer = function(user_id, username) {
	$('#recipient').html('Получатель: ' + username);
	$('#recipient_id').val(user_id);
	showPopup('popup-transfer');
};

$.mute = function(user_id, username) {
	$('#mute_user').html('Пользователь: ' + username);
	$('#mute_id').val(user_id);
	showPopup('popup-mute');
};

$.copyToClipboard = function(element) {
    var $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(element).val()).select();
    document.execCommand('copy');
    $temp.remove();
    $.wnoty({
        position: 'top-right',
        type: 'success',
        message: 'Скопировано в буфер обмена!'
    });
};

initializeRoute();
$(container).css({'opacity': 0});