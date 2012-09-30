(function ($)
{
	/* gallery */

	$.fn.gallery = function (options)
	{
		/* extend options */

		if (r.module.gallery.options !== options)
		{
			options = $.extend({}, r.module.gallery.options, options || {});
		}

		var win = $(window),
			body = $('body'),
			gallery = $(options.element.gallery),
			galleryOverlay = $(options.element.galleryOverlay);

		/* prematurely terminate gallery */

		if (r.constant.MY_BROWSER === 'msie' && r.constant.MY_BROWSER_VERSION < 7)
		{
			return false;
		}

		/* open gallery */

		$(this).click(function (event)
		{
			/* define variables */

			var link = $(this),
				string = link.attr('href'),
				thumb = link.find('img'),
				image = $('<img src="' + string + '" />'),
				imageCounter = thumb.data('counter'),
				imageTotal = thumb.data('total'),
				imageArtist = thumb.data('artist'),
				imageDate = thumb.data('date'),
				imageDescription = thumb.data('description'),
				gallery = $(options.element.gallery),
				galleryLoader = $('<img src="' + options.loader + '" />'),
				galleryOverlay = $(options.element.galleryOverlay),
				timeoutLoader, timeoutImage, output;

			/* prematurely terminate gallery */

			if (gallery.length || galleryOverlay.length)
			{
				return false;
			}

			/* collect overlay */

			output = '<div class="' + options.classString.galleryOverlay + '"></div>';

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

			galleryOverlay = $(options.element.galleryOverlay).css('opacity', 0).fadeTo(r.lightbox.overlay.duration, r.lightbox.overlay.opacity);
			gallery = $(options.element.gallery).css('opacity', 0).fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);
			galleryLoader = $(options.element.galleryLoader).css('opacity', 0);

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

			/* full image loaded */

			image.load(function ()
			{
				/* clear timeout */

				clearTimeout(timeoutLoader);
				clearTimeout(timeoutImage);

				/* append image */

				galleryLoader.css('opacity', 0);
				image.appendTo(gallery).trigger('fit');
			});

			/* fit image to viewport */

			image.on('fit', function ()
			{
				var image = $(this),
					imageHeight = image.height(),
					imageWidth = image.width(),
					winHeight = win.height(),
					winWidth = win.width();

				/* calculate image dimensions */

				if (imageHeight > winHeight)
				{
					imageWidth = imageWidth * winHeight * options.scaling / imageHeight;
					imageHeight = winHeight * options.scaling;
				}
				if (imageWidth > winWidth)
				{
					imageHeight = imageHeight * winWidth * options.scaling / imageWidth;
					imageWidth = winWidth * options.scaling;
				}

				/* setup height and width */

				image.add(gallery).css(
				{
					'height': imageHeight,
					'width': imageWidth
				});

				/* setup margin */

				gallery.css(
				{
					'margin-top': -imageHeight / 2,
					'margin-left': -imageWidth / 2
				});
			});

			/* close gallery on click */

			galleryOverlay.click(function ()
			{
				gallery.add(galleryOverlay).remove();
			});
			event.preventDefault();
		});
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.module.gallery.startup)
	{
		$(r.module.gallery.selector).gallery(r.module.gallery.options);
	}
});