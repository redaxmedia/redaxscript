/**
 * @tableofcontents
 *
 * 1. redaxscript object
 *    1.1 plugins
 *    1.2 modules
 *    1.3 flags
 *    1.4 support
 *    1.5 base url
 *    1.6 startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function (doc, docElement, win, nav)
{
	'use strict';

	/* @section 1. redaxscript object */

	win.r = win.r || {};

	/* @section 1.1 plugins */

	r.plugins =
	{
		accordion:
		{
			startup: true,
			selector: 'div.js_accordion, form.js_accordion',
			options:
			{
				element:
				{
					accordionSet: 'div.js_set_accordion, fieldset.js_set_accordion',
					accordionTitle: 'h3.js_title_accordion, legend.js_title_accordion',
					accordionBox: 'div.js_box_accordion, ul.js_box_accordion'
				},
				duration: 600
			}
		},
		autoResize:
		{
			startup: true,
			selector: 'form textarea.js_auto_resize',
			options:
			{
				overflow: 'hidden',
				resize: 'none',
				limit: 1000,
				eol: '\n'
			}
		},
		confirmLink:
		{
			startup: true,
			selector: 'a.js_confirm'
		},
		dialog:
		{
			options:
			{
				className:
				{
					dialog: 'js_dialog dialog dialog',
					dialogTitle: 'js_title_dialog title_dialog title_dialog',
					dialogBox: 'js_box_dialog box_dialog box_dialog',
					dialogOverlay: 'js_dialog_overlay dialog_overlay dialog_overlay',
					buttonOk: 'js_ok button',
					buttonCancel: 'js_cancel button',
					fieldPrompt: 'js_prompt field_text'
				},
				suffix:
				{
					backend: '_admin',
					frontend: '_default'
				},
				type: 'alert',
				message: '',
				callback: ''
			}
		},
		dropdown:
		{
			startup: true,
			selector: 'ul.js_dropdown',
			options:
			{
				element:
				{
					item: 'li'
				},
				duration: 2000
			}
		},
		enableIndent:
		{
			startup: true,
			selector: 'form textarea.js_editor_textarea, form textarea.js_enable_indent',
			options:
			{
				eol: '\n',
				indent: '\t'
			}
		},
		forwardNotification:
		{
			startup: true,
			selector: 'a.js_forward_notification',
			options:
			{
				duration: 1000
			}
		},
		generateAlias:
		{
			startup: true,
			selector: 'form input.js_generate_alias_input, form input.js_generate_alias_output',
			options:
			{
				element:
				{
					related: 'input.js_generate_alias_output'
				}
			}
		},
		generateKeyword:
		{
			startup: true,
			selector: 'form textarea.js_generate_keyword_input',
			options:
			{
				element:
				{
					related: 'textarea.js_generate_keyword_output'
				},
				tag:
				[
					'h1',
					'h2',
					'h3',
					'strong',
					'em'
				],
				delimiter: ' ',
				limit: 10
			}
		},
		keyShortcut:
		{
			startup: true,
			selector: win,
			options:
			{
				element:
				{
					adminDock: 'div.js_dock_admin',
					adminPanel: 'nav.js_panel_admin',
					buttonSubmit: 'form button.js_submit',
					buttonOk: 'div a.js_ok span span, form button.js_ok',
					buttonCancel: 'div a.js_cancel span span, form button.js_cancel'
				},
				keyCode:
				{
					cancel: 67,
					dock: 68,
					log: 76,
					ok: 79,
					toggle: 80,
					submit: 83
				}
			},
			routes:
			{
				login: 'login',
				logout: 'logout'
			}
		},
		preventUnload:
		{
			startup: true,
			selector: 'ul.js_box_accordion, div.js_box_tab',
			options:
			{
				element:
				{
					not: 'ul.js_list_tab a'
				}
			}
		},
		tab:
		{
			startup: true,
			selector: 'div.js_tab, form.js_tab',
			options:
			{
				element:
				{
					tabBox: 'div.js_box_tab',
					tabItem: 'ul.js_list_tab li',
					tabSet: 'div.js_set_tab, fieldset.js_set_tab'
				}
			}
		},
		unmaskPassword:
		{
			startup: true,
			selector: 'form input.js_unmask_password'
		},
		validateForm:
		{
			startup: true,
			selector: 'form.js_validate_form',
			options:
			{
				element:
				{
					buttonSubmit: 'button.js_submit',
					field: 'div.js_editor_preview, input, select, textarea'
				},
				autoFocus: true,
				message: true,
				vibrate: 300
			}
		},
		validateSearch:
		{
			startup: true,
			selector: 'form.js_validate_search',
			options:
			{
				element:
				{
					field: 'input.js_search'
				},
				duration: 1000
			}
		}
	};

	/* @section 1.2 modules */

	r.modules = {};

	/* @section 1.3 flags */

	r.flags = {};

	/* @section 1.4 support */

	r.support =
	{
		applicationCache: function ()
		{
			if (typeof win.applicationCache === 'object')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		battery: function ()
		{
			if ('battery' in nav)
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		canvas: function ()
		{
			if (typeof doc.createElement('canvas').getContext === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		checkValidity: function ()
		{
			if (typeof doc.createElement('input').checkValidity === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		cookies: function ()
		{
			if (nav.cookieEnabled)
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		draggable: function ()
		{
			if ('draggable' in doc.createElement('span'))
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		geolocation: function ()
		{
			if (typeof nav.geolocation === 'object')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		form: function ()
		{
			var attributes =
				[
					'autocomplete',
					'noValidate'
				],
				form = doc.createElement('form'),
				output = {};

			/* check attributes */

			for (var i in attributes)
			{
				var attribute = attributes[i];

				if (attribute in form)
				{
					output[attribute] = true;
				}
				else
				{
					output[attribute] = false;
				}
			}
			return output;
		}(),
		history: function ()
		{
			if (typeof win.history === 'object' && typeof win.history.pushState === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		indexedDB: function ()
		{
			if ('indexedDB' in win)
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
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

			/* check types */

			for (var i in types)
			{
				var type = types[i];

				input.setAttribute('type', type);
				if (input.type === type)
				{
					output[type] = true;
				}
				else
				{
					output[type] = false;
				}
			}

			/* check attributes */

			for (var j in attributes)
			{
				var attribute = attributes[j];

				if (attribute in input)
				{
					output[attribute] = true;
				}
				else
				{
					output[attribute] = false;
				}
			}
			return output;
		}(),
		nativeJSON: function (json)
		{
			if (typeof json === 'object' && typeof json.parse === 'function' && typeof json.stringify === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(win.JSON),
		postMessage: function ()
		{
			if (typeof win.postMessage === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		svg: function ()
		{
			if (typeof doc.createElementNS === 'function' && typeof doc.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		touch: function ()
		{
			if ('ontouchstart' in docElement)
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		vibrate: function ()
		{
			if ('vibrate' in nav)
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		webGL: function ()
		{
			if (typeof win.WebGLRenderingContext === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		webSQL: function ()
		{
			if (typeof win.openDatabase === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		webSocket: function ()
		{
			if (typeof win.WebSocket === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		webStorage: function ()
		{
			if (nav.cookieEnabled && typeof win.localStorage === 'object' && typeof win.sessionStorage === 'object')
			{
				return true;
			}
			else
			{
				return false;
			}
		}(),
		webWorker: function ()
		{
			if (typeof win.Worker === 'function')
			{
				return true;
			}
			else
			{
				return false;
			}
		}()
	};

	/* @section 1.5 base url */

	r.baseURL = function ()
	{
		var base = doc.getElementsByTagName('base'),
			checkBase = base.length,
			output = '';

		if (checkBase)
		{
			output = base[0].href;
		}
		return output;
	}();

	/* @section 1.6 startup */

	r.startup = function ()
	{
		/* javascript enabled */

		if (docElement.className)
		{
			docElement.className += ' ';
		}
		docElement.className += 'js';

		/* support classes */

		if (r.support.canvas)
		{
			docElement.className += ' canvas';
		}
		else
		{
			docElement.className += ' no_canvas';
		}
		if (r.support.svg)
		{
			docElement.className += ' svg';
		}
		else
		{
			docElement.className += ' no_svg';
		}
		return true;
	}();
})(document, document.documentElement, window, window.navigator);
