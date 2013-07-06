/**
 * @tableofcontents
 *
 * 1. dialog
 * 2. confirm link
 * 3. prevent unload
 * 4. startup
 *
 * @since 2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. dialog */

	$.fn.dialog = function (options)
	{
		/* extend options */

		if (r.plugins.dialog.options !== options)
		{
			options = $.extend({}, r.plugins.dialog.options, options || {});
		}

		/* detect needed mode */

		if (r.constants.LOGGED_IN === r.constants.TOKEN && r.constants.FIRST_PARAMETER === 'admin')
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

		if (r.constants.MY_BROWSER === 'msie' && r.constants.MY_BROWSER_VERSION < 7 || dialog.length || dialogOverlay.length)
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

		if (r.constants.FIRST_PARAMETER !== 'admin')
		{
			options.suffix = '';
		}

		/* prompt */

		if (options.type === 'prompt')
		{
			output += '<input type="text" class="' + options.classString.fieldPrompt + options.suffix + '" value="' + options.value + '" />';
		}

		/* ok button */

		output += '<a class="' + options.classString.buttonOk + options.suffix + '">' + l.ok + '</a>';

		/* cancel button if confirm or prompt */

		if (options.type === 'confirm' || options.type === 'prompt')
		{
			output += '<a class="' + options.classString.buttonCancel + options.suffix + '">' + l.cancel + '</a>';
		}
		output += '</div></div>';

		/* append output to body */

		body.append(output);

		/* fade in overlay and dialog */

		dialogOverlay = body.find(options.element.dialogOverlay).css('opacity', 0).fadeTo(r.lightbox.overlay.duration, r.lightbox.overlay.opacity);
		dialog = body.find(options.element.dialog).css('opacity', 0).fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);

		/* find related buttons */

		buttonOk = dialog.find(options.element.buttonOk);
		buttonCancel = dialog.find(options.element.buttonCancel);

		/* close dialog on click */

		buttonOk.add(buttonCancel).add(dialogOverlay).click(function ()
		{
			dialog.add(dialogOverlay).remove();
		});

		/* callback if ok */

		if (typeof options.callback === 'function')
		{
			buttonOk.on('click', function ()
			{
				if (options.type === 'prompt')
				{
					var input = dialog.find(options.element.fieldPrompt),
						value = $.trim(input.val());

					if (value)
					{
						options.callback.call(this, value);
					}
				}
				else
				{
					options.callback.call(this);
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
				var link = $(this),
					url = link.attr('href');

				if (typeof url === 'string')
				{
					/* confirm dialog to continue */

					$.fn.dialog(
					{
						type: 'confirm',
						message: l.dialog_question + l.question_mark,
						callback: function ()
						{
							/* check for internal link */

							if (url.substr(0, 7) !== 'http://' && url.substr(0, 8) !== 'https://')
							{
								url = r.baseURL + url;
							}
							window.location = url;
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

		if (r.plugins.preventUnload.options !== options)
		{
			options = $.extend({}, r.plugins.preventUnload.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			if (r.constants.ADMIN_PARAMETER === 'new' || r.constants.ADMIN_PARAMETER === 'edit')
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
		if (r.plugins.confirmLink.startup)
		{
			$(r.plugins.confirmLink.selector).confirmLink();

			/* dependency */

			if (r.plugins.preventUnload.startup)
			{
				$(r.plugins.preventUnload.selector).preventUnload(r.plugins.preventUnload.options);
			}
		}
	});
})(window.jQuery || window.Zepto);