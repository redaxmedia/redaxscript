/**
 * @tableofcontents
 *
 * 1. support
 *    1.1 application cache
 *    1.2 battery
 *    1.3 canvas
 *    1.4 check validity
 *    1.5 css transform
 *    1.6 css transition
 *    1.7 cookies
 *    1.8 draggable
 *    1.9 geolocation
 *    1.10 form
 *    1.11 fullscreen
 *    1.12 history
 *    1.13 index db
 *    1.14 input
 *    1.15 native json
 *    1.16 post message
 *    1.17 speech
 *    1.18 svg
 *    1.19 touch
 *    1.20 vibrate
 *    1.21 web gl
 *    1.22 web sql
 *    1.23 web socket
 *    1.24 web storage
 *    1.25 web worker
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function (doc, win, nav)
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

		/* @section 1.5 css transform */

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

		/* @section 1.6 css transition */

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

		/* @section 1.7 cookies */

		cookies: function ()
		{
			if (nav.cookieEnabled)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.8 draggable */

		draggable: function ()
		{
			if ('draggable' in doc.createElement('span'))
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.9 geolocation */

		geolocation: function ()
		{
			if (typeof nav.geolocation === 'object')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.10 form */

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

		/* @section 1.11 fullscreen */

		fullscreen: function ()
		{
			if (doc.fullscreenEnabled || doc.webkitFullscreenEnabled || doc.mozFullScreenEnabled || doc.msFullscreenEnabled)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.12 history */

		history: function ()
		{
			if (typeof win.history === 'object' && typeof win.history.pushState === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.13 index db */

		indexedDB: function ()
		{
			if ('indexedDB' in win)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.14 input */

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

		/* @section 1.15 native json */

		nativeJSON: function (json)
		{
			if (typeof json === 'object' && typeof json.parse === 'function' && typeof json.stringify === 'function')
			{
				return true;
			}
			return false;
		}(win.JSON),

		/* @section 1.16 post message */

		postMessage: function ()
		{
			if (typeof win.postMessage === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.17 speech */

		speech: function ()
		{
			if ('speechSynthesis' in win)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.18 svg */

		svg: function ()
		{
			if (typeof doc.createElementNS === 'function' && typeof doc.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.19 touch */

		touch: function ()
		{
			if ('touchstart' in win || nav.msPointerEnabled)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.20 vibrate */

		vibrate: function ()
		{
			if ('vibrate' in nav)
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.21 web gl */

		webGL: function ()
		{
			if (typeof win.WebGLRenderingContext === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.22 web sql */

		webSQL: function ()
		{
			if (typeof win.openDatabase === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.23 web socket */

		webSocket: function ()
		{
			if (typeof win.WebSocket === 'function')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.24 web storage */

		webStorage: function ()
		{
			if (nav.cookieEnabled && typeof win.localStorage === 'object' && typeof win.sessionStorage === 'object')
			{
				return true;
			}
			return false;
		}(),

		/* @section 1.25 web worker */

		webWorker: function ()
		{
			if (typeof win.Worker === 'function')
			{
				return true;
			}
			return false;
		}()
	};
})(document, window, window.navigator);
