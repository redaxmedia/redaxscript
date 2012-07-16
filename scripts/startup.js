/* redaxscript object */

var r =
	{
		lightbox:
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
		},
		plugin:
		{
			accordion:
			{
				startup: 1,
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
				startup: 1,
				selector: 'form textarea.js_auto_resize',
				options:
				{
					overflow: 'hidden',
					summand: 10
				}
			},
			dropDown:
			{
				startup: 1,
				selector: 'ul.js_dropdown'
			},
			checkRequired:
			{
				startup: 1,
				selector: 'form.js_check_required',
				options:
				{
					required: 'div.js_required, input.js_required, select.js_required, textarea.js_required'
				}
			},
			checkSearch:
			{
				startup: 1,
				selector: 'form.js_check_search',
				options:
				{
					required: 'input.js_required',
					duration: 1000
				}
			},
			clearFocus:
			{
				startup: 1,
				selector: 'form input.js_clear_focus, form textarea.js_clear_focus'
			},
			confirmLink:
			{
				startup: 1,
				selector: 'a.js_confirm'
			},
			forwardNotification:
			{
				startup: 1,
				selector: 'a.js_forward_notification span span',
				options:
				{
					duration: 1000
				}
			},
			generateAlias:
			{
				startup: 1,
				selector: 'form input.js_generate_alias_input, form input.js_generate_alias_output',
				options:
				{
					related: 'input.js_generate_alias_output'
				}
			},
			keyShortcut :
			{
				startup: 1,
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
				startup: 1,
				selector: 'form.js_note_required',
				options:
				{
					related: 'a, button',
					duration: 1000
				}
			},
			preventUnload:
			{
				startup: 1,
				selector: 'ul.js_box_accordion, div.js_box_tab_menue',
				options:
				{
					excluded: 'ul.js_list_tab_menue a'
				}
			},
			tabMenue:
			{
				startup: 1,
				selector: 'ul.js_list_tab_menue li',
				options:
				{
					element:
					{
						tabMenueBox: 'div.js_box_tab_menue',
						tabMenueList: 'ul.js_list_tab_menue',
						tabMenueSet: 'div.js_set_tab_menue, fieldset.js_set_tab_menue'
					}
				}
			},
			unmaskPassword:
			{
				startup: 1,
				selector: 'form input.js_unmask_password'
			}
		},
		module: {},
		baseURL: document.getElementsByTagName('base')[0].href,
		startup: function ()
		{
			if (document.documentElement.className)
			{
				document.documentElement.className += ' ';
			}
			document.documentElement.className += 'js';
		}
	};