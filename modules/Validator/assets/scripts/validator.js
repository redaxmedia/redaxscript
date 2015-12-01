/**
 * @tableofcontents
 *
 * 1. validator
 * 2. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. validator */

	$.fn.validator = function (options)
	{
		/* extend options */

		if (rs.modules.validator.options !== options)
		{
			options = $.extend({}, rs.modules.validator.options, options || {});
		}

		var urlParameter = '?doc=' + rs.baseURL + rs.registry.rewriteRoute + rs.registry.fullRoute + '&parser=' + options.parser + '&level=' + options.level + '&out=json';

		/* request data */

		$.ajax(
		{
			url: options.url + '/' + urlParameter,
			dataType: 'json',
			success: function (data)
			{
				var messages = data.messages,
					output = '';

				/* handle messages */

				if (messages.length)
				{
					for (var i in messages)
					{
						var that = messages[i],
							type = that.type,
							message = that.message,
							firstLine = that.firstLine,
							firstColumn = that.firstColumn,
							lastLine = that.lastLine,
							lastColumn = that.lastColumn,
							allowedTypes =
							[
								'success',
								'warning',
								'error'
							];

						/* type fallback */

						if ($.inArray(type, allowedTypes) === -1)
						{
							type = 'info';
						}

						/* collect output */

						output += '<ul class="rs-box-note rs-note-' + type + '">';
						output += '<li class="' + options.className.validatorMessage + '">' + message + '</li>';

						/* lines and columns */

						if (firstLine && firstColumn || lastLine && lastColumn)
						{
							output += '<li class="' + options.className.validatorDescription + '">' + rs.language._validator.from + rs.language.colon + ' ';

							/* first wording */

							if (firstLine && firstColumn)
							{
								output += rs.language._validator.line + ' ' + firstLine + rs.language.comma + ' ' + rs.language._validator.column + ' ' + firstColumn;
							}

							/* to wording */

							if (firstLine && firstColumn && lastLine && lastColumn)
							{
								output += ' ' + rs.language._validator.to + ' ';
							}

							/* last wording */

							if (lastLine && lastColumn)
							{
								output += rs.language._validator.line + ' ' + lastLine + rs.language.comma + ' ' + rs.language._validator.column + ' ' + lastColumn;
							}
							output += '</li>';
						}
						output += '</ul>';
					}
				}

				/* prepend output to body */

				if (output)
				{
					$('<div>' + output + '</div>').addClass(options.className.validatorBox).prependTo('body');
				}
			}
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.validator.init)
		{
			$.fn.validator(rs.modules.validator.options);
		}
	});
})(window.jQuery || window.Zepto);
