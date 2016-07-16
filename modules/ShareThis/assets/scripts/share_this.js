/**
 * @tableofcontents
 *
 * 1. share this
 *    1.1 fetch data
 *    1.2 process data
 *    1.3 listen
 *    1.4 init
 * 2. init
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. share this */

	$.fn.shareThis = function (options)
	{
		/* extend options */

		if (rs.modules.shareThis.options !== options)
		{
			options = $.extend({}, rs.modules.shareThis.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var shareThis =
				{
					links: $(this)
				};

			/* @section 1.1 fetch data */

			shareThis.fetchData = function ()
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
							shareThis.processData(data);
						}
					}
				});
			};

			/* @section 1.2 process data */

			shareThis.processData = function (data)
			{
				for (var i in data)
				{
					var counter = data[i],
						type = i.toLowerCase();

					/* facebook */

					if (type === 'facebook')
					{
						counter = data[i].share_count;
					}

					/* filter by type */

					shareThis.links.filter('[data-type="' + type + '"]').attr('data-counter', counter);
				}
			};

			/* @section 1.3 listen */

			shareThis.listen = function ()
			{
				shareThis.links.on('click', function (event)
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

			shareThis.init = function ()
			{
				shareThis.fetchData();
				shareThis.listen();
			};

			/* init as needed */

			if (shareThis.links.length)
			{
				shareThis.init();
			}
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.shareThis.init)
		{
			$(rs.modules.shareThis.selector).shareThis(rs.modules.shareThis.options);
		}
	});
})(window.jQuery || window.Zepto);