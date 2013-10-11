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
			r.flags.backend = true;
		}
		else
		{
			options.suffix = options.suffix.frontend;
		}

		var body = $('body'),
			dialogOverlay = $('<div>').addClass(options.classString.dialogOverlay + options.suffix),
			dialog = $('<div>').addClass(options.classString.dialog + options.suffix),
			dialogTitle = $('<h3>' + l[options.type] + '</h3>').addClass(options.classString.dialogTitle + options.suffix),
			dialogBox = $('<div>').addClass(options.classString.dialogBox + options.suffix),
			buttonOk = $('<a>' + l.ok + '</a>').addClass(options.classString.buttonOk + options.suffix),
			buttonCancel = $('<a>' + l.cancel + '</a>').addClass(options.classString.buttonCancel + options.suffix),
			textMessage = '',
			fieldPrompt = '';

		/* prematurely terminate dialog */

		if (r.constants.MY_BROWSER === 'msie' && r.constants.MY_BROWSER_VERSION < 7 || r.flags.dialog === true)
		{
			return false;
		}
		else
		{
			r.flags.dialog = true;
		}

		/* append basic elements */

		dialogTitle.add(dialogBox).appendTo(dialog);

		/* append message */

		if (options.message)
		{
			textMessage = $('<p>' + options.message + '</p>').appendTo(dialogBox);
		}

		/* prompt input */

		if (options.type === 'prompt')
		{
			fieldPrompt = $('<input type="text" class="' + options.classString.fieldPrompt + (r.flags.backend === true ? options.suffix : '') + '" value="' + options.value + '" />').appendTo(dialogBox);
		}

		/* ok button */

		buttonOk.appendTo(dialogBox);

		/* cancel button */

		if (options.type === 'confirm' || options.type === 'prompt')
		{
			buttonCancel.appendTo(dialogBox);
		}

		/* append output to body */

		dialog.add(dialogOverlay).appendTo(body);

		/* close dialog on click */

		buttonOk.add(buttonCancel).add(dialogOverlay).on('click', function ()
		{
			dialog.add(dialogOverlay).remove();
			r.flags.dialog = false;
		});

		/* callback if ok */

		if (typeof options.callback === 'function')
		{
			buttonOk.on('click', function ()
			{
				if (options.type === 'prompt')
				{
					var value = $.trim(fieldPrompt.val());

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