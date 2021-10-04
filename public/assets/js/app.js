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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/jquery-pjax/jquery.pjax.js":
/*!*************************************************!*\
  !*** ./node_modules/jquery-pjax/jquery.pjax.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*!
 * Copyright 2012, Chris Wanstrath
 * Released under the MIT License
 * https://github.com/defunkt/jquery-pjax
 */

(function($){

// When called on a container with a selector, fetches the href with
// ajax into the container or with the data-pjax attribute on the link
// itself.
//
// Tries to make sure the back button and ctrl+click work the way
// you'd expect.
//
// Exported as $.fn.pjax
//
// Accepts a jQuery ajax options object that may include these
// pjax specific options:
//
//
// container - String selector for the element where to place the response body.
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// For convenience the second parameter can be either the container or
// the options object.
//
// Returns the jQuery object
function fnPjax(selector, container, options) {
  options = optionsFor(container, options)
  return this.on('click.pjax', selector, function(event) {
    var opts = options
    if (!opts.container) {
      opts = $.extend({}, options)
      opts.container = $(this).attr('data-pjax')
    }
    handleClick(event, opts)
  })
}

// Public: pjax on click handler
//
// Exported as $.pjax.click.
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//   $(document).on('click', 'a', $.pjax.click)
//   // is the same as
//   $(document).pjax('a')
//
// Returns nothing.
function handleClick(event, container, options) {
  options = optionsFor(container, options)

  var link = event.currentTarget
  var $link = $(link)

  if (link.tagName.toUpperCase() !== 'A')
    throw "$.fn.pjax or $.pjax.click requires an anchor element"

  // Middle click, cmd click, and ctrl click should open
  // links in a new tab as normal.
  if ( event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey )
    return

  // Ignore cross origin links
  if ( location.protocol !== link.protocol || location.hostname !== link.hostname )
    return

  // Ignore case when a hash is being tacked on the current URL
  if ( link.href.indexOf('#') > -1 && stripHash(link) == stripHash(location) )
    return

  // Ignore event with default prevented
  if (event.isDefaultPrevented())
    return

  var defaults = {
    url: link.href,
    container: $link.attr('data-pjax'),
    target: link
  }

  var opts = $.extend({}, defaults, options)
  var clickEvent = $.Event('pjax:click')
  $link.trigger(clickEvent, [opts])

  if (!clickEvent.isDefaultPrevented()) {
    pjax(opts)
    event.preventDefault()
    $link.trigger('pjax:clicked', [opts])
  }
}

// Public: pjax on form submit handler
//
// Exported as $.pjax.submit
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//  $(document).on('submit', 'form', function(event) {
//    $.pjax.submit(event, '[data-pjax-container]')
//  })
//
// Returns nothing.
function handleSubmit(event, container, options) {
  options = optionsFor(container, options)

  var form = event.currentTarget
  var $form = $(form)

  if (form.tagName.toUpperCase() !== 'FORM')
    throw "$.pjax.submit requires a form element"

  var defaults = {
    type: ($form.attr('method') || 'GET').toUpperCase(),
    url: $form.attr('action'),
    container: $form.attr('data-pjax'),
    target: form
  }

  if (defaults.type !== 'GET' && window.FormData !== undefined) {
    defaults.data = new FormData(form)
    defaults.processData = false
    defaults.contentType = false
  } else {
    // Can't handle file uploads, exit
    if ($form.find(':file').length) {
      return
    }

    // Fallback to manually serializing the fields
    defaults.data = $form.serializeArray()
  }

  pjax($.extend({}, defaults, options))

  event.preventDefault()
}

// Loads a URL with ajax, puts the response body inside a container,
// then pushState()'s the loaded URL.
//
// Works just like $.ajax in that it accepts a jQuery ajax
// settings object (with keys like url, type, data, etc).
//
// Accepts these extra keys:
//
// container - String selector for where to stick the response body.
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// Use it just like $.ajax:
//
//   var xhr = $.pjax({ url: this.href, container: '#main' })
//   console.log( xhr.readyState )
//
// Returns whatever $.ajax returns.
function pjax(options) {
  options = $.extend(true, {}, $.ajaxSettings, pjax.defaults, options)

  if ($.isFunction(options.url)) {
    options.url = options.url()
  }

  var hash = parseURL(options.url).hash

  var containerType = $.type(options.container)
  if (containerType !== 'string') {
    throw "expected string value for 'container' option; got " + containerType
  }
  var context = options.context = $(options.container)
  if (!context.length) {
    throw "the container selector '" + options.container + "' did not match anything"
  }

  // We want the browser to maintain two separate internal caches: one
  // for pjax'd partial page loads and one for normal page loads.
  // Without adding this secret parameter, some browsers will often
  // confuse the two.
  if (!options.data) options.data = {}
  if ($.isArray(options.data)) {
    options.data.push({name: '_pjax', value: options.container})
  } else {
    options.data._pjax = options.container
  }

  function fire(type, args, props) {
    if (!props) props = {}
    props.relatedTarget = options.target
    var event = $.Event(type, props)
    context.trigger(event, args)
    return !event.isDefaultPrevented()
  }

  var timeoutTimer

  options.beforeSend = function(xhr, settings) {
    // No timeout for non-GET requests
    // Its not safe to request the resource again with a fallback method.
    if (settings.type !== 'GET') {
      settings.timeout = 0
    }

    xhr.setRequestHeader('X-PJAX', 'true')
    xhr.setRequestHeader('X-PJAX-Container', options.container)

    if (!fire('pjax:beforeSend', [xhr, settings]))
      return false

    if (settings.timeout > 0) {
      timeoutTimer = setTimeout(function() {
        if (fire('pjax:timeout', [xhr, options]))
          xhr.abort('timeout')
      }, settings.timeout)

      // Clear timeout setting so jquerys internal timeout isn't invoked
      settings.timeout = 0
    }

    var url = parseURL(settings.url)
    if (hash) url.hash = hash
    options.requestUrl = stripInternalParams(url)
  }

  options.complete = function(xhr, textStatus) {
    if (timeoutTimer)
      clearTimeout(timeoutTimer)

    fire('pjax:complete', [xhr, textStatus, options])

    fire('pjax:end', [xhr, options])
  }

  options.error = function(xhr, textStatus, errorThrown) {
    var container = extractContainer("", xhr, options)

    var allowed = fire('pjax:error', [xhr, textStatus, errorThrown, options])
    if (options.type == 'GET' && textStatus !== 'abort' && allowed) {
      locationReplace(container.url)
    }
  }

  options.success = function(data, status, xhr) {
    var previousState = pjax.state

    // If $.pjax.defaults.version is a function, invoke it first.
    // Otherwise it can be a static string.
    var currentVersion = typeof $.pjax.defaults.version === 'function' ?
      $.pjax.defaults.version() :
      $.pjax.defaults.version

    var latestVersion = xhr.getResponseHeader('X-PJAX-Version')

    var container = extractContainer(data, xhr, options)

    var url = parseURL(container.url)
    if (hash) {
      url.hash = hash
      container.url = url.href
    }

    // If there is a layout version mismatch, hard load the new url
    if (currentVersion && latestVersion && currentVersion !== latestVersion) {
      locationReplace(container.url)
      return
    }

    // If the new response is missing a body, hard load the page
    if (!container.contents) {
      locationReplace(container.url)
      return
    }

    pjax.state = {
      id: options.id || uniqueId(),
      url: container.url,
      title: container.title,
      container: options.container,
      fragment: options.fragment,
      timeout: options.timeout
    }

    if (options.push || options.replace) {
      window.history.replaceState(pjax.state, container.title, container.url)
    }

    // Only blur the focus if the focused element is within the container.
    var blurFocus = $.contains(context, document.activeElement)

    // Clear out any focused controls before inserting new page contents.
    if (blurFocus) {
      try {
        document.activeElement.blur()
      } catch (e) { /* ignore */ }
    }

    if (container.title) document.title = container.title

    fire('pjax:beforeReplace', [container.contents, options], {
      state: pjax.state,
      previousState: previousState
    })
    context.html(container.contents)

    // FF bug: Won't autofocus fields that are inserted via JS.
    // This behavior is incorrect. So if theres no current focus, autofocus
    // the last field.
    //
    // http://www.w3.org/html/wg/drafts/html/master/forms.html
    var autofocusEl = context.find('input[autofocus], textarea[autofocus]').last()[0]
    if (autofocusEl && document.activeElement !== autofocusEl) {
      autofocusEl.focus()
    }

    executeScriptTags(container.scripts)

    var scrollTo = options.scrollTo

    // Ensure browser scrolls to the element referenced by the URL anchor
    if (hash) {
      var name = decodeURIComponent(hash.slice(1))
      var target = document.getElementById(name) || document.getElementsByName(name)[0]
      if (target) scrollTo = $(target).offset().top
    }

    if (typeof scrollTo == 'number') $(window).scrollTop(scrollTo)

    fire('pjax:success', [data, status, xhr, options])
  }


  // Initialize pjax.state for the initial page load. Assume we're
  // using the container and options of the link we're loading for the
  // back button to the initial page. This ensures good back button
  // behavior.
  if (!pjax.state) {
    pjax.state = {
      id: uniqueId(),
      url: window.location.href,
      title: document.title,
      container: options.container,
      fragment: options.fragment,
      timeout: options.timeout
    }
    window.history.replaceState(pjax.state, document.title)
  }

  // Cancel the current request if we're already pjaxing
  abortXHR(pjax.xhr)

  pjax.options = options
  var xhr = pjax.xhr = $.ajax(options)

  if (xhr.readyState > 0) {
    if (options.push && !options.replace) {
      // Cache current container element before replacing it
      cachePush(pjax.state.id, [options.container, cloneContents(context)])

      window.history.pushState(null, "", options.requestUrl)
    }

    fire('pjax:start', [xhr, options])
    fire('pjax:send', [xhr, options])
  }

  return pjax.xhr
}

// Public: Reload current page with pjax.
//
// Returns whatever $.pjax returns.
function pjaxReload(container, options) {
  var defaults = {
    url: window.location.href,
    push: false,
    replace: true,
    scrollTo: false
  }

  return pjax($.extend(defaults, optionsFor(container, options)))
}

// Internal: Hard replace current state with url.
//
// Work for around WebKit
//   https://bugs.webkit.org/show_bug.cgi?id=93506
//
// Returns nothing.
function locationReplace(url) {
  window.history.replaceState(null, "", pjax.state.url)
  window.location.replace(url)
}


var initialPop = true
var initialURL = window.location.href
var initialState = window.history.state

// Initialize $.pjax.state if possible
// Happens when reloading a page and coming forward from a different
// session history.
if (initialState && initialState.container) {
  pjax.state = initialState
}

// Non-webkit browsers don't fire an initial popstate event
if ('state' in window.history) {
  initialPop = false
}

// popstate handler takes care of the back and forward buttons
//
// You probably shouldn't use pjax on pages with other pushState
// stuff yet.
function onPjaxPopstate(event) {

  // Hitting back or forward should override any pending PJAX request.
  if (!initialPop) {
    abortXHR(pjax.xhr)
  }

  var previousState = pjax.state
  var state = event.state
  var direction

  if (state && state.container) {
    // When coming forward from a separate history session, will get an
    // initial pop with a state we are already at. Skip reloading the current
    // page.
    if (initialPop && initialURL == state.url) return

    if (previousState) {
      // If popping back to the same state, just skip.
      // Could be clicking back from hashchange rather than a pushState.
      if (previousState.id === state.id) return

      // Since state IDs always increase, we can deduce the navigation direction
      direction = previousState.id < state.id ? 'forward' : 'back'
    }

    var cache = cacheMapping[state.id] || []
    var containerSelector = cache[0] || state.container
    var container = $(containerSelector), contents = cache[1]

    if (container.length) {
      if (previousState) {
        // Cache current container before replacement and inform the
        // cache which direction the history shifted.
        cachePop(direction, previousState.id, [containerSelector, cloneContents(container)])
      }

      var popstateEvent = $.Event('pjax:popstate', {
        state: state,
        direction: direction
      })
      container.trigger(popstateEvent)

      var options = {
        id: state.id,
        url: state.url,
        container: containerSelector,
        push: false,
        fragment: state.fragment,
        timeout: state.timeout,
        scrollTo: false
      }

      if (contents) {
        container.trigger('pjax:start', [null, options])

        pjax.state = state
        if (state.title) document.title = state.title
        var beforeReplaceEvent = $.Event('pjax:beforeReplace', {
          state: state,
          previousState: previousState
        })
        container.trigger(beforeReplaceEvent, [contents, options])
        container.html(contents)

        container.trigger('pjax:end', [null, options])
      } else {
        pjax(options)
      }

      // Force reflow/relayout before the browser tries to restore the
      // scroll position.
      container[0].offsetHeight // eslint-disable-line no-unused-expressions
    } else {
      locationReplace(location.href)
    }
  }
  initialPop = false
}

// Fallback version of main pjax function for browsers that don't
// support pushState.
//
// Returns nothing since it retriggers a hard form submission.
function fallbackPjax(options) {
  var url = $.isFunction(options.url) ? options.url() : options.url,
      method = options.type ? options.type.toUpperCase() : 'GET'

  var form = $('<form>', {
    method: method === 'GET' ? 'GET' : 'POST',
    action: url,
    style: 'display:none'
  })

  if (method !== 'GET' && method !== 'POST') {
    form.append($('<input>', {
      type: 'hidden',
      name: '_method',
      value: method.toLowerCase()
    }))
  }

  var data = options.data
  if (typeof data === 'string') {
    $.each(data.split('&'), function(index, value) {
      var pair = value.split('=')
      form.append($('<input>', {type: 'hidden', name: pair[0], value: pair[1]}))
    })
  } else if ($.isArray(data)) {
    $.each(data, function(index, value) {
      form.append($('<input>', {type: 'hidden', name: value.name, value: value.value}))
    })
  } else if (typeof data === 'object') {
    var key
    for (key in data)
      form.append($('<input>', {type: 'hidden', name: key, value: data[key]}))
  }

  $(document.body).append(form)
  form.submit()
}

// Internal: Abort an XmlHttpRequest if it hasn't been completed,
// also removing its event handlers.
function abortXHR(xhr) {
  if ( xhr && xhr.readyState < 4) {
    xhr.onreadystatechange = $.noop
    xhr.abort()
  }
}

// Internal: Generate unique id for state object.
//
// Use a timestamp instead of a counter since ids should still be
// unique across page loads.
//
// Returns Number.
function uniqueId() {
  return (new Date).getTime()
}

function cloneContents(container) {
  var cloned = container.clone()
  // Unmark script tags as already being eval'd so they can get executed again
  // when restored from cache. HAXX: Uses jQuery internal method.
  cloned.find('script').each(function(){
    if (!this.src) $._data(this, 'globalEval', false)
  })
  return cloned.contents()
}

// Internal: Strip internal query params from parsed URL.
//
// Returns sanitized url.href String.
function stripInternalParams(url) {
  url.search = url.search.replace(/([?&])(_pjax|_)=[^&]*/g, '').replace(/^&/, '')
  return url.href.replace(/\?($|#)/, '$1')
}

// Internal: Parse URL components and returns a Locationish object.
//
// url - String URL
//
// Returns HTMLAnchorElement that acts like Location.
function parseURL(url) {
  var a = document.createElement('a')
  a.href = url
  return a
}

// Internal: Return the `href` component of given URL object with the hash
// portion removed.
//
// location - Location or HTMLAnchorElement
//
// Returns String
function stripHash(location) {
  return location.href.replace(/#.*/, '')
}

// Internal: Build options Object for arguments.
//
// For convenience the first parameter can be either the container or
// the options object.
//
// Examples
//
//   optionsFor('#container')
//   // => {container: '#container'}
//
//   optionsFor('#container', {push: true})
//   // => {container: '#container', push: true}
//
//   optionsFor({container: '#container', push: true})
//   // => {container: '#container', push: true}
//
// Returns options Object.
function optionsFor(container, options) {
  if (container && options) {
    options = $.extend({}, options)
    options.container = container
    return options
  } else if ($.isPlainObject(container)) {
    return container
  } else {
    return {container: container}
  }
}

// Internal: Filter and find all elements matching the selector.
//
// Where $.fn.find only matches descendants, findAll will test all the
// top level elements in the jQuery object as well.
//
// elems    - jQuery object of Elements
// selector - String selector to match
//
// Returns a jQuery object.
function findAll(elems, selector) {
  return elems.filter(selector).add(elems.find(selector))
}

function parseHTML(html) {
  return $.parseHTML(html, document, true)
}

// Internal: Extracts container and metadata from response.
//
// 1. Extracts X-PJAX-URL header if set
// 2. Extracts inline <title> tags
// 3. Builds response Element and extracts fragment if set
//
// data    - String response data
// xhr     - XHR response
// options - pjax options Object
//
// Returns an Object with url, title, and contents keys.
function extractContainer(data, xhr, options) {
  var obj = {}, fullDocument = /<html/i.test(data)

  // Prefer X-PJAX-URL header if it was set, otherwise fallback to
  // using the original requested url.
  var serverUrl = xhr.getResponseHeader('X-PJAX-URL')
  obj.url = serverUrl ? stripInternalParams(parseURL(serverUrl)) : options.requestUrl

  var $head, $body
  // Attempt to parse response html into elements
  if (fullDocument) {
    $body = $(parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]))
    var head = data.match(/<head[^>]*>([\s\S.]*)<\/head>/i)
    $head = head != null ? $(parseHTML(head[0])) : $body
  } else {
    $head = $body = $(parseHTML(data))
  }

  // If response data is empty, return fast
  if ($body.length === 0)
    return obj

  // If there's a <title> tag in the header, use it as
  // the page's title.
  obj.title = findAll($head, 'title').last().text()

  if (options.fragment) {
    var $fragment = $body
    // If they specified a fragment, look for it in the response
    // and pull it out.
    if (options.fragment !== 'body') {
      $fragment = findAll($fragment, options.fragment).first()
    }

    if ($fragment.length) {
      obj.contents = options.fragment === 'body' ? $fragment : $fragment.contents()

      // If there's no title, look for data-title and title attributes
      // on the fragment
      if (!obj.title)
        obj.title = $fragment.attr('title') || $fragment.data('title')
    }

  } else if (!fullDocument) {
    obj.contents = $body
  }

  // Clean up any <title> tags
  if (obj.contents) {
    // Remove any parent title elements
    obj.contents = obj.contents.not(function() { return $(this).is('title') })

    // Then scrub any titles from their descendants
    obj.contents.find('title').remove()

    // Gather all script[src] elements
    obj.scripts = findAll(obj.contents, 'script[src]').remove()
    obj.contents = obj.contents.not(obj.scripts)
  }

  // Trim any whitespace off the title
  if (obj.title) obj.title = $.trim(obj.title)

  return obj
}

// Load an execute scripts using standard script request.
//
// Avoids jQuery's traditional $.getScript which does a XHR request and
// globalEval.
//
// scripts - jQuery object of script Elements
//
// Returns nothing.
function executeScriptTags(scripts) {
  if (!scripts) return

  var existingScripts = $('script[src]')

  scripts.each(function() {
    var src = this.src
    var matchedScripts = existingScripts.filter(function() {
      return this.src === src
    })
    if (matchedScripts.length) return

    var script = document.createElement('script')
    var type = $(this).attr('type')
    if (type) script.type = type
    script.src = $(this).attr('src')
    document.head.appendChild(script)
  })
}

// Internal: History DOM caching class.
var cacheMapping      = {}
var cacheForwardStack = []
var cacheBackStack    = []

// Push previous state id and container contents into the history
// cache. Should be called in conjunction with `pushState` to save the
// previous container contents.
//
// id    - State ID Number
// value - DOM Element to cache
//
// Returns nothing.
function cachePush(id, value) {
  cacheMapping[id] = value
  cacheBackStack.push(id)

  // Remove all entries in forward history stack after pushing a new page.
  trimCacheStack(cacheForwardStack, 0)

  // Trim back history stack to max cache length.
  trimCacheStack(cacheBackStack, pjax.defaults.maxCacheLength)
}

// Shifts cache from directional history cache. Should be
// called on `popstate` with the previous state id and container
// contents.
//
// direction - "forward" or "back" String
// id        - State ID Number
// value     - DOM Element to cache
//
// Returns nothing.
function cachePop(direction, id, value) {
  var pushStack, popStack
  cacheMapping[id] = value

  if (direction === 'forward') {
    pushStack = cacheBackStack
    popStack  = cacheForwardStack
  } else {
    pushStack = cacheForwardStack
    popStack  = cacheBackStack
  }

  pushStack.push(id)
  id = popStack.pop()
  if (id) delete cacheMapping[id]

  // Trim whichever stack we just pushed to to max cache length.
  trimCacheStack(pushStack, pjax.defaults.maxCacheLength)
}

// Trim a cache stack (either cacheBackStack or cacheForwardStack) to be no
// longer than the specified length, deleting cached DOM elements as necessary.
//
// stack  - Array of state IDs
// length - Maximum length to trim to
//
// Returns nothing.
function trimCacheStack(stack, length) {
  while (stack.length > length)
    delete cacheMapping[stack.shift()]
}

// Public: Find version identifier for the initial page load.
//
// Returns String version or undefined.
function findVersion() {
  return $('meta').filter(function() {
    var name = $(this).attr('http-equiv')
    return name && name.toUpperCase() === 'X-PJAX-VERSION'
  }).attr('content')
}

// Install pjax functions on $.pjax to enable pushState behavior.
//
// Does nothing if already enabled.
//
// Examples
//
//     $.pjax.enable()
//
// Returns nothing.
function enable() {
  $.fn.pjax = fnPjax
  $.pjax = pjax
  $.pjax.enable = $.noop
  $.pjax.disable = disable
  $.pjax.click = handleClick
  $.pjax.submit = handleSubmit
  $.pjax.reload = pjaxReload
  $.pjax.defaults = {
    timeout: 650,
    push: true,
    replace: false,
    type: 'GET',
    dataType: 'html',
    scrollTo: 0,
    maxCacheLength: 20,
    version: findVersion
  }
  $(window).on('popstate.pjax', onPjaxPopstate)
}

// Disable pushState behavior.
//
// This is the case when a browser doesn't support pushState. It is
// sometimes useful to disable pushState for debugging on a modern
// browser.
//
// Examples
//
//     $.pjax.disable()
//
// Returns nothing.
function disable() {
  $.fn.pjax = function() { return this }
  $.pjax = fallbackPjax
  $.pjax.enable = enable
  $.pjax.disable = $.noop
  $.pjax.click = $.noop
  $.pjax.submit = $.noop
  $.pjax.reload = function() { window.location.reload() }

  $(window).off('popstate.pjax', onPjaxPopstate)
}


// Add the state property to jQuery's event object so we can use it in
// $(window).bind('popstate')
if ($.event.props && $.inArray('state', $.event.props) < 0) {
  $.event.props.push('state')
} else if (!('state' in $.Event.prototype)) {
  $.event.addProp('state')
}

// Is pjax supported by this browser?
$.support.pjax =
  window.history && window.history.pushState && window.history.replaceState &&
  // pushState isn't reliable on iOS until 5.
  !navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/)

if ($.support.pjax) {
  enable()
} else {
  disable()
}

})(jQuery)


/***/ }),

/***/ "./node_modules/nprogress/nprogress.js":
/*!*********************************************!*\
  !*** ./node_modules/nprogress/nprogress.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/* NProgress, (c) 2013, 2014 Rico Sta. Cruz - http://ricostacruz.com/nprogress
 * @license MIT */

;(function(root, factory) {

  if (true) {
    !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}

})(this, function() {
  var NProgress = {};

  NProgress.version = '0.2.0';

  var Settings = NProgress.settings = {
    minimum: 0.08,
    easing: 'ease',
    positionUsing: '',
    speed: 200,
    trickle: true,
    trickleRate: 0.02,
    trickleSpeed: 800,
    showSpinner: true,
    barSelector: '[role="bar"]',
    spinnerSelector: '[role="spinner"]',
    parent: 'body',
    template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
  };

  /**
   * Updates configuration.
   *
   *     NProgress.configure({
   *       minimum: 0.1
   *     });
   */
  NProgress.configure = function(options) {
    var key, value;
    for (key in options) {
      value = options[key];
      if (value !== undefined && options.hasOwnProperty(key)) Settings[key] = value;
    }

    return this;
  };

  /**
   * Last number.
   */

  NProgress.status = null;

  /**
   * Sets the progress bar status, where `n` is a number from `0.0` to `1.0`.
   *
   *     NProgress.set(0.4);
   *     NProgress.set(1.0);
   */

  NProgress.set = function(n) {
    var started = NProgress.isStarted();

    n = clamp(n, Settings.minimum, 1);
    NProgress.status = (n === 1 ? null : n);

    var progress = NProgress.render(!started),
        bar      = progress.querySelector(Settings.barSelector),
        speed    = Settings.speed,
        ease     = Settings.easing;

    progress.offsetWidth; /* Repaint */

    queue(function(next) {
      // Set positionUsing if it hasn't already been set
      if (Settings.positionUsing === '') Settings.positionUsing = NProgress.getPositioningCSS();

      // Add transition
      css(bar, barPositionCSS(n, speed, ease));

      if (n === 1) {
        // Fade out
        css(progress, { 
          transition: 'none', 
          opacity: 1 
        });
        progress.offsetWidth; /* Repaint */

        setTimeout(function() {
          css(progress, { 
            transition: 'all ' + speed + 'ms linear', 
            opacity: 0 
          });
          setTimeout(function() {
            NProgress.remove();
            next();
          }, speed);
        }, speed);
      } else {
        setTimeout(next, speed);
      }
    });

    return this;
  };

  NProgress.isStarted = function() {
    return typeof NProgress.status === 'number';
  };

  /**
   * Shows the progress bar.
   * This is the same as setting the status to 0%, except that it doesn't go backwards.
   *
   *     NProgress.start();
   *
   */
  NProgress.start = function() {
    if (!NProgress.status) NProgress.set(0);

    var work = function() {
      setTimeout(function() {
        if (!NProgress.status) return;
        NProgress.trickle();
        work();
      }, Settings.trickleSpeed);
    };

    if (Settings.trickle) work();

    return this;
  };

  /**
   * Hides the progress bar.
   * This is the *sort of* the same as setting the status to 100%, with the
   * difference being `done()` makes some placebo effect of some realistic motion.
   *
   *     NProgress.done();
   *
   * If `true` is passed, it will show the progress bar even if its hidden.
   *
   *     NProgress.done(true);
   */

  NProgress.done = function(force) {
    if (!force && !NProgress.status) return this;

    return NProgress.inc(0.3 + 0.5 * Math.random()).set(1);
  };

  /**
   * Increments by a random amount.
   */

  NProgress.inc = function(amount) {
    var n = NProgress.status;

    if (!n) {
      return NProgress.start();
    } else {
      if (typeof amount !== 'number') {
        amount = (1 - n) * clamp(Math.random() * n, 0.1, 0.95);
      }

      n = clamp(n + amount, 0, 0.994);
      return NProgress.set(n);
    }
  };

  NProgress.trickle = function() {
    return NProgress.inc(Math.random() * Settings.trickleRate);
  };

  /**
   * Waits for all supplied jQuery promises and
   * increases the progress as the promises resolve.
   *
   * @param $promise jQUery Promise
   */
  (function() {
    var initial = 0, current = 0;

    NProgress.promise = function($promise) {
      if (!$promise || $promise.state() === "resolved") {
        return this;
      }

      if (current === 0) {
        NProgress.start();
      }

      initial++;
      current++;

      $promise.always(function() {
        current--;
        if (current === 0) {
            initial = 0;
            NProgress.done();
        } else {
            NProgress.set((initial - current) / initial);
        }
      });

      return this;
    };

  })();

  /**
   * (Internal) renders the progress bar markup based on the `template`
   * setting.
   */

  NProgress.render = function(fromStart) {
    if (NProgress.isRendered()) return document.getElementById('nprogress');

    addClass(document.documentElement, 'nprogress-busy');
    
    var progress = document.createElement('div');
    progress.id = 'nprogress';
    progress.innerHTML = Settings.template;

    var bar      = progress.querySelector(Settings.barSelector),
        perc     = fromStart ? '-100' : toBarPerc(NProgress.status || 0),
        parent   = document.querySelector(Settings.parent),
        spinner;
    
    css(bar, {
      transition: 'all 0 linear',
      transform: 'translate3d(' + perc + '%,0,0)'
    });

    if (!Settings.showSpinner) {
      spinner = progress.querySelector(Settings.spinnerSelector);
      spinner && removeElement(spinner);
    }

    if (parent != document.body) {
      addClass(parent, 'nprogress-custom-parent');
    }

    parent.appendChild(progress);
    return progress;
  };

  /**
   * Removes the element. Opposite of render().
   */

  NProgress.remove = function() {
    removeClass(document.documentElement, 'nprogress-busy');
    removeClass(document.querySelector(Settings.parent), 'nprogress-custom-parent');
    var progress = document.getElementById('nprogress');
    progress && removeElement(progress);
  };

  /**
   * Checks if the progress bar is rendered.
   */

  NProgress.isRendered = function() {
    return !!document.getElementById('nprogress');
  };

  /**
   * Determine which positioning CSS rule to use.
   */

  NProgress.getPositioningCSS = function() {
    // Sniff on document.body.style
    var bodyStyle = document.body.style;

    // Sniff prefixes
    var vendorPrefix = ('WebkitTransform' in bodyStyle) ? 'Webkit' :
                       ('MozTransform' in bodyStyle) ? 'Moz' :
                       ('msTransform' in bodyStyle) ? 'ms' :
                       ('OTransform' in bodyStyle) ? 'O' : '';

    if (vendorPrefix + 'Perspective' in bodyStyle) {
      // Modern browsers with 3D support, e.g. Webkit, IE10
      return 'translate3d';
    } else if (vendorPrefix + 'Transform' in bodyStyle) {
      // Browsers without 3D support, e.g. IE9
      return 'translate';
    } else {
      // Browsers without translate() support, e.g. IE7-8
      return 'margin';
    }
  };

  /**
   * Helpers
   */

  function clamp(n, min, max) {
    if (n < min) return min;
    if (n > max) return max;
    return n;
  }

  /**
   * (Internal) converts a percentage (`0..1`) to a bar translateX
   * percentage (`-100%..0%`).
   */

  function toBarPerc(n) {
    return (-1 + n) * 100;
  }


  /**
   * (Internal) returns the correct CSS for changing the bar's
   * position given an n percentage, and speed and ease from Settings
   */

  function barPositionCSS(n, speed, ease) {
    var barCSS;

    if (Settings.positionUsing === 'translate3d') {
      barCSS = { transform: 'translate3d('+toBarPerc(n)+'%,0,0)' };
    } else if (Settings.positionUsing === 'translate') {
      barCSS = { transform: 'translate('+toBarPerc(n)+'%,0)' };
    } else {
      barCSS = { 'margin-left': toBarPerc(n)+'%' };
    }

    barCSS.transition = 'all '+speed+'ms '+ease;

    return barCSS;
  }

  /**
   * (Internal) Queues a function to be executed.
   */

  var queue = (function() {
    var pending = [];
    
    function next() {
      var fn = pending.shift();
      if (fn) {
        fn(next);
      }
    }

    return function(fn) {
      pending.push(fn);
      if (pending.length == 1) next();
    };
  })();

  /**
   * (Internal) Applies css properties to an element, similar to the jQuery 
   * css method.
   *
   * While this helper does assist with vendor prefixed property names, it 
   * does not perform any manipulation of values prior to setting styles.
   */

  var css = (function() {
    var cssPrefixes = [ 'Webkit', 'O', 'Moz', 'ms' ],
        cssProps    = {};

    function camelCase(string) {
      return string.replace(/^-ms-/, 'ms-').replace(/-([\da-z])/gi, function(match, letter) {
        return letter.toUpperCase();
      });
    }

    function getVendorProp(name) {
      var style = document.body.style;
      if (name in style) return name;

      var i = cssPrefixes.length,
          capName = name.charAt(0).toUpperCase() + name.slice(1),
          vendorName;
      while (i--) {
        vendorName = cssPrefixes[i] + capName;
        if (vendorName in style) return vendorName;
      }

      return name;
    }

    function getStyleProp(name) {
      name = camelCase(name);
      return cssProps[name] || (cssProps[name] = getVendorProp(name));
    }

    function applyCss(element, prop, value) {
      prop = getStyleProp(prop);
      element.style[prop] = value;
    }

    return function(element, properties) {
      var args = arguments,
          prop, 
          value;

      if (args.length == 2) {
        for (prop in properties) {
          value = properties[prop];
          if (value !== undefined && properties.hasOwnProperty(prop)) applyCss(element, prop, value);
        }
      } else {
        applyCss(element, args[1], args[2]);
      }
    }
  })();

  /**
   * (Internal) Determines if an element or space separated list of class names contains a class name.
   */

  function hasClass(element, name) {
    var list = typeof element == 'string' ? element : classList(element);
    return list.indexOf(' ' + name + ' ') >= 0;
  }

  /**
   * (Internal) Adds a class to an element.
   */

  function addClass(element, name) {
    var oldList = classList(element),
        newList = oldList + name;

    if (hasClass(oldList, name)) return; 

    // Trim the opening space.
    element.className = newList.substring(1);
  }

  /**
   * (Internal) Removes a class from an element.
   */

  function removeClass(element, name) {
    var oldList = classList(element),
        newList;

    if (!hasClass(element, name)) return;

    // Replace the class name.
    newList = oldList.replace(' ' + name + ' ', ' ');

    // Trim the opening and closing spaces.
    element.className = newList.substring(1, newList.length - 1);
  }

  /**
   * (Internal) Gets a space separated list of the class names on the element. 
   * The list is wrapped with a single space on each end to facilitate finding 
   * matches within the list.
   */

  function classList(element) {
    return (' ' + (element.className || '') + ' ').replace(/\s+/gi, ' ');
  }

  /**
   * (Internal) Removes an element from the DOM.
   */

  function removeElement(element) {
    element && element.parentNode && element.parentNode.removeChild(element);
  }

  return NProgress;
});



/***/ }),

/***/ "./resources/assets/js/app.js":
/*!************************************!*\
  !*** ./resources/assets/js/app.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var nprogress__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nprogress */ "./node_modules/nprogress/nprogress.js");
/* harmony import */ var nprogress__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(nprogress__WEBPACK_IMPORTED_MODULE_0__);
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
__webpack_require__(/*! jquery-pjax */ "./node_modules/jquery-pjax/jquery.pjax.js");

__webpack_require__(/*! ./routes */ "./resources/assets/js/routes.js");


var container = '.pageContent';
var cachedResources = [];
var loadedContents = null;

$.on = function (route, callback) {
  var cssUrls = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];
  $(document).on("page:".concat(route.substr(1)), function () {
    $.loadCSS(cssUrls, callback);
  });
};

var initializeRoute = function initializeRoute() {
  var route = $.routes()["/".concat($.currentRoute())];

  if (route === undefined) {
    $.loadCSS([], function () {});
    console.error("/".concat($.currentRoute(), " is not routed"));
    nprogress__WEBPACK_IMPORTED_MODULE_0___default.a.done();
  } else {
    $.loadScripts(route, function () {
      $(document).trigger("page:".concat($.currentRoute()));
      var pathname = window.location.pathname.substr(1);
      if (pathname !== $.currentRoute()) $(document).trigger("page:".concat(window.location.pathname.substr(1)));
    });
  }

  $.each($('*[data-page-trigger]'), function (i, e) {
    var match = false;
    $.each(JSON.parse("[".concat($(e).attr('data-page-trigger').replaceAll('\'', '"'), "]")), function (aI, aE) {
      if (window.location.pathname === aE) match = true;
    });
    $(e).toggleClass($(e).attr('data-toggle-class'), match);
  });
};

$(document).pjax('a:not(.disable-pjax)', container);

window.redirect = function (page) {
  $.pjax({
    url: page,
    container: container
  });
};

$(document).on('pjax:start', function () {
  nprogress__WEBPACK_IMPORTED_MODULE_0___default.a.start();
});
$(document).on('pjax:beforeReplace', function (e, contents) {
  $(container).css({
    'opacity': 0
  });
  loadedContents = contents;
});
$(document).on('pjax:end', function () {
  $('[data-async-css]').remove();
  initializeRoute();
  var close = document.querySelectorAll('[data-close="alert"]');

  for (var i = 0; i < close.length; i++) {
    close[i].onclick = function () {
      var div = this.parentElement;
      div.style.opacity = '0';
      setTimeout(function () {
        div.style.display = 'none';
      }, 400);
    };
  }

  ;
});
$(document).on('pjax:timeout', function (event) {
  event.preventDefault();
});

$.loadScripts = function (urls, callback) {
  var notLoaded = [];

  var _loop = function _loop(i) {
    $.cacheResource(urls[i], function () {
      notLoaded.push(urls[i]);
    });
  };

  for (var i = 0; i < urls.length; i++) {
    _loop(i);
  }

  if (notLoaded.length > 0) {
    $(".preloading-wrapper").fadeIn();
    var index = 0;

    var next = function next() {
      $.getScript(notLoaded[index], index !== notLoaded.length - 1 ? function () {
        index++;
        next();
      } : callback);
    };

    next();
  } else {
    callback();
  }
};

$.loadCSS = function (urls, callback) {
  var loaded = 0;

  var finish = function finish() {
    if (loadedContents != null) $(container).html(loadedContents);
    $(".preloading-wrapper").fadeOut(0);
    $(container).animate({
      opacity: 1
    }, 350, callback);
    nprogress__WEBPACK_IMPORTED_MODULE_0___default.a.done();
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

  var stylesheetLoadCallback = function stylesheetLoadCallback() {
    loaded++;
    if (loaded >= urls.length) setTimeout(finish, 100);
  };

  if (urls.length === 0) finish();
  $.map(urls, function (url) {
    loadStyleSheet(url, stylesheetLoadCallback);
  });
};

function loadStyleSheet(path, fn, scope) {
  var head = document.getElementsByTagName('head')[0],
      link = document.createElement('link');
  link.setAttribute('href', path);
  link.setAttribute('rel', 'stylesheet');
  link.setAttribute('type', 'text/css');
  link.setAttribute('data-async-css', 'true');
  var sheet, cssRules;

  if ('sheet' in link) {
    sheet = 'sheet';
    cssRules = 'cssRules';
  } else {
    sheet = 'styleSheet';
    cssRules = 'rules';
  }

  var interval_id = setInterval(function () {
    try {
      if (link[sheet] && link[sheet][cssRules].length) {
        clearInterval(interval_id);
        clearTimeout(timeout_id);
        fn.call(scope || window, true, link);
      }
    } catch (e) {} finally {}
  }, 10);
  var timeout_id = setTimeout(function () {
    clearInterval(interval_id);
    clearTimeout(timeout_id);
    head.removeChild(link);
    fn.call(scope || window, false, link);
    console.error(path + ' loading error');
  }, 15000);
  head.appendChild(link);
  return link;
}

;

$.cacheResource = function (key, callback) {
  if (cachedResources.includes(key)) return;
  cachedResources.push(key);
  console.log("".concat(key, " is loaded"));
  return callback();
};

$.currentRoute = function () {
  var page = window.location.pathname;

  var format = function format(skip) {
    return page.count('/') > skip ? page.substr(skip === 1 ? 1 : page.indexOf('/' + page.split('/')[skip]), page.lastIndexOf('/') - 1) : page.substr(1);
  };

  return format(1);
};

String.prototype.replaceAll = String.prototype.replaceAll || function (string, replaced) {
  return this.replace(new RegExp(string, 'g'), replaced);
};

String.prototype.capitalize = function () {
  return this.charAt(0).toUpperCase() + this.substring(1);
};

String.prototype.count = function (find) {
  return this.split(find).length - 1;
};

$.audio = function (audio, vol) {
  if (sounds == 'off') {} else {
    var newgames = new Audio();
    newgames.src = audio;
    newgames.volume = vol;
    newgames.play();
  }
};

$.isGuest = function () {
  return window.Laravel.userId == null;
};

$.userId = function () {
  return window.Laravel.userId;
};

$.userAccess = function () {
  return window.Laravel.access;
};

$.urlParam = function (name) {
  var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
  if (results == null) return null;
  return decodeURI(results[1]) || 0;
};

$.setBalance = function (amount) {
  $('.money').text(amount.toFixed(3).replace(/.$/, ''));
  $('#money').val(amount.toFixed(3).replace(/.$/, ''));
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
var sounds = $.getCookie('sounds');
$(document).ready(function (e) {
  $(document).trigger('pjax:start');
  $('#sounds').hide();
  $('#soundsOn').hide();

  if (sounds == 'off') {
    $('#sounds').show();
    $('#soundsOn').hide();
  } else {
    $('#sounds').hide();
    $('#soundsOn').show();
  }

  $(document).on('click', '#sounds', function (e) {
    $('#sounds').hide();
    $('#soundsOn').show();
    sounds = 'on';
    $.setCookie('sounds', 'on');
  });
  $(document).on('click', '#soundsOn', function (e) {
    $('#sounds').show();
    $('#soundsOn').hide();
    sounds = 'off';
    $.setCookie('sounds', 'off');
  });
  window.socket.on('roulette', function (res) {
    if (res.type == 'newGame') {
      $('#getPriceDouble').html('0 <i class="fas fa-coins"></i>');
    }

    if (res.type == 'bets') {
      $('#getPriceDouble').html(res.allBank + ' <i class="fas fa-coins"></i>');
    }
  });
  window.socket.on('battle.newBet', function (data) {
    $('#getPriceBattle').html(data.allBank + ' <i class="fas fa-coins"></i>');
  });
  window.socket.on('battle.newGame', function (data) {
    $('#getPriceBattle').html('0 <i class="fas fa-coins"></i>');
  });
  window.socket.on('jackpot.newBet', function (data) {
    $('#getPriceJackpot').html(data.allBank + ' <i class="fas fa-coins"></i>');
  });
  window.socket.on('jackpot.newGame', function (data) {
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
  $('#openChat').click(function () {
    if ($('body').is('.chat-mobile')) {
      $('body').removeClass('chat-mobile');
      $('.chat .open > span').text('<');
      $('body').addClass('chat-closed');
    } else {
      $('body').addClass('chat-mobile');
      $('.chat .open > span').text('>');
      $('body').removeClass('chat-closed');
    }

    if ($.getCookie('chat') === 'open') {
      $.setCookie('chat', 'closed');
    } else {
      $.setCookie('chat', 'open');
    }
  });
  $('#menuOpen').click(function () {
    if ($('body').is('.menu-open')) {
      $('body').removeClass('menu-open');
      $('#menuOpen span').text('>');
    } else {
      $('body').addClass('menu-open');
      $('#menuOpen span').text('<');
    }

    if ($.getCookie('chat') === 'open') {
      $.setCookie('chat', 'closed');
      $('body').addClass('chat-closed');
      $('body').removeClass('chat-mobile');
    }
  });
  $(window).resize(function () {
    resize();
  });

  function resize() {
    if (window.innerWidth <= 610) {
      $('body').removeClass('chat-mobile');
      $('body').addClass('chat-closed');
      $.setCookie('chat', 'closed');
    }

    $('.chat .messages .scroll').scrollTop(1e7);
  }

  ;

  function chatOn() {
    if ($.getCookie('chat') === 'closed') {
      $('body').addClass('chat-closed');
      $('.chat .open > span').text('<');
      $('body').removeClass('chat-mobile');
    } else {
      $('body').removeClass('chat-closed');
      $('.chat .open > span').text('>');
      $('body').addClass('chat-mobile');
    }
  }

  ;
  $('.overlay').click(function (e) {
    var target = e.target || e.srcElement;

    if (!target.className.search('overlay')) {
      $('.overlay, .popup, body').removeClass('active');
    }
  });
  $('[rel=popup]').click(function (e) {
    showPopup($(this).attr('data-popup'));
    return false;
  });
  var close = document.querySelectorAll('[data-close="alert"]');

  for (var i = 0; i < close.length; i++) {
    close[i].onclick = function () {
      var div = this.parentElement;
      div.style.opacity = '0';
      setTimeout(function () {
        div.style.display = 'none';
      }, 400);
    };
  }

  ;
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
  }

  ;
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

      var comission = val + val / 100 * perc + com * 10;
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

      var comission = val + val / 100 * perc + com * 10;
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

      var comission = val + val / 100 * perc + com * 10;
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

      var comission = val + val / 100 * perc + com * 10;
      $('#min_wid').html(comission);
      $('#value').attr('placeholder', 'Мин. сумма: ' + comission + ' монет');
      $('#wallet').attr('placeholder', '4700xxxxxxxxxxxx');
      $('#com').html(perc + '% + ' + com + 'руб.');
    }
  }

  ;
  $('#wallet').keydown(function (event) {
    if (event.shiftKey === true) return false;

    if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 65 && event.ctrlKey === true || event.keyCode >= 35 && event.keyCode <= 39) {
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
      success: function success(data) {
        $('.popup, .overlay, body').removeClass('active');
        $.wnoty({
          position: 'top-right',
          type: data.type,
          message: data.msg
        });
        return false;
      },
      error: function error(data) {
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
    var msg = JSON.parse(data);
    var chat = $('.messages');
    /* ban panel */

    if ($.userAccess() == 'admin') {
      if (msg.access != 'admin' || msg.access != 'moder') {
        var mute = "<a class=\"delete tooltip\" title=\"\u0417\u0430\u043C\u0443\u0442\u0438\u0442\u044C\" onclick=\"$.mute('" + msg.user_id + "','" + msg.username + "')\"><i class=\"fas fa-ban\"></i></a>";
      }

      var panel = '<a class="delete tooltip" title="Удалить" onclick="$.chatdelet(' + msg.time2 + ')"><i class="fas fa-trash-alt"></i></a>';
    } else {
      var mute = '';
      var panel = '';
    }

    if ($.userId() != null) {
      var transfer = "<a class=\"delete tooltip\" title=\"\u041F\u0435\u0440\u0435\u0432\u043E\u0434\" onclick=\"$.transfer('" + msg.user_id + "','" + msg.username + "')\"><i class=\"fas fa-gift\"></i></a>";
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
    chat.find('.scroll').append('<div class="msg flex flex-between" id="chatm_' + msg.time2 + '">' + '<div class="ava"><div class="image" style="background: url(' + msg.avatar + ') no-repeat center center / 100%;"></div></div>' + '<div class="r"><div class="top flex flex-between"><span>' + name + '</span><div class="data"><b>' + msg.user_id + '</b></div>' + panel + mute + transfer + '</div><div class="mess">' + messages + '</div>' + '</div>' + '</div>');
    $('.chat .messages .scroll').scrollTop(1e7);
    $.audio('/assets/sounds/chat-message-add.mp3', 0.4);
    if ($('.chat .msg').length >= 20) $('.chat .msg:nth-child(1)').remove();
  });
  window.socket.on('chatdel', function (data) {
    var info = JSON.parse(data);
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
        } else input.val('');
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
  $(document).on('click', '#send_mute', function (e) {
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
  $(document).on('click', '#send_transfer', function (e) {
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
}

;

$.chatdelet = function (id) {
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

$.transfer = function (user_id, username) {
  $('#recipient').html('Получатель: ' + username);
  $('#recipient_id').val(user_id);
  showPopup('popup-transfer');
};

$.mute = function (user_id, username) {
  $('#mute_user').html('Пользователь: ' + username);
  $('#mute_id').val(user_id);
  showPopup('popup-mute');
};

$.copyToClipboard = function (element) {
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
$(container).css({
  'opacity': 0
});

/***/ }),

/***/ "./resources/assets/js/routes.js":
/*!***************************************!*\
  !*** ./resources/assets/js/routes.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$.routes = function () {
  return {
    '/': ['/assets/js/pages/Jackpot.js'],
    '/battle': ['/assets/js/pages/Battle.js'],
    '/pvp': ['/assets/js/pages/Pvp.js'],
    '/double': ['/assets/js/pages/Double.js'],
    '/history': ['/assets/js/pages/GameHistory.js'],
    '/referral': ['/assets/js/pages/Referral.js'],
    '/bonus': ['/assets/js/pages/Bonus.js'],
    '/rules': ['/assets/js/pages/Rules.js'],
    '/help': ['/assets/js/pages/Help.js'],
    '/pay': ['/assets/js/pages/Payment.js'],
    '/fair': ['/assets/js/pages/Fair.js']
  };
};

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/*!****************************************!*\
  !*** ./resources/assets/sass/app.scss ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/sass/media.scss":
/*!******************************************!*\
  !*** ./resources/assets/sass/media.scss ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/sass/notifyme.scss":
/*!*********************************************!*\
  !*** ./resources/assets/sass/notifyme.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/assets/sass/tooltipster.scss":
/*!************************************************!*\
  !*** ./resources/assets/sass/tooltipster.scss ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*********************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/assets/js/app.js ./resources/assets/sass/app.scss ./resources/assets/sass/media.scss ./resources/assets/sass/notifyme.scss ./resources/assets/sass/tooltipster.scss ***!
  \*********************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /home/ploi/pvp.bulk.bet/resources/assets/js/app.js */"./resources/assets/js/app.js");
__webpack_require__(/*! /home/ploi/pvp.bulk.bet/resources/assets/sass/app.scss */"./resources/assets/sass/app.scss");
__webpack_require__(/*! /home/ploi/pvp.bulk.bet/resources/assets/sass/media.scss */"./resources/assets/sass/media.scss");
__webpack_require__(/*! /home/ploi/pvp.bulk.bet/resources/assets/sass/notifyme.scss */"./resources/assets/sass/notifyme.scss");
module.exports = __webpack_require__(/*! /home/ploi/pvp.bulk.bet/resources/assets/sass/tooltipster.scss */"./resources/assets/sass/tooltipster.scss");


/***/ })

/******/ });