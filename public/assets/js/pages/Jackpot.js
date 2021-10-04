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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/pages/Jackpot.js":
/*!**********************************************!*\
  !*** ./resources/assets/js/pages/Jackpot.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$.on('/', function () {
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

  $.room = function () {
    return window.room;
  };

  if ($('.title.' + $.room()).hasClass('small')) {
    $('#titleRoom').text('(Бомж)');
  }

  if ($('.title.' + $.room()).hasClass('classic')) {
    $('#titleRoom').text('(Классик)');
  }

  if ($('.title.' + $.room()).hasClass('major')) {
    $('#titleRoom').text('(Мажор)');
  }

  $('.rooms').find('li').removeClass('active');
  $('.rooms li.' + $.room()).addClass('active');
  $('.makeBet').on("click", function () {
    $.post('/newBet', {
      sum: $('#amount').val(),
      room: $.room()
    });
    localStorage.setItem('last', $('#amount').val());
  });
  window.socket.on('jackpot.newBet', function (data) {
    var bet = '';
    data.bets.forEach(function (info) {
      bet += '<div class="bet flex flex-between">';
      bet += '<div class="left">';
      bet += '<div class="ava">';
      bet += '<div class="image" style="background: url(' + info.avatar + ') no-repeat center center / 100%;"></div>';
      bet += '</div>';
      bet += '<div class="username">' + info.username + '</div>';
      bet += '<div class="tickets"><span>билеты</span> <b>' + info.from + ' - ' + info.to + '</b></div>';
      bet += '<div class="amount points">' + info.sum + ' <i class="fas fa-coins"></i></div>';
      bet += '</div>';
      bet += '<div class="right">';
      bet += '<div class="date percent">' + info.chance + '%</div>';
      bet += '</div>';
      bet += '</div>';
    });
    var chances = '';

    for (var i = 0; i < data.chances.length; i++) {
      chances += '<div class="user tooltip" title="' + data.chances[i].username + '">';
      chances += '<div class="chance">' + data.chances[i].chance + '%</div>';
      chances += '<div class="image" style="background: url(' + data.chances[i].avatar + ') no-repeat center center / 100%;"></div>';
      chances += '</div>';
    }

    $('#roombank_' + data.room).html(data.game.price + ' <i class="fas fa-coins"></i>');
    $('#gamebank_' + data.room).text(data.game.price);
    $('#players_' + data.room).text(data.chances.length);
    $('#bets_' + data.room).html(bet);
    $('#chances_' + data.room).html(chances);
    $.audio('/assets/sounds/bet-4.mp3', 0.4);
    $('.tooltip').tooltipster({
      side: 'bottom',
      theme: 'tooltipster-borderless'
    });
  });
  window.socket.on('jackpot.timer', function (data) {
    var sec = data.sec,
        min = data.min,
        time = data.time,
        timer = data.timer;
    if (sec < 10) sec = '0' + sec;
    if (min < 10) min = '0' + min;
    $('#timer-svg_' + data.room).css({
      strokeDashoffset: 282.783 - 282.783 / timer * time + 10 + 'px'
    });
    $('#timerShadow_' + data.room).css({
      strokeDashoffset: 282.783 - 282.783 / timer * time + 10 + 'px'
    });
    $('#time_' + data.room).html('<i class="far fa-clock"></i> ' + min + ':' + sec);
  });
  window.socket.on('jackpot.ngTimer', function (data) {
    $('#timer-svg_' + data.room).css({
      strokeDashoffset: 282.783 - 282.783 / 19 * data.ngtime + 'px'
    });
    $('#timerShadow_' + data.room).css({
      strokeDashoffset: 282.783 - 282.783 / 19 * data.ngtime + 'px'
    });
    if (data.ngtime < 10) data.ngtime = '0' + data.ngtime;
    $('#time_' + data.room).html('<i class="far fa-clock"></i> ' + '00:' + data.ngtime);
    $('#timeline_' + data.room).css({
      width: data.ngtime / 19 * 100 + '%'
    });
  });
  window.socket.on('jackpot.slider', function (data) {
    $('#chouser_' + data.room).slideDown();
    var members = '';

    for (var i = 0; i < data.members.length; i++) {
      members += '<div class="user"><div class="image" style="background: url(' + data.members[i].avatar + ') no-repeat center center / 100%;"></div></div>';
    }

    $('#carousel_' + data.room).html(members);
    $('#carousel_' + data.room).css({
      transform: 'translate3d(-' + data.ml + 'px, 0px, 0px)',
      transition: 'transform 15500ms cubic-bezier(0, 0, 0, 1) -4ms'
    });
    var m = 4,
        p = 0;
    rouletteInterval = setInterval(function () {
      p = _getTransformOffset($('#carousel_' + data.room)), m - p >= 60 && (m = p, $.audio('/assets/sounds/click.mp3', 0.1));
    }, 80);
    setTimeout(function () {
      $('#winnerAvatar_' + data.room).attr('src', data.winner.avatar);
      $('#winnerName_' + data.room).text(data.winner.username);
      $('#winnerSum_' + data.room).text(data.winner.sum);
      $('#winnerChance_' + data.room).text(data.winner.chance + '%');
      $('#winnerTicket_' + data.room).text(data.winner.ticket);
      $('#winnerBox_' + data.room).slideDown();
      $('#carousel_' + data.room + ' .user:nth-child(81)').addClass('winner');
      $('#check_' + data.room).attr('href', '/fair/' + data.hash);
    }, 16000);
  });
  window.socket.on('jackpot.newGame', function (data) {
    $('#timer-svg_' + data.room).css({
      strokeDashoffset: '0.783px'
    });
    $('#timerShadow_' + data.room).css({
      strokeDashoffset: '0.783px'
    });
    $('#carousel_' + data.room).removeAttr('style');
    $('#carousel_' + data.room).html('');
    $('#chouser_' + data.room).slideUp();
    $('#winnerBox_' + data.room).slideUp();
    $('#time_' + data.room).text(data.time[0] + ':' + data.time[1]);
    $('#roombank_' + data.room).html('0 <i class="fas fa-coins"></i>');
    $('#gamebank_' + data.room).text('0');
    $('#bets_' + data.room).html('');
    $('#chances_' + data.room).html('');
    $('#roundId_' + data.room).html('#' + data.game.id);
    $('#timeline_' + data.room).css({
      width: '100%'
    });
    $('#hash_' + data.room).text(data.game.hash);
  });
}, []);

/***/ }),

/***/ 3:
/*!****************************************************!*\
  !*** multi ./resources/assets/js/pages/Jackpot.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/ploi/pvp.bulk.bet/resources/assets/js/pages/Jackpot.js */"./resources/assets/js/pages/Jackpot.js");


/***/ })

/******/ });