/**
 * @tableofcontents
 *
 * 1. lazy load
 *    1.1 show
 *    1.2 listen
 *    1.3 init
 * 2. startup
 *
 * @since 2.0.1
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. lazy load */

	$.fn.lazyLoad = function (options)
	{
		/* extend options */

		if (rs.modules.lazyLoad.options !== options)
		{
			options = $.extend({}, rs.modules.lazyLoad.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var lazyLoad = {};

			lazyLoad.images = $(this);

			/* @section 1.1 show */

			lazyLoad.show = function ()
			{
				var win = $(window),
					winHeight = win.height(),
					winTop = win.scrollTop(),
					winBottom = winTop + winHeight;

				/* handle images */

				lazyLoad.images.each(function ()
				{
					var image = $(this),
						imageHeight = image.height(),
						imageTop = image.offset().top,
						imageBottom = imageTop + imageHeight,
						imageRoute = image.data('src');

					/* show images in view */

					if (imageTop >= winTop && imageBottom - options.threshold <= winBottom)
					{
						image.attr('src', imageRoute);

						/* clear move interval */

						clearInterval(lazyLoad.intervalMove);
					}
				});
			};

			/* @section 1.2 listen */

			lazyLoad.listen = function ()
			{
				$(window).on('resize scroll', function ()
				{
					lazyLoad.move = true;
				});

				/* interval enhanced move */

				lazyLoad.intervalMove = setInterval(function ()
				{
					if (lazyLoad.move)
					{
						lazyLoad.show();
						lazyLoad.move = false;
					}
				}, options.interval);
			};

			/* @section 1.3 init */

			lazyLoad.init = function ()
			{
				lazyLoad.show();
				lazyLoad.listen();
			};

			/* init as needed */

			if (lazyLoad.images.length)
			{
				lazyLoad.init();
			}
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (rs.modules.lazyLoad.startup)
		{
			$(rs.modules.lazyLoad.selector).lazyLoad(rs.modules.lazyLoad.options);
		}
	});
})(window.jQuery || window.Zepto);