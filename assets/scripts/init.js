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
			selector: 'div.rs-js-accordion, form.rs-js-accordion',
			options:
			{
				element:
				{
					accordionSet: 'div.rs-js-set-accordion, fieldset.rs-js-set-accordion',
					accordionTitle: 'h3.rs-js-title-accordion, legend.rs-js-title-accordion',
					accordionBox: 'div.rs-js-box-accordion, ul.rs-js-box-accordion'
				},
				duration: 600
			}
		},
		autoResize:
		{
			init: true,
			selector: 'form textarea.rs-js-auto-resize',
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
			selector: 'a.rs-js-confirm'
		},
		dialog:
		{
			options:
			{
				className:
				{
					dialog: 'rs-js-dialog rs-dialog rs-dialog',
					dialogTitle: 'rs-js-title-dialog rs-title-dialog rs-title-dialog',
					dialogBox: 'rs-js-box-dialog rs-box-dialog rs-box-dialog',
					dialogOverlay: 'rs-js-dialog-overlay rs-dialog-overlay rs-dialog-overlay',
					buttonOk: 'rs-js-ok rs-button',
					buttonCancel: 'rs-js-cancel rs-button',
					fieldPrompt: 'rs-js-prompt rs-field-text'
				},
				suffix:
				{
					backend: '-admin',
					frontend: '-default'
				},
				type: 'alert',
				message: '',
				callback: ''
			}
		},
		dropdown:
		{
			init: rs.support.touch,
			selector: 'ul.rs-js-dropdown',
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
			selector: 'form textarea.rs-js-editor-textarea, form textarea.rs-js-enable-indent',
			options:
			{
				eol: '\n',
				indent: '\t'
			}
		},
		forwardNotification:
		{
			init: true,
			selector: 'a.rs-js-forward-notification',
			options:
			{
				duration: 1000
			}
		},
		generateAlias:
		{
			init: true,
			selector: 'form input.rs-js-generate-alias-input, form input.rs-js-generate-alias-output',
			options:
			{
				element:
				{
					related: 'input.rs-js-generate-alias-output'
				}
			}
		},
		generateKeyword:
		{
			init: true,
			selector: 'form textarea.rs-js-generate-keyword-input',
			options:
			{
				element:
				{
					related: 'textarea.rs-js-generate-keyword-output',
					target: 'h1, h2, h3, strong'
				},
				splitter:
				{
					text: '\n',
					keyword: ' '
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
					adminDock: 'div.rs-admin-js-dock',
					adminPanel: 'nav.rs-admin-js-panel',
					buttonSubmit: 'form button.rs-js-submit',
					buttonOk: 'div a.rs-js-ok span span, form button.rs-js-ok',
					buttonCancel: 'div a.rs-js-cancel span span, form button.rs-js-cancel'
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
			selector: 'ul.rs-js-box-accordion, div.rs-js-box-tab',
			options:
			{
				element:
				{
					not: 'ul.rs-js-list-tab a'
				}
			}
		},
		tab:
		{
			init: true,
			selector: 'div.rs-js-tab, form.rs-js-tab',
			options:
			{
				element:
				{
					tabBox: 'div.rs-js-box-tab',
					tabItem: 'ul.rs-js-list-tab li',
					tabSet: 'div.rs-js-set-tab, fieldset.rs-js-set-tab'
				}
			}
		},
		unmaskPassword:
		{
			init: true,
			selector: 'form input.rs-js-unmask-password'
		},
		validateForm:
		{
			init: true,
			selector: 'form.rs-js-validate-form',
			options:
			{
				element:
				{
					buttonSubmit: 'button.rs-js-submit',
					field: 'div.rs-js-editor-preview, input, select, textarea'
				},
				autoFocus: true,
				message: true,
				vibrate: 300
			}
		},
		validateSearch:
		{
			init: rs.support.input && rs.support.input.placeholder,
			selector: 'form.rs-js-validate-search',
			options:
			{
				element:
				{
					field: 'input.rs-js-search'
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

	(function ()
	{
		/* javascript enabled */

		if (docElement.className)
		{
			docElement.className += ' ';
		}
		docElement.className += 'js';

		/* support classes */

		for (var i in rs.support)
		{
			if (typeof rs.support[i] === 'boolean')
			{
				if (rs.support[i])
				{
					docElement.className += ' ' + i.toLowerCase();
				}
				else
				{
					docElement.className += ' no-' + i.toLowerCase();
				}
			}
		}
	})();
})(document, document.documentElement, window);
