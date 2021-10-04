/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/pages/Pvp.js":
/*!******************************************!*\
  !*** ./resources/assets/js/pages/Pvp.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$.on('/pvp', function () {
  var spinArray = ['animation2160'];
  $('.methods-value ul li a').on('click', function (event) {
    var value = parseFloat($('#amount').val()) || 0,
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
  window.socket.on('new.flip', function (data) {
    var html = '';
    html += '<div class="game-coin flip_' + data.id + '">';
    html += '<div class="top">';
    html += '<div class="left">';
    html += '<div class="players block">';
    html += '<div class="user">';
    html += '<div class="ava user-link">';
    html += '<img src="' + data.avatar + '">';
    html += '</div>';
    html += '<div class="info">';
    html += '<span class="user-link">' + data.username + '</span>';
    html += '</div>';
    html += '</div>';
    html += '<div class="vs">vs</div>';
    html += '<div class="user">';
    html += '<div class="ava user-link">';
    html += '<img src="/assets/images/telegram.png">';
    html += '</div>';
    html += '<div class="info">';
    html += '<span class="user-link">Ожидаем...</span>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '<div class="go">';
    html += '<a href="#" class="joinGame" onclick="$.joinRoom(' + data.id + ')">присоединиться</a>';
    html += '</div>';
    html += '<div class="info block">';
    html += '<span><i class="fas fa-coins"></i> ' + data.price + '</span>';
    html += '</div>';
    html += '<div class="status block">';
    html += '<div class="game-status tooltip" title="Ожидаем..."></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $('.games-active').append(html);
    $.audio('/assets/sounds/bet-4.mp3', 0.4);
  });
  window.socket.on('end.flip', function (data) {
    $('.flip_' + data.game.id).remove();
    var html = '';
    html += '<div class="game-coin flip-block_' + data.game.id + '">';
    html += '<div class="top">';
    html += '<div class="left">';
    html += '<div class="players block">';
    html += '<div class="user">';
    html += '<div class="ava user-link">';
    html += '<img src="' + data.user1.avatar + '">';
    html += '</div>';
    html += '<div class="info">';
    html += '<span class="user-link">' + data.user1.username + '</span>';
    html += '<p>' + data.user1.from + ' - ' + data.user1.to + ' <i class="fas fa-ticket-alt"></i></p>';
    html += '</div>';
    html += '</div>';
    html += '<div class="vs">vs</div>';
    html += '<div class="user">';
    html += '<div class="ava user-link">';
    html += '<img src="' + data.user2.avatar + '">';
    html += '</div>';
    html += '<div class="info">';
    html += '<span class="user-link">' + data.user2.username + '</span>';
    html += '<p>' + data.user2.from + ' - ' + data.user2.to + ' <i class="fas fa-ticket-alt"></i></p>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '<div class="avatars">';
    html += '<div class="tridiv">';
    html += '<div class="time" id="timer_' + data.game.id + '">';
    html += '<span id="count_num_' + data.game.id + '">5s</span>';
    html += '</div>';
    html += '<div id="coin-flip-cont_' + data.game.id + '" style="display: none;">';
    html += '<div id="coin_' + data.game.id + '" class="animation2160">';
    html += '<div class="front"><img src="' + data.winner.avatar + '"></div>';
    html += '<div class="back"><img src="' + data.loser.avatar + '"></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '<div class="win-ticket tooltip" title="Счастливый билет" style="display:none;">';
    html += '<span>??? <i class="fas fa-ticket-alt"></i></span>';
    html += '</div>';
    html += '<div class="info block">';
    html += '<span><i class="fas fa-coins"></i> ' + data.game.price + '</span>';
    html += '</div>';
    html += '<div class="status block">';
    html += '<div class="game-status done tooltip" title="Игра проводится..."></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $('.games-ended').prepend(html);
    $.audio('/assets/sounds/end-flip.mp3', 0.4);
    setTimeout(function () {
      handleTimer(data.game.id);
    }, 2000);
    setTimeout(function () {
      $('.flip-block_' + data.game.id + ' .win-ticket').show();
      $('.flip-block_' + data.game.id + ' .win-ticket span').html(data.winner.ticket + ' <i class="fas fa-ticket-alt"></i>');
      $('.flip-block_' + data.game.id + ' .status').html('<div class="game-status done tooltip" title="Игра завершена..."></div>');
      $('.flip-block_' + data.game.id + ' .center .front').addClass('winner_a');
      $('.flip-block_' + data.game.id + ' .check-random .btn').show();
    }, 13000);
    $('.games-ended .game-coin:nth-child(6)').remove();
  });

  $.createRoom = function () {
    var value = parseFloat($('#amount').val());

    if (isNaN(value)) {
      $.wnoty({
        position: 'top-right',
        type: 'error',
        message: 'Вы забыли указать сумму!'
      });
      return;
    }

    $.ajax({
      url: '/flip/newGame',
      type: 'post',
      data: {
        value: value
      },
      success: function success(data) {
        $('#amount').val('');
        $.wnoty({
          position: 'top-right',
          type: data.type,
          message: data.msg
        });
      },
      error: function error(data) {
        console.log(data.responseText);
        $.wnoty({
          position: 'top-right',
          type: 'error',
          message: 'Ошибка!'
        });
      }
    });
  };

  $.joinRoom = function (id) {
    $.ajax({
      url: '/flip/joinRoom',
      data: {
        id: id
      },
      type: 'POST',
      dataType: 'JSON',
      success: function success(data) {
        $.wnoty({
          position: 'top-right',
          type: data.type,
          message: data.msg
        });
      }
    });
  };

  function getSpin() {
    var spin = spinArray[Math.floor(Math.random() * spinArray.length)];
    return spin;
  }

  function handleTimer(id) {
    var countdownElement = document.getElementById('count_num_' + id),
        seconds = 5,
        second = 0,
        interval;
    interval = setInterval(function () {
      countdownElement.firstChild.data = seconds - second + 's';

      if (second >= seconds) {
        $('#timer_' + id).hide();
        $('#coin-flip-cont_' + id).show();
        $('#coin_' + id).addClass(getSpin());
        clearInterval(interval);
        setInterval(function () {
          $('.flip-block_' + id + ' .front').addClass('winner_a');
        }, 4000);
      }

      second++;
    }, 1000);
  }
}, []);

/***/ }),

/***/ 5:
/*!************************************************!*\
  !*** multi ./resources/assets/js/pages/Pvp.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/resources/assets/js/pages/Pvp.js */"./resources/assets/js/pages/Pvp.js");


/***/ })

/******/ });