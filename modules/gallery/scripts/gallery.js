/**
 * @tableofcontents
 *
 * 1. gallery
 *    1.1 preload images
 *    1.2 open gallery
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
			var win = $(window),
				body = $('body'),
				gallery = body.find(options.element.gallery),
				galleryOverlay = body.find(options.element.galleryOverlay);

			/* prematurely terminate gallery */

			if (r.constants.MY_BROWSER === 'msie' && r.constants.MY_BROWSER_VERSION < 7 || gallery.length || galleryOverlay.length)
			{
				return false;
			}

			/* @section 1.1 preload images */

			if (options.preload.startup)
			{
				$(this).each(function ()
				{
					var link = $(this),
						url = link.attr('href'),
						thumb = link.children(),
						related = thumb.attr('src'),
						image = $('<img src="' + url + '" />');

					/* setup opacity and add class */

					thumb.fadeTo(options.preload.duration, options.preload.opacity).addClass('image_gallery_preload');

					/* opera load fix */

					if (r.constants.MY_BROWSER === 'opera')
					{
						image.appendTo(body).remove();
					}

					/* full image loaded */

					image.data('related', related).on('load', function ()
					{
						var thumbRelated = $(this).data('related');

						/* fade in related thumb and remove class */

						link.find('img[src="' + thumbRelated + '"]').fadeTo(options.preload.duration, 1).removeClass('image_gallery_preload');
					});
				});
			}

			/* @section 1.2 open gallery */

			$(this).on('click', function (event)
			{
				var link = $(this),
					url = link.attr('href'),
					thumb = link.children(),
					image = $('<img src="' + url + '" />'),
					imageCounter = thumb.data('counter'),
					imageTotal = thumb.data('total'),
					imageArtist = thumb.data('artist'),
					imageDescription = thumb.data('description'),
					gallery = body.find(options.element.gallery),
					galleryLoader = $('<img src="' + options.loader + '" />'),
					galleryMeta = gallery.find(options.element.galleryMeta),
					galleryOverlay = body.find(options.element.galleryOverlay),
					galleryName = thumb.data('gallery-name'),
					buttonPrevious = gallery.find(options.element.buttonPrevious),
					buttonNext = gallery.find(options.element.buttonNext),
					checkGallery = gallery.length,
					checkGalleryOverlay = galleryOverlay.length,
					timeoutLoader = '',
					timeoutImage = '',
					intervalVisible = '',
					output = '';

				/* prematurely terminate gallery */

				if (checkGallery)
				{
					return false;
				}

				/* collect overlay */

				if (checkGalleryOverlay === 0)
				{
					output = '<div class="' + options.classString.galleryOverlay + ' ' + galleryName + '"></div>';
				}

				/* collect gallery elements */

				output += '<div class="' + options.classString.gallery + ' ' + galleryName + '">';
				if (options.loader)
				{
					output += '<img class="' + options.classString.galleryLoader + '" src="' + options.loader + '" />';
				}
				output += '</div>';

				/* append output to body */

				body.append(output);

				/* fade in overlay and loader */

				galleryOverlay = body.find(options.element.galleryOverlay);
				if (checkGalleryOverlay === 0)
				{
					galleryOverlay.css('opacity', 0).fadeTo(r.options.overlay.duration, r.options.overlay.opacity);
				}
				gallery = body.find(options.element.gallery).css('opacity', 0).fadeTo(r.options.body.duration, r.options.body.opacity);
				galleryLoader = gallery.find(options.element.galleryLoader).css('opacity', 0);

				/* fade in loader on timeout */

				timeoutLoader = setTimeout(function ()
				{
					galleryLoader.fadeTo(r.options.body.duration, r.options.body.opacity);
				}, options.timeout.loader);

				/* close gallery on timeout */

				timeoutImage = setTimeout(function ()
				{
					galleryOverlay.click();
				}, options.timeout.image);

				/* opera load fix */

				if (r.constants.MY_BROWSER === 'opera')
				{
					image.appendTo(body).remove();
				}

				/* full image loaded */

				image.on('load', function ()
				{
					/* clear loader and image timeout */

					clearTimeout(timeoutLoader);
					clearTimeout(timeoutImage);

					/* append image and remove loader */

					galleryLoader.remove();
					gallery.css('opacity', 0);
					image.appendTo(gallery);

					/* check visible interval */

					intervalVisible = setInterval(function ()
					{
						if (image.is(':visible'))
						{
							gallery.addClass(options.classString.galleryReady).fadeTo(r.options.body.duration, r.options.body.opacity);
							clearInterval(intervalVisible);
						}
					}, options.interval);

					/* append meta information */

					galleryMeta = $('<div class="' + options.classString.galleryMeta + '"><span class="' + options.classString.galleryPagination + '">' + imageCounter + '<span class="' + options.classString.galleryDivider + '">' + l.gallery_divider + '</span>' + imageTotal + '</span></div>').appendTo(gallery);

					/* append image artist */

					if (imageArtist)
					{
						galleryMeta.append('<span class="' + options.classString.galleryArtist + '"><span class="' + options.classString.galleryLabel + '">' + l.gallery_image_artist + l.colon + '</span>' + imageArtist + '</span>');
					}

					/* append image description */

					if (imageDescription)
					{
						galleryMeta.prepend('<span class="' + options.classString.galleryDescription + '"><span class="' + options.classString.galleryLabel + '">' + l.gallery_image_description + l.colon + '</span>' + imageDescription + '</span>');
					}

					/* append previous and next */

					if (imageCounter > 1)
					{
						buttonPrevious = $('<a class="' + options.classString.buttonPrevious + '"><span>' + l.gallery_image_previous + '</span></a>').appendTo(gallery);
					}
					if (imageCounter < imageTotal)
					{
						buttonNext = $('<a class="' + options.classString.buttonNext + '"><span>' + l.gallery_image_next + '</span></a>').appendTo(gallery);
					}

					/* fit image after render */

					image.trigger('fit');

					/* next and previous */

					buttonPrevious.add(buttonNext).on('click', function (event)
					{
						var link = $(this),
							checkButtonPrevious = link.hasClass('js_gallery_previous'),
							checkButtonNext = link.hasClass('js_gallery_next');

						/* calculate image counter */

						if (checkButtonPrevious)
						{
							imageCounter--;
						}
						else if (checkButtonNext)
						{
							imageCounter++;
						}
						if (imageCounter > 1 || imageCounter < imageTotal)
						{
							gallery.remove();
							win.off();
							$('#' + galleryName + ' img[data-counter="' + imageCounter + '"]').parent().click();
						}
						event.preventDefault();
					});

					/* listen for keydown */

					win.on('keydown', function (event)
					{
						/* trigger close action */

						if (event.which === 27)
						{
							galleryOverlay.click();
						}

						/* trigger previous action */

						if (event.which === 37)
						{
							buttonPrevious.click();
						}

						/* trigger next action */

						if (event.which === 39)
						{
							buttonNext.click();
						}

						/* disable up and down */

						if (event.which === 38 || event.which === 40)
						{
							event.preventDefault();
						}
					});

					/* auto resize */

					if (options.autoResize)
					{
						win.on('resize', function ()
						{
							image.trigger('fit');
						});
					}
				});

				/* fit image to viewport */

				image.on('fit', function ()
				{
					var image = $(this),
						imageHeight = image.data('height') || image.height(),
						imageWidth = image.data('width') || image.width(),
						winHeight = win.height(),
						winWidth = win.width(),
						minWidth = options.minWidth,
						scaling = options.scaling,
						galleryHeight = '',
						galleryWidth = '';

					/* store image dimensions */

					image.data({
						'height': imageHeight,
						'width': imageWidth
					});

					/* calculate image dimensions */

					if (imageHeight >= winHeight)
					{
						imageWidth = imageWidth * winHeight * scaling / imageHeight;
						imageHeight = winHeight * scaling;
					}
					if (imageWidth >= winWidth)
					{
						imageHeight = imageHeight * winWidth * scaling / imageWidth;
						imageWidth = winWidth * scaling;
					}
					if (imageWidth <= minWidth)
					{
						imageHeight = imageHeight * minWidth * scaling / imageWidth;
						imageWidth = minWidth * scaling;
					}

					/* setup height and width */

					image.css(
					{
						'height': imageHeight,
						'width': imageWidth
					});

					/* gallery outer dimensions */

					galleryHeight = gallery.outerHeight();
					galleryWidth = gallery.outerWidth();

					/* setup gallery margin */

					gallery.css(
					{
						'margin-top': -galleryHeight / 2,
						'margin-left': -galleryWidth / 2
					});
				});

				/* remove gallery and overlay */

				galleryOverlay.on('click', function ()
				{
					gallery.add(galleryOverlay).remove();
					win.off();
				});
				event.preventDefault();
			});
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