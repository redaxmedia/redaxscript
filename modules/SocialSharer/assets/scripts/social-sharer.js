/**
 * @tableofcontents
 *
 * 1. social sharer
 *    1.1 fetch data
 *    1.2 process data
 *    1.3 listen
 *    1.4 init
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. social sharer */

	$.fn.socialSharer = function (options)
	{
		/* extend options */

		if (rs.modules.socialSharer.options !== options)
		{
			options = $.extend({}, rs.modules.socialSharer.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var socialSharer =
				{
					links: $(this).find('a')
				};

			/* @section 1.1 fetch data */

			socialSharer.fetchData = function ()
			{
				$.ajax(
				{
					url: options.url,
					data:
					{
						url: rs.baseURL,
						apikey: options.key
					},
					dataType: 'json',
					success: function (data)
					{
						if (typeof data === 'object')
						{
							socialSharer.processData(data);
						}
					}
				});
			};

			/* @section 1.2 process data */

			socialSharer.processData = function (data)
			{
				for (var i in data)
				{
					var counter = data[i],
						type = i.toLowerCase();

					if (typeof counter.total_count === 'number')
					{
						counter = counter.total_count;
					}

					/* filter by type */

					if (typeof counter === 'number')
					{
						socialSharer.links.filter('[data-type="' + type + '"]').attr('data-counter', counter);
					}
				}
			};

			/* @section 1.3 listen */

			socialSharer.listen = function ()
			{
				socialSharer.links.on('click', function (event)
				{
					var link = $(this),
						url = link.attr('href'),
						height = link.data('height') || options.popup.height,
						width = link.data('width') || options.popup.width;

					if (typeof url === 'string')
					{
						window.open(url, options.popup.name, 'height=' + height + ', width=' + width + ', menubar=' + options.popup.menubar + ', resizable=' + options.popup.resizable + ', status=' + options.popup.status + ', scrollbars=' + options.popup.scrollbars + ', toolbar=' + options.popup.toolbar);
						event.preventDefault();
					}
				});
			};

			/* @section 1.4 init */

			socialSharer.init = function ()
			{
				socialSharer.fetchData();
				socialSharer.listen();
			};

			/* init as needed */

			if (socialSharer.links.length)
			{
				socialSharer.init();
			}
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.socialSharer.init)
		{
			$(rs.modules.socialSharer.selector).socialSharer(rs.modules.socialSharer.options);
		}
	});
})(window.jQuery);