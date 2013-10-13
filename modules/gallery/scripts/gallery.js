/**
 * @tableofcontents
 *
 * 1. gallery
 *    1.1 preload
 *    1.2 open
 *    1.3 create control
 *    1.4 create meta
 *    1.5 listen
 *    1.6 action
 *    1.6.1 previous
 *    1.6.2 next
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
					var link = $(this).addClass(options.classString.galleryLoader),
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
						link.removeClass(options.classString.galleryLoader);
					});
				});
			};

			/* @section 1.2 open */

			gallery.open = function (link)
			{
				var url = link.attr('href'),
					image = $('<img src="' + url + '" />'),
					timeoutLoad = '';

				/* data */

				gallery.data =
				{
					counter: link.data('counter'),
					total: link.data('total'),
					id: link.data('id'),
					artist: link.data('data-artist') || '',
					date: link.data('date') || '',
					description: link.data('description') || ''
				};

				/* add loader */

				gallery.container.addClass(options.classString.galleryLoader);

				/* append to body */

				gallery.container.add(gallery.overlay).appendTo('body');
				r.flags.modal = true;

				/* listen for load */

				image.on('load', function ()
				{
					clearTimeout(timeoutLoad);
					gallery.container.removeClass(options.classString.galleryLoader).html(image);

					/* create control and meta */

					gallery.createControl();
					gallery.createMeta();
				})

				/* stop propagation */

				.on('click', function (event)
				{
					event.stopPropagation();
				});

				/* close after timeout */

				timeoutLoad = setTimeout(function ()
				{
					gallery.close();
				}, options.timeout);
			};

			/* @section 1.3 create control */

			gallery.createControl = function ()
			{
				if (gallery.data.total > 1)
				{
					/* previous control */

					if (gallery.data.counter > 1)
					{
						gallery.buttonPrevious = $('<a>' + l.gallery_image_previous + '</a>').addClass(options.classString.controlPrevious)

						/* listen for click */

						.on('click', function (event)
						{
							gallery.previous();
							event.stopPropagation();
						}).appendTo(gallery.container);
					}

					/* next control */

					if (gallery.data.counter < gallery.data.total)
					{
						gallery.buttonNext = $('<a>' + l.gallery_image_next + '</a>').addClass(options.classString.controlNext)

						/* listen for click */

						.on('click', function (event)
						{
							gallery.next();
							event.stopPropagation();
						}).appendTo(gallery.container);
					}
				}
			};

			/* @section 1.4 create meta */

			gallery.createMeta = function ()
			{
				gallery.meta = $('<div>').addClass(options.classString.galleryMeta);

				/* artist */

				if (gallery.data.artist)
				{
					gallery.artist = $('<div data-label="' + l.gallery_image_artist + '">' + gallery.data.artist + '</div>').addClass(options.classString.galleryArtist).appendTo(gallery.meta);
				}

				/* description */

				if (gallery.data.description)
				{
					gallery.description = $('<div data-label="' + l.gallery_image_description + '">' + gallery.data.description + '</div>').addClass(options.classString.galleryDescription).appendTo(gallery.meta);
				}

				/* pagination */

				if (gallery.data.total > 1)
				{
					gallery.pagination = $('<div>' + gallery.data.counter + l.gallery_divider + gallery.data.total + '</div>').addClass(options.classString.galleryPagination).appendTo(gallery.meta);
				}

				/* append meta */

				gallery.meta.appendTo(gallery.container);
			};

			/* @section 1.5 listen */

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

			/* @section 1.6 action */

			gallery.action = function (mode)
			{
				var related = $('#' + gallery.data.id),
					counter = gallery.data.counter,
					link = '';

				if (mode === 'previous')
				{
					counter--;
				}
				else if (mode === 'next')
				{
					counter++;
				}

				/* close and open gallery */

				if (counter > 1 || counter < gallery.data.total)
				{
					link = related.find('a[data-counter="' + counter + '"]');

					if (link.length)
					{
						gallery.close();
						gallery.open(link);
					}
				}
			};

			/* @section 1.6.1 previous */

			gallery.previous = function ()
			{
				gallery.action('previous');
			};

			/* @section 1.6.2 next */

			gallery.next = function ()
			{
				gallery.action('next');
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