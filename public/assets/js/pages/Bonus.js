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
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/pages/Bonus.js":
/*!********************************************!*\
  !*** ./resources/assets/js/pages/Bonus.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$.on('/bonus', function () {
  function declOfNum(number, titles) {
    var cases = [2, 0, 1, 1, 1, 2];
    return titles[number % 100 > 4 && number % 100 < 20 ? 2 : cases[number % 10 < 5 ? number % 10 : 5]];
  }

  ;
  $('.getBonus').click(function () {
    $.ajax({
      url: '/bonus/getBonus',
      type: 'post',
      data: {
        recapcha: $('#g-recaptcha-response').val()
      },
      success: function success(data) {
        $.wnoty({
          position: 'top-right',
          type: data.type,
          message: data.msg
        });
        grecaptcha.reset();
        return false;
      },
      error: function error(data) {
        console.log(data.responseText);
      }
    });
  });
  window.socket.on('bonus', function (data) {
    if ($.userId() == data.user_id) {
      var line = '';

      for (var i = 0; i < data.line.length; i++) {
        line += '<div class="user" style="background: #495168;border-radius: 5px;"><div class="summ"><b>' + data.line[i].sum + '</b><p>' + declOfNum(data.line[i].sum, ['монета', 'монеты', 'монет']) + '</p></div></div>';
      }

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

/***/ }),

/***/ 7:
/*!**************************************************!*\
  !*** multi ./resources/assets/js/pages/Bonus.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/resources/assets/js/pages/Bonus.js */"./resources/assets/js/pages/Bonus.js");


/***/ })

/******/ });