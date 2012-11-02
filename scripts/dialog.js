/**
 * @tableofcontents
 *
 * 1. dialog
 * 2. confirm link
 * 3. prevent unload
 * 4. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. dialog */

	$.fn.dialog = function (options)
	{
		/* extend options */

		if (r.plugin.dialog.options !== options)
		{
			options = $.extend({}, r.plugin.dialog.options, options || {});
		}

		/* detect needed mode */

		if (r.constant.FIRST_PARAMETER === 'admin')
		{
			options.suffix = options.suffix.backend;
		}
		else
		{
			options.suffix = options.suffix.frontend;
		}

		var body = $('body'),
			dialog = body.find(options.element.dialog),
			dialogOverlay = body.find(options.element.dialogOverlay),
			buttonOk, buttonCancel, output = '';

		/* prematurely terminate dialog */

		if (r.constant.MY_BROWSER === 'msie' && r.constant.MY_BROWSER_VERSION < 7 || dialog.length || dialogOverlay.length)
		{
			return false;
		}

		/* collect overlay */

		output = '<div class="' + options.classString.dialogOverlay + options.suffix + '"></div>';

		/* collect dialog elements */

		output += '<div class="' + options.classString.dialog + options.suffix + '"><h3 class="' + options.classString.dialogTitle + options.suffix + '">' + l[options.type] + '</h3><div class="' + options.classString.dialogBox + options.suffix + '">';
		if (options.message)
		{
			output += '<p>' + options.message + '</p>';
		}

		/* manage suffix */

		if (r.constant.FIRST_PARAMETER !== 'admin')
		{
			options.suffix = '';
		}

		/* prompt */

		if (options.type === 'prompt')
		{
			output += '<input type="text" class="js_prompt field_text' + options.suffix + '" value="' + options.value + '" />';
		}

		/* ok button */

		output += '<a class="js_ok field_button' + options.suffix + '"><span><span>' + l.ok + '</span></span></a>';

		/* cancel button if confirm or prompt */

		if (options.type === 'confirm' || options.type === 'prompt')
		{
			output += '<a class="js_cancel field_button' + options.suffix + '"><span><span>' + l.cancel + '</span></span></a>';
		}
		output += '</div></div>';

		/* append output to body */

		body.append(output);

		/* fade in overlay and dialog */

		dialogOverlay = body.find(options.element.dialogOverlay).css('opacity', 0).fadeTo(r.lightbox.overlay.duration, r.lightbox.overlay.opacity);
		dialog = body.find(options.element.dialog).css('opacity', 0).fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);
		buttonOk = dialog.find(options.element.buttonOk);
		buttonCancel = dialog.find(options.element.buttonCancel);

		/* close dialog on click */

		buttonOk.add(buttonCancel).add(dialogOverlay).click(function ()
		{
			dialog.add(dialogOverlay).remove();
		});

		/* callback if ok */

		if (options.callback)
		{
			buttonOk.click(function ()
			{
				if (options.type === 'prompt')
				{
					var value = dialog.find('input.js_prompt')[0].value;

					options.callback.call($(this), value);
				}
				else
				{
					options.callback.call($(this));
				}
			});
		}
	};

	/* @section 2. confirm link */

	$.fn.confirmLink = function ()
	{
		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function (event)
			{
				var string = $(this)[0].href;

				if (string)
				{
					/* confirm dialog to continue */

					$.fn.dialog(
					{
						type: 'confirm',
						message: l.dialog_question + l.question_mark,
						callback: function ()
						{
							/* check for internal link */

							if (string.substr(0, 7) !== 'http://' && string.substr(0, 8) !== 'https://')
							{
								string = r.baseURL + string;
							}
							window.location = string;
						}
					});
					event.preventDefault();
				}
			});
		});
	};

	/* @section 3. prevent unload */

	$.fn.preventUnload = function (options)
	{
		/* extend options */

		if (r.plugin.preventUnload.options !== options)
		{
			options = $.extend({}, r.plugin.preventUnload.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			if (r.constant.ADMIN_PARAMETER === 'new' || r.constant.ADMIN_PARAMETER === 'edit')
			{
				/* listen for change */

				$(this).one('change', function ()
				{
					$('a').not(options.excluded).confirmLink();
				});
			}
		});
	};

	/* @section 4. startup */

	$(function ()
	{
		if (r.plugin.confirmLink.startup)
		{
			$(r.plugin.confirmLink.selector).confirmLink();

			/* depending startup */

			if (r.plugin.preventUnload.startup)
			{
				$(r.plugin.preventUnload.selector).preventUnload(r.plugin.preventUnload.options);
			}
		}
	});
})(jQuery);