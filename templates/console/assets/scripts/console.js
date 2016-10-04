/**
 * @tableofcontents
 *
 * 1. console
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function (doc, win, $)
{
	'use strict';

	/* @section 1. console */

	$.fn.console = function (options)
	{
		/* extend options */

		if (rs.plugins.console.options !== options)
		{
			options = $.extend({}, rs.plugins.console.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var form = $(this),
				box = $(options.element.consoleBox),
				label = form.find(options.element.consoleLabel),
				field = form.find(options.element.consoleField),
				root = $(options.element.root);

			/* listen for submit */

			form.on('submit', function (event)
			{
				$.post(location.href,
				{
					argv: field.val()
				})
				.always(function (response)
				{
					var labelText = label.text(),
						fieldValue = field.val();

					box.append(labelText + ' ' + fieldValue + options.eol);
					if (typeof response === 'string')
					{
						box.append(response);
					}
					field.val(null);
					$(win).trigger('resize');
				});
				event.preventDefault();
			});

			/* listen for resize and click */

			$(win)
				.on('resize', function ()
				{
					var fieldWidth = box.width() - label.width() - 1,
						scrollHeight = $(doc).height() - $(win).height();

					root.scrollTop(scrollHeight);
					field.width(fieldWidth);
				})
				.on('click', function ()
				{
					field.focus();
				})
				.trigger('resize');

		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.plugins.console.init)
		{
			$(rs.plugins.console.selector).console(rs.plugins.console.options);
		}
	});
})(document, window, window.jQuery || window.Zepto);