/**
 * @tableofcontents
 *
 * 1. validator
 * 2. startup
 *
 * @since 2.0
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

		var parameter = '?doc=' + r.baseURL + r.constants.REWRITE_ROUTE + r.constants.FULL_ROUTE + '&parser=' + options.parser + '&level=' + options.level + '&out=json',
			url = 'http://validator.nu/' + parameter;

		/* request data */

		$.ajax(
		{
			url: url,
			dataType: 'json',
			success: function (data)
			{
				var messages = data.messages,
					i = 0,
					output = '';

				if (messages.length)
				{
					for (i in messages)
					{
						var that = messages[i],
							type = that.type,
							message = that.message,
							firstLine = that.firstLine,
							firstColumn = that.firstColumn,
							lastLine = that.lastLine,
							lastColumn = that.lastColumn;

						/* type fallback */

						if ($.inArray(type, ['success', 'warning', 'error']) === -1)
						{
							type = 'info';
						}

						/* collect output */

						output += '<ul class="box_note note_' + type + '">';
						output += '<li class="' + options.classString.validatorMessage + '">' + message + '</li>';

						/* lines and columns */

						if (firstLine && firstColumn || lastLine && lastColumn)
						{
							output += '<li class="' + options.classString.validatorDescription + '">' + l.validator_from + l.colon + ' ';

							/* first wording */

							if (firstLine && firstColumn)
							{
								output += l.validator_line + ' ' + firstLine + l.comma + ' ' + l.validator_column + ' ' + firstColumn;
							}

							/* to wording */

							if (firstLine && firstColumn && lastLine && lastColumn)
							{
								output += ' ' + l.validator_to + ' ';
							}

							/* last wording */

							if (lastLine && lastColumn)
							{
								output += l.validator_line + ' ' + lastLine + l.comma + ' ' + l.validator_column + ' ' + lastColumn;
							}
							output += '</li>';
						}
						output += '</ul>';
					}
				}

				/* prepend output to body */

				if (output)
				{
					$('<div class="' + options.classString.validatorBox + '">' + output + '</div>').hide().prependTo('body').fadeIn(options.duration);
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