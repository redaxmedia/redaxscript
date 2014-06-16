/**
 * @tableofcontents
 *
 * 1. validator
 * 2. startup
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

		if (r.modules.validator.options !== options)
		{
			options = $.extend({}, r.modules.validator.options, options || {});
		}

		var urlParameter = '?doc=' + r.baseURL + r.constants.REWRITE_ROUTE + r.constants.FULL_ROUTE + '&parser=' + options.parser + '&level=' + options.level + '&out=json';

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

						output += '<ul class="box_note note_' + type + '">';
						output += '<li class="' + options.classString.validatorMessage + '">' + message + '</li>';

						/* lines and columns */

						if (firstLine && firstColumn || lastLine && lastColumn)
						{
							output += '<li class="' + options.classString.validatorDescription + '">' + l.validator.from + l.colon + ' ';

							/* first wording */

							if (firstLine && firstColumn)
							{
								output += l.validator.line + ' ' + firstLine + l.comma + ' ' + l.validator.column + ' ' + firstColumn;
							}

							/* to wording */

							if (firstLine && firstColumn && lastLine && lastColumn)
							{
								output += ' ' + l.validator.to + ' ';
							}

							/* last wording */

							if (lastLine && lastColumn)
							{
								output += l.validator.line + ' ' + lastLine + l.comma + ' ' + l.validator.column + ' ' + lastColumn;
							}
							output += '</li>';
						}
						output += '</ul>';
					}
				}

				/* prepend output to body */

				if (output)
				{
					$('<div>' + output + '</div>').addClass(options.classString.validatorBox).prependTo('body');
				}
			}
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		$.fn.validator(r.modules.validator.options);
	});
})(window.jQuery || window.Zepto);