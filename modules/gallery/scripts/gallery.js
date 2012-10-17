(function ($)
{
	'use strict';

	/* gallery */

	$.fn.gallery = function (options)
	{
		/* extend options */

		if (r.module.gallery.options !== options)
		{
			options = $.extend({}, r.module.gallery.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var win = $(window),
				body = $('body'),
				gallery = body.find(options.element.gallery),
				galleryOverlay = body.find(options.element.galleryOverlay);

			/* prematurely terminate gallery */

			if (r.constant.MY_BROWSER === 'msie' && r.constant.MY_BROWSER_VERSION < 7)
			{
				return false;
			}

			/* preload images */

			if (options.preload.startup)
			{
				$(this).each(function ()
				{
					var link = $(this),
						string = link.attr('href'),
						thumb = link.children(),
						related = thumb.attr('src'),
						image = $('<img src="' + string + '" />');

					/* setup opacity and add class */

					thumb.css('opacity', options.preload.opacity).addClass('image_gallery_preload');

					/* opera load fix */

					if (r.constant.MY_BROWSER === 'opera')
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

			/* open gallery on click */

			$(this).click(function (event)
			{
				var link = $(this),
					string = link.attr('href'),
					thumb = link.children(),
					image = $('<img src="' + string + '" />'),
					imageCounter = thumb.data('counter'),
					imageTotal = thumb.data('total'),
					imageArtist = thumb.data('artist'),
					imageDate = thumb.data('date'),
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
					timeoutLoader, timeoutImage, intervalVisible, output = '';

				/* prematurely terminate gallery */

				if (checkGallery)
				{
					return false;
				}

				/* collect overlay */

				if (checkGalleryOverlay === 0)
				{
					output = '<div class="' + options.classString.galleryOverlay + '"></div>';
				}

				/* collect gallery elements */

				output += '<div class="' + options.classString.gallery + '">';
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
					galleryOverlay.css('opacity', 0).fadeTo(r.lightbox.overlay.duration, r.lightbox.overlay.opacity);
				}
				gallery = body.find(options.element.gallery).css('opacity', 0).fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);
				galleryLoader = gallery.find(options.element.galleryLoader).css('opacity', 0);

				/* fade in loader on timeout */

				timeoutLoader = setTimeout(function ()
				{
					galleryLoader.fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);
				}, options.timeout.loader);

				/* close gallery on timout */

				timeoutImage = setTimeout(function ()
				{
					galleryOverlay.click();
				}, options.timeout.image);

				/* opera load fix */

				if (r.constant.MY_BROWSER === 'opera')
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
					image.appendTo(gallery).trigger('fit');

					/* check visible interval */

					intervalVisible = setInterval(function ()
					{
						if (image.is(':visible'))
						{
							gallery.fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);
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

					/* next and previous on click */

					buttonPrevious.add(buttonNext).click(function (event)
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
						win.on('resize', function () {
							image.trigger('fit');
						});
					}
				});

				/* fit image to viewport */

				image.on('fit', function ()
				{
					var image = $(this),
						imageHeight = image.height(),
						imageWidth = image.width(),
						originalHeight = image.data('height'),
						originalWidth = image.data('width'),
						winHeight = win.height(),
						winWidth = win.width(),
						minWidth = options.minWidth,
						scaling = options.scaling;

					/* store image dimensions */

					if (typeof originalHeight === 'undefined')
					{
						image.data('height', imageHeight);
						originalHeight = imageHeight;
					}
					if (typeof originalWidth === 'undefined')
					{
						image.data('width', imageWidth);
						originalWidth = imageWidth;
					}

					/* restore image dimensions */

					imageHeight = originalHeight;
					imageWidth = originalWidth;

					/* calculate image dimensions */

					if (imageHeight > winHeight)
					{
						imageWidth = imageWidth * winHeight * scaling / imageHeight;
						imageHeight = winHeight * scaling;
					}
					if (imageWidth > winWidth)
					{
						imageHeight = imageHeight * winWidth * scaling / imageWidth;
						imageWidth = winWidth * scaling;
					}
					if (imageWidth < minWidth)
					{
						imageHeight = imageHeight * minWidth * scaling / imageWidth;
						imageWidth = minWidth * scaling;
					}

					/* setup height and width */

					image.add(gallery).css(
					{
						'height': imageHeight,
						'width': imageWidth
					});

					/* setup gallery margin */

					gallery.css(
					{
						'margin-top': -imageHeight / 2,
						'margin-left': -imageWidth / 2
					});
				});

				/* remove gallery and overlay on click */

				galleryOverlay.click(function ()
				{
					gallery.add(galleryOverlay).remove();
					win.off();
				});
				event.preventDefault();
			});
		});
	};

	/* startup */

	$(function ()
	{
		if (r.module.gallery.startup)
		{
			$(r.module.gallery.selector).gallery(r.module.gallery.options);
		}
	});
})(jQuery);