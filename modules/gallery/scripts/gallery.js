(function ($)
{
	/* gallery */

	$.fn.gallery = function ()
	{
		/* prematurely terminate gallery */

		if (r.constant.MY_BROWSER === 'msie' && r.constant.MY_BROWSER_VERSION < 7)
		{
			return false;
		}

		/* setup counter fields */

		$(this).each(function ()
		{
			var counter = 0,
				gallery = $(this),
				galleryName = gallery.attr('id');

			gallery.find('a').each(function ()
			{
				$(this).data('counter', ++counter).addClass('js_' + galleryName + '_' + counter);
			});
			gallery.data('total', counter);
		});

		/* open gallery box */

		$(this).find('a').click(function ()
		{
			/* define variables */

			var link = $(this),
				linkString = link.attr('href'),
				linkAlt = link.attr('title'),
				linkCounter = link.data('counter'),
				gallery = link.closest('ul.js_gallery'),
				galleryName = gallery.attr('id'),
				galleryTotal = gallery.data('total'),
				galleryOverlay = $('div.js_gallery_overlay'),
				checkGalleryOverlay = galleryOverlay.length,
				checkGalleryLoading, galleryBox, galleryLoading;

			/* build box elements */

			if (checkGalleryOverlay === 0)
			{
				$('body').append('<div class="js_gallery_overlay gallery_overlay"></div>');
				galleryOverlay = $('div.js_gallery_overlay').css('opacity', 0).fadeTo(r.lightbox.overlay.duration, r.lightbox.overlay.opacity);
			}
			$('body').append('<img class="js_gallery_loading gallery_loading" src="modules/gallery/images/loading.gif" alt="loading" /><div class="js_gallery_box gallery_box"><img src="' + linkString + '" alt="' + linkAlt + '" /></div>');
			galleryBox = $('div.js_gallery_box').css('opacity', 0);
			galleryLoading = $('img.js_gallery_loading').css('opacity', 0).delay(500).fadeTo(r.lightbox.loading.duration, r.lightbox.loading.opacity);
			
			/* build previous and next links */

			if (linkCounter > 1)
			{
				galleryBox.append('<div class="js_gallery_previous gallery_previous"><div>' + l.gallery_image_previous + '</div></div>');
			}
			if (linkCounter < galleryTotal)
			{
				galleryBox.append('<div class="js_gallery_next gallery_next"><div>' + l.gallery_image_next + '</div></div>');
			}

			/* fix transparent next and previous area for msie */

			if (r.constant.MY_BROWSER === 'msie')
			{
				$('div.js_gallery_next, div.js_gallery_previous').css('background-image', 'url(\'fix\')');
			}

			/* port data to image */

			galleryBox.find('img').data(
			{
				'gallery': galleryName,
				'counter': linkCounter,
				'total': galleryTotal
			});

			/* align box once image is loaded */

			galleryBox.find('img').load(function ()
			{
				var image = $(this),
					windowHeight = $(window).height(),
					windowWidth = $(window).width(),
					imageHeight = image.height(),
					imageWidth = image.width();

				galleryLoading.remove();

				/* calculate image dimensions */

				if (imageHeight > windowHeight)
				{
					imageWidth = imageWidth * windowHeight * 0.9 / imageHeight;
					imageHeight = windowHeight * 0.9;
				}
				if (imageWidth > windowWidth)
				{
					imageHeight = imageHeight * windowWidth * 0.9 / imageWidth;
					imageWidth = windowWidth * 0.9;
				}

				/* fit image to screen */

				image.css(
				{
					'height': imageHeight,
					'width': imageWidth
				});
				galleryBox.css(
				{
					'height': imageHeight,
					'width': imageWidth,
					'margin-top': -(imageHeight / 2) - 1,
					'margin-left': -(imageWidth / 2) - 1
				}).fadeTo(r.lightbox.body.duration, r.lightbox.body.opacity);

				/* handle next and previous links */

				galleryBox.find('div.js_gallery_next, div.js_gallery_previous').click(function ()
				{
					var link = $(this),
						image = galleryBox.find('img'),
						imageGallery = image.data('gallery'),
						imageCounter = image.data('counter'),
						imageTotal = image.data('total'),
						checkGalleryNext = link.hasClass('js_gallery_next'),
						checkGalleryPrevious = link.hasClass('js_gallery_previous');

					if (checkGalleryNext)
					{
						imageCounter++;
					}
					else if (checkGalleryPrevious)
					{
						imageCounter--;
					}
					if (imageCounter > 1 || imageCounter < imageTotal)
					{
						galleryBox.remove();
						$('a.js_' + imageGallery + '_' + imageCounter).click();
					}
					return false;
				});

				/* close gallery box */

				$(galleryBox).add(galleryOverlay).click(function ()
				{
					galleryLoading = $('img.js_gallery_loading');
					checkGalleryLoading = galleryLoading.length;
					if (checkGalleryLoading === 0)
					{
						$(galleryBox).add(galleryOverlay).remove();
					}
				});
				
			});
			return false;
		});
	};
})(jQuery);

$(function ()
{
	/* startup */

	$('ul.js_gallery').gallery();
});