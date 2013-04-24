/**
 * @tableofcontents
 *
 * 1. validator
 * 2. startup
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

		l.validator_from = 'From';
		l.validator_line = 'Line';
		l.validator_column = 'Column';

		var route = r.baseURL + r.constants.FULL_ROUTE,
			url = 'http://validator.nu/?doc=' + route + '&level=' + options.level + '&out=json';

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
							lastLine = that.lastLine,
							lastColumn = that.lastColumn;

						output += '<ul class="box_validator box_note note_' + type + '">';
						output += '<li class="message_validator">' + message + '</li>';

						/* last line and column */

						if (lastLine && lastColumn)
						{
							output += '<li class="description_validator">' + l.validator_from + l.colon + ' ';
							output += l.validator_line + ' ' + lastLine + ', ' + l.validator_column + ' ' + lastColumn;
							output += '</li>';
						}
						output += '</ul>';
					}
				}

				/* prepend to body */

				$(output).hide().prependTo('body').slideDown();
			}
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		$.fn.validator(r.modules.validator.options);
	});
})(jQuery);