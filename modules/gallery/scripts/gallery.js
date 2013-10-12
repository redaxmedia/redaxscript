/**
 * @tableofcontents
 *
 * 1. gallery
 *    1.1 preload
 *    1.2 open
 *    1.3 loader
 *    1.4 listen
 *    1.5 previous
 *    1.6 next
 *    1.7 close
 *    1.8 init
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

	/* @section 1. gallery */

	$.fn.gallery = function (options)
	{
		/* extend options */

		if (r.modules.gallery.options !== options)
		{
			options = $.extend({}, r.modules.gallery.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var gallery = {};

			gallery.list = $(this);
			gallery.links = gallery.list.find('a');

			/* @section 1.1 preload */

			gallery.preload = function ()
			{
				gallery.links.each(function ()
				{
					var link = $(this).addClass(options.classString.galleryPreload),
						url = link.attr('href'),
						image = $('<img src="' + url + '" />');

					/* opera load fix */

					if (r.constants.MY_BROWSER === 'opera')
					{
						image.appendTo('body').remove();
					}

					/* image loaded */

					image.on('load', function ()
					{
						link.removeClass(options.classString.galleryPreload);
					});
				});
			};

			/* @section 1.2 open */

			gallery.open = function (link)
			{
				var data =
					{
						counter: link.data('counter'),
						total: link.data('total'),
						galleryName: link.data('gallery-name')
					},
					meta =
					{
						artist: link.data('data-artist') || '',
						date: link.data('date') || '',
						description: link.data('description') || ''
					},
					url = link.attr('href'),
					image = $('<img src="' + url + '" />');

				/* listen for load */

				image.on('load', function ()
				{
					gallery.container.html(image);

					/* append to body */

					gallery.container.add(gallery.overlay).appendTo('body');
					r.flags.modal = true;
				})

				/* stop propagation */

				.on('click', function (event)
				{
					event.stopPropagation();
				});
			};

			/* @section 1.3 loader */

			gallery.loader = function ()
			{

			};

			/* @section 1.4 listen */

			gallery.listen = function ()
			{
				/* listen for click */

				gallery.links.on('click', function (event)
				{
					var link = $(this);

					gallery.open(link);
					event.preventDefault();
				});

				/* close dialog */

				gallery.overlay.add(gallery.container).on('click', function ()
				{
					gallery.close();
				});

				/* listen for keydown */

				$(window).on('keydown', function (event)
				{
					/* trigger close action */

					if (event.which === 27)
					{
						gallery.close();
					}

					/* trigger previous action */

					if (event.which === 37)
					{
						gallery.previous();
					}

					/* trigger next action */

					if (event.which === 39)
					{
						gallery.next();
					}

					/* disable up and down */

					if (event.which === 38 || event.which === 40)
					{
						event.preventDefault();
					}
				});
			};

			/* @section 1.5 previous */

			gallery.previous = function ()
			{

			};

			/* @section 1.6 next */

			gallery.next = function ()
			{

			};

			/* @section 1.7 close */

			gallery.close = function ()
			{
				gallery.container.add(gallery.overlay).detach();
				r.flags.modal = false;
			};

			/* @section 1.8 init */

			gallery.init = function ()
			{
				/* create gallery elements */

				gallery.overlay = $('<div>').addClass(options.classString.galleryOverlay);
				gallery.container = $('<div>').addClass(options.classString.gallery);

				/* preload */

				if (options.preload)
				{
					gallery.preload();
				}

				/* listen */

				gallery.listen();
			};

			/* init as needed */

			if (r.constants.MY_BROWSER === 'msie' && r.constants.MY_BROWSER_VERSION < 7 || r.flags.modal === true)
			{
				return false;
			}
			else if (gallery.list.length)
			{
				gallery.init();
			}
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.gallery.startup)
		{
			$(r.modules.gallery.selector).gallery(r.modules.gallery.options);
		}
	});
})(window.jQuery || window.Zepto);