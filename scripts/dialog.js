(function ($)
{
	/* dialog */

	$.fn.dialog = function (options)
	{
		var suffix = '_default',
			dialog = $('div.js_dialog'),
			dialogOverlay = $('div.js_dialog_overlay'),
			dialogClasses = 'js_dialog dialog',
			dialogOverlayClasses = 'js_dialog_overlay dialog_overlay',
			checkDialog = dialog.length || dialogOverlay.length,
			output;

		/* handle suffix */

		if (r.constant.FIRST_PARAMETER === 'admin')
		{
			suffix = '_admin';
		}
		dialogClasses += ' dialog' + suffix;
		dialogOverlayClasses += ' dialog_overlay' + suffix;

		/* prematurely terminate dialog */

		if (r.constant.MY_BROWSER === 'msie' && r.constant.MY_BROWSER_VERSION < 7 || checkDialog)
		{
			return false;
		}

		/* build dialog elements */

		output = '<div class="' + dialogOverlayClasses + '"></div><div class="js_dialog ' + dialogClasses + '"><h3 class="title_dialog' + suffix + '">' + l[options.type] + '</h3><div class="box_dialog' + suffix + '">';
		if (options.message)
		{
			output += '<p>' + options.message + '</p>';
		}

		/* manage suffix */

		if (r.constant.FIRST_PARAMETER !== 'admin')
		{
			suffix = '';
		}

		/* prompt */

		if (options.type === 'prompt')
		{
			output += '<input type="text" class="js_prompt field_text' + suffix + '" value="';
			if (options.value)
			{
				output += options.value;
			}
			output += '" />';
		}

		/* ok button */

		output += '<a class="js_ok field_button' + suffix + '"><span><span>' + l.ok + '</span></span></a>';

		/* cancel button if confirm or prompt */

		if (options.type === 'confirm' || options.type === 'prompt')
		{
			output += '<a class="js_cancel field_button' + suffix + '"><span><span>' + l.cancel + '</span></span></a>';
		}
		output += '</div></div>';
		$('body').append(output);

		/* fade in overlay and dialog */

		dialogOverlay = $('div.js_dialog_overlay').css('opacity', 0).fadeTo(r.lightbox.overlay.duration, r.lightbox.overlay.opacity);
		dialog = $('div.js_dialog').css('opacity', 0).fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);

		/* close dialog */

		dialog.find('a.js_cancel, a.js_ok').add(dialogOverlay).click(function ()
		{
			dialog.add(dialogOverlay).remove();
		});

		/* callback if ok */

		if (options.callback)
		{
			dialog.find('a.js_ok').click(function ()
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

	/* confirm link */

	$.fn.confirmLink = function ()
	{
		$(this).on('click', function ()
		{
			var string = $(this)[0].href,
				checkDialogPosition, dialog, dialogOverlay;

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

						/* timeout to fix opera */

						setTimeout(function ()
						{
							window.location = string;
						}, 0);
					}
				});

				/* check dialog position */

				dialog = $('div.js_dialog'),
				dialogOverlay = $('div.js_dialog_overlay'),
				checkDialogPosition = dialog.css('position');

				/* prevent link forward */

				if (checkDialogPosition === 'fixed')
				{
					return false;
				}

				/* else remove dialog */

				else
				{
					dialog.add(dialogOverlay).remove();
				}
			}
		});
	};

	/* prevent unload on admin */

	$.fn.preventUnload = function (options)
	{
		/* extend options */

		if (r.plugin.preventUnload.options !== options)
		{
			options = $.extend({}, r.plugin.preventUnload.options, options || {});
		}

		if (r.constant.ADMIN_PARAMETER === 'new' || r.constant.ADMIN_PARAMETER === 'edit')
		{
			$(this).one('change', function ()
			{
				$('a').not(options.excluded).confirmLink();
			});
		}
	};
})(jQuery);

$(function ()
{
	/* startup */

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