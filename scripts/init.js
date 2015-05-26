/**
 * @tableofcontents
 *
 * 1. redaxscript
 *    1.1 plugins
 *    1.2 base url
 *    1.3 helper
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function (doc, docElement, win)
{
	'use strict';

	win.rs = win.rs || {};

	/* @section 1. redaxscript */

	rs.flags = rs.flags || {};
	rs.modules = rs.modules || {};
	rs.support = rs.support || {};

	/* @section 1.1 plugins */

	rs.plugins =
	{
		accordion:
		{
			init: true,
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
			init: true,
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
			init: true,
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
			init: true,
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
			init: true,
			selector: 'form textarea.js_editor_textarea, form textarea.js_enable_indent',
			options:
			{
				eol: '\n',
				indent: '\t'
			}
		},
		forwardNotification:
		{
			init: true,
			selector: 'a.js_forward_notification',
			options:
			{
				duration: 1000
			}
		},
		generateAlias:
		{
			init: true,
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
			init: true,
			selector: 'form textarea.js_generate_keyword_input',
			options:
			{
				element:
				{
					related: 'textarea.js_generate_keyword_output',
					target: 'h1, h2, h3, strong'
				},
				delimiter: ' ',
				limit: 10
			}
		},
		keyShortcut:
		{
			init: true,
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
			init: true,
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
			init: true,
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
			init: true,
			selector: 'form input.js_unmask_password'
		},
		validateForm:
		{
			init: true,
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
			init: true,
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

	/* @section 1.2 base url */

	rs.baseURL = function ()
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

	/* @section 1.3 helper */

	rs.helper = function ()
	{
		/* javascript enabled */

		if (docElement.className)
		{
			docElement.className += ' ';
		}
		docElement.className += 'js';

		/* support classes */

		if (rs.support.canvas)
		{
			docElement.className += ' canvas';
		}
		else
		{
			docElement.className += ' no_canvas';
		}
		if (rs.support.svg)
		{
			docElement.className += ' svg';
		}
		else
		{
			docElement.className += ' no_svg';
		}
	}();
})(document, document.documentElement, window);
