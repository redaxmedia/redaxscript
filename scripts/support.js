/**
 * @tableofcontents
 *
 * 1. support
 *    1.1 application cache
 *    1.2 battery
 *    1.3 canvas
 *    1.4 check validity
 *    1.5 css flexbox
 *    1.6 css transform
 *    1.7 css transition
 *    1.8 css viewport unit
 *    1.9 cookies
 *    1.10 draggable
 *    1.11 geolocation
 *    1.12 form
 *    1.13 fullscreen
 *    1.14 history
 *    1.15 index db
 *    1.16 input
 *    1.17 native json
 *    1.18 post message
 *    1.19 speech
 *    1.20 svg
 *    1.21 touch
 *    1.22 vibrate
 *    1.23 web gl
 *    1.24 web sql
 *    1.25 web socket
 *    1.26 web storage
 *    1.27 web worker
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function (doc, docElement, win, nav)
{
	'use strict';

	win.rs = win.rs || {};

	/* @section 1. support */

	rs.support =
	{
		/* @section 1.1 application cache */

		applicationCache: function ()
		{
			if (typeof win.applicationCache === 'object')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.2 battery */

		battery: function ()
		{
			if ('battery' in nav)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.3 canvas */

		canvas: function ()
		{
			if (typeof doc.createElement('canvas').getContext === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.4 check validity */

		checkValidity: function ()
		{
			if (typeof doc.createElement('input').checkValidity === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.5 css flexbox */

		cssFlexbox: function ()
		{
			if ('flexWrap' in docElement.style || 'WebkitFlexWrap' in docElement.style || 'msFlexWrap' in docElement.style)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.6 css transform */

		cssTransform: function ()
		{
			var transform =
				[
					'transform',
					'WebkitTransform',
					'MozTransform',
					'msTransform',
					'OTransform'
				],
				span = doc.createElement('span');

			/* process transform */

			for (var i in transform)
			{
				if (transform[i] in span.style)
				{
					return true;
				}
			}
			return false;
		}(),

		/* @section 1.7 css transition */

		cssTransition: function ()
		{
			var transition =
				[
					'transition',
					'WebkitTransition',
					'MozTransition',
					'msTransition',
					'OTransition'
				],
				span = doc.createElement('span');

			/* process transition */

			for (var i in transition)
			{
				if (transition[i] in span.style)
				{
					return true;
				}
			}
			return false;
		}(),

		/* @section 1.8 css viewport unit */

		cssViewportUnit: function ()
		{
			var span = doc.createElement('span'),
				computedStyle = win.getComputedStyle ? getComputedStyle(span) : span.currentStyle;

			span.style = 'height: 50vh; width: 50vw;';
			if (parseInt(win.innerHeight / 2) === parseInt(computedStyle.height) && parseInt(win.innerWidth / 2) === parseInt(computedStyle.width))
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.9 cookies */

		cookies: function ()
		{
			if (nav.cookieEnabled)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.10 draggable */

		draggable: function ()
		{
			if ('draggable' in doc.createElement('span'))
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.11 geolocation */

		geolocation: function ()
		{
			if (typeof nav.geolocation === 'object')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.12 form */

		form: function ()
		{
			var attributes =
				[
					'autocomplete',
					'noValidate'
				],
				form = doc.createElement('form'),
				output = {};

			/* process attributes */

			for (var i in attributes)
			{
				var attribute = attributes[i];

				output[attribute] = false;
				if (attribute in form)
				{
					output[attribute] = true;
				}
			}
			return output;
		}(),

		/* @section 1.13 fullscreen */

		fullscreen: function ()
		{
			if (doc.fullscreenEnabled || doc.webkitFullscreenEnabled || doc.mozFullScreenEnabled || doc.msFullscreenEnabled)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.14 history */

		history: function ()
		{
			if (typeof win.history === 'object' && typeof win.history.pushState === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.15 index db */

		indexedDB: function ()
		{
			if ('indexedDB' in win)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.16 input */

		input: function ()
		{
			var types =
				[
					'color',
					'date',
					'datetime',
					'datetime-local',
					'email',
					'month',
					'number',
					'range',
					'search',
					'tel',
					'time',
					'url',
					'week'
				],
				attributes =
				[
					'autocomplete',
					'autofocus',
					'pattern',
					'placeholder',
					'required'
				],
				input = doc.createElement('input'),
				output = {};

			/* process types */

			for (var i in types)
			{
				var type = types[i];

				output[type] = false;
				input.setAttribute('type', type);
				if (input.type === type)
				{
					output[type] = true;
				}
			}

			/* process attributes */

			for (var j in attributes)
			{
				var attribute = attributes[j];

				output[attribute] = false;
				if (attribute in input)
				{
					output[attribute] = true;
				}
			}
			return output;
		}(),

		/* @section 1.17 native json */

		nativeJSON: function (json)
		{
			if (typeof json === 'object' && typeof json.parse === 'function' && typeof json.stringify === 'function')
			{
				return true;
			}
			return false;
		}(win.JSON),

		/* @section 1.18 post message */

		postMessage: function ()
		{
			if (typeof win.postMessage === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.19 speech */

		speech: function ()
		{
			if ('speechSynthesis' in win)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.20 svg */

		svg: function ()
		{
			if (typeof doc.createElementNS === 'function' && typeof doc.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.21 touch */

		touch: function ()
		{
			if ('touchstart' in win || nav.msPointerEnabled)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.22 vibrate */

		vibrate: function ()
		{
			if ('vibrate' in nav)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.23 web gl */

		webGL: function ()
		{
			if (typeof win.WebGLRenderingContext === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.24 web sql */

		webSQL: function ()
		{
			if (typeof win.openDatabase === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.25 web socket */

		webSocket: function ()
		{
			if (typeof win.WebSocket === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.26 web storage */

		webStorage: function ()
		{
			if (nav.cookieEnabled && typeof win.localStorage === 'object' && typeof win.sessionStorage === 'object')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.27 web worker */

		webWorker: function ()
		{
			if (typeof win.Worker === 'function')
			{
				return true;
			}
			return false;
		}()
	};
})(document, document.documentElement, window, window.navigator);
