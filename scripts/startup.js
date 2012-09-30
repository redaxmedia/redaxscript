/* redaxscript object */

var r = r || {};

/* define lightbox */

r.lightbox =
{
	overlay:
	{
		opacity: 0.25,
		duration: 'slow'
	},
	loading:
	{
		opacity: 1,
		duration: 'slow'
	},
	body:
	{
		opacity: 1,
		duration: 'fast'
	}
};

/* define plugin */

r.plugin =
{
	accordion:
	{
		startup: true,
		selector: 'div.js_accordion h3.js_title_accordion, form.js_accordion legend.js_title_accordion',
		options:
		{
			element:
			{
				accordion: 'div.js_accordion, form.js_accordion',
				accordionBox: 'div.js_box_accordion, ul.js_box_accordion',
				accordionSet: 'div.js_set_accordion, fieldset.js_set_accordion',
				accordionTitle: 'h3.js_title_accordion, legend.js_title_accordion'

			},
			duration: 'normal'
		}
	},
	autoResize:
	{
		startup: true,
		selector: 'form textarea.js_auto_resize',
		options:
		{
			overflow: 'hidden',
			summand: 10
		}
	},
	dialog:
	{
		options:
		{
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
			related: 'li',
			duration: 1000
		}
	},
	checkRequired:
	{
		startup: true,
		selector: 'form.js_check_required',
		options:
		{
			required: 'div.js_required, input.js_required, select.js_required, textarea.js_required'
		}
	},
	checkSearch:
	{
		startup: true,
		selector: 'form.js_check_search',
		options:
		{
			required: 'input.js_required',
			duration: 1000
		}
	},
	clearFocus:
	{
		startup: true,
		selector: 'form input.js_clear_focus, form textarea.js_clear_focus'
	},
	confirmLink:
	{
		startup: true,
		selector: 'a.js_confirm'
	},
	forwardNotification:
	{
		startup: true,
		selector: 'a.js_forward_notification span span',
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
			related: 'input.js_generate_alias_output'
		}
	},
	keyShortcut:
	{
		startup: true,
		selector: window,
		options:
		{
			element:
			{
				adminDock: 'div.js_dock_admin',
				buttonCancel: 'div a.js_cancel span span, form button.js_cancel',
				buttonOk: 'div a.js_ok span span, form button.js_ok',
				buttonSubmit: 'form button.js_submit'
			}
		}
	},
	noteRequired:
	{
		startup: true,
		selector: 'form.js_note_required',
		options:
		{
			markup: '<div class="js_note_required note_required box_note"></div>',
			related: 'a, button',
			duration: 1000
		}
	},
	preventUnload:
	{
		startup: true,
		selector: 'ul.js_box_accordion, div.js_box_tab',
		options:
		{
			excluded: 'ul.js_list_tab a'
		}
	},
	tab:
	{
		startup: true,
		selector: 'ul.js_list_tab li',
		options:
		{
			element:
			{
				tabBox: 'div.js_box_tab',
				tabList: 'ul.js_list_tab',
				tabSet: 'div.js_set_tab, fieldset.js_set_tab'
			}
		}
	},
	unmaskPassword:
	{
		startup: true,
		selector: 'form input.js_unmask_password'
	}
};

/* define module */

r.module = {};

/* define base url */

r.baseURL = function ()
{
	var base = document.getElementsByTagName('base'),
		checkBase = base.length,
		output;

	if (checkBase)
	{
		output = base[0].href;
	}
	else
	{
		output = '';
	}
	return output;
}();

/* startup */

r.startup = function (html)
{
	if (html.className)
	{
		html.className += ' ';
	}
	html.className += 'js';
	return true;
}(document.documentElement);