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
			selector: 'div.rs-admin-js-accordion, form.rs-admin-js-accordion, div.rs-js-accordion, form.rs-js-accordion',
			options:
			{
				element:
				{
					accordionSet: 'div.rs-admin-js-set-accordion, fieldset.rs-admin-js-set-accordion, div.rs-js-set-accordion, fieldset.rs-js-set-accordion',
					accordionTitle: 'h3.rs-admin-js-title-accordion, legend.rs-admin-js-title-accordion, h3.rs-js-title-accordion, legend.rs-js-title-accordion',
					accordionBox: 'div.rs-admin-js-box-accordion, ul.rs-admin-js-box-accordion, div.rs-js-box-accordion, ul.rs-js-box-accordion'
				},
				duration: 300
			}
		},
		autoResize:
		{
			init: true,
			selector: 'form textarea.rs-admin-js-auto-resize, form textarea.rs-js-auto-resize',
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
			selector: 'a.rs-admin-js-confirm, a.rs-js-confirm'
		},
		dialog:
		{
			options:
			{
				className:
				{
					backend:
					{
						dialog: 'rs-admin-js-dialog rs-admin-dialog rs-admin-dialog',
						dialogTitle: 'rs-admin-js-title-dialog rs-admin-title-dialog rs-admin-title-dialog',
						dialogBox: 'rs-admin-js-box-dialog rs-admin-box-dialog rs-admin-box-dialog',
						dialogOverlay: 'rs-admin-js-dialog-overlay rs-admin-dialog-overlay rs-admin-dialog-overlay',
						buttonOk: 'rs-admin-js-ok rs-admin-button-default',
						buttonCancel: 'rs-admin-js-cancel rs-admin-button-default',
						fieldPrompt: 'rs-admin-js-prompt rs-admin-field-text'
					},
					frontend:
					{
						dialog: 'rs-js-dialog rs-dialog rs-dialog',
						dialogTitle: 'rs-js-title-dialog rs-title-dialog rs-title-dialog',
						dialogBox: 'rs-js-box-dialog rs-box-dialog rs-box-dialog',
						dialogOverlay: 'rs-js-dialog-overlay rs-dialog-overlay rs-dialog-overlay',
						buttonOk: 'rs-js-ok rs-button-default',
						buttonCancel: 'rs-js-cancel rs-button-default',
						fieldPrompt: 'rs-js-prompt rs-field-text'
					}
				},
				type: 'alert'
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
					buttonSubmit: 'form button.rs-admin-js-submit',
					buttonOk: 'div a.rs-admin-js-ok, form button.rs-admin-js-ok',
					buttonCancel: 'div a.rs-admin-js-cancel, form button.rs-admin-js-cancel'
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
			selector: 'ul.rs-admin-js-box-accordion, div.rs-admin-js-box-tab, ul.rs-js-box-accordion, div.rs-js-box-tab',
			options:
			{
				element:
				{
					not: 'ul.rs-admin-js-list-tab a, ul.rs-js-list-tab a'
				}
			}
		},
		tab:
		{
			init: true,
			selector: 'div.rs-admin-js-tab, form.rs-admin-js-tab, div.rs-js-tab, form.rs-js-tab',
			options:
			{
				element:
				{
					tabBox: 'div.rs-admin-js-box-tab, div.rs-js-box-tab',
					tabItem: 'ul.rs-admin-js-list-tab li, ul.rs-js-list-tab li',
					tabSet: 'div.rs-admin-js-set-tab, fieldset.rs-admin-js-set-tab, div.rs-js-set-tab, fieldset.rs-js-set-tab'
				}
			}
		},
		unmaskPassword:
		{
			init: true,
			selector: 'form input.rs-admin-js-unmask-password, form input.rs-js-unmask-password',
			options:
			{
				keyCode:
				{
					unmask: 85
				}
			}
		},
		validateForm:
		{
			init: true,
			selector: 'form.rs-admin-js-validate-form, form.rs-js-validate-form',
			options:
			{
				element:
				{
					buttonSubmit: 'button.rs-admin-js-submit, button.rs-js-submit',
					field: 'div.rs-admin-js-editor-preview, div.rs-js-editor-preview, input, select, textarea'
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
			output = null;

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
		docElement.className += 'rs-js';

		/* support classes */

		for (var i in rs.support)
		{
			if (typeof rs.support[i] === 'boolean')
			{
				if (rs.support[i])
				{
					docElement.className += ' rs-' + i.toLowerCase();
				}
				else
				{
					docElement.className += ' rs-no-' + i.toLowerCase();
				}
			}
		}
	})();
})(document, document.documentElement, window);
