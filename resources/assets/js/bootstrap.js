window._ = require('lodash');
window.Popper = require('popper.js').default;
window.$ = window.jQuery = require('jquery');
require('jquery');
require('jquery-ui');
require('tooltipster');

/**
 * We'll load the axios HTTP library which allows us requests and cookies access
 */

require('./extern/notifyme');
require('./helpers/cookie'); 
require('./helpers/request');
require('./helpers/lang');

$.mixManifest = function(asset) {
    return window._mixManifest[asset] ?? asset;
}

/**
 * Socket exposes an expressive API for subscribing to channels and listening
 */
 
const io = require("socket.io-client");

let assetsLoaded = false, successfullyLoaded = false;

const app = function() {
	window.socket = io.connect(':8005');

	window.socket.on('connect', () => {
	  console.log('Successfully connected to socket server!');
	});

	$.getScript($.mixManifest('/assets/js/app.js'), function () {
		successfullyLoaded = true;
	});
};

app();

$(window).on('load', function() {
    assetsLoaded = true;
});

const unloadLoader = setInterval(function() {
    if(assetsLoaded && successfullyLoaded) {
		$(document).trigger('bootstrap:load');
		$(".preloading-wrapper").fadeOut(0);
		clearInterval(unloadLoader);
    }
}, 10);