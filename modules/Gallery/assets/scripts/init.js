/**
 * @tableofcontents
 *
 * 1. gallery
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. gallery */

rs.modules.gallery =
{
	init: true,
	selector: 'ul.rs-js-list-gallery a',
	options:
	{
		className:
		{
			gallery: 'rs-js-gallery rs-gallery',
			galleryLoader: 'rs-js-gallery-loader rs-gallery-loader',
			galleryMedia: 'rs-js-gallery-media rs-gallery-media',
			galleryMeta: 'rs-js-gallery-meta rs-gallery-meta',
			galleryPagination: 'rs-js-gallery-pagination rs-gallery-pagination',
			galleryArtist: 'rs-js-gallery-artist rs-gallery-artist',
			galleryDate: 'rs-js-gallery-date rs-gallery-date',
			galleryDescription: 'rs-js-gallery-description rs-gallery-description',
			galleryOverlay: 'rs-js-gallery-overlay rs-gallery-overlay',
			controlPrevious: 'rs-js-gallery-previous rs-gallery-control rs-gallery-control-previous',
			controlNext: 'rs-js-gallery-next rs-gallery-control rs-gallery-control-next'
		},
		scaling: 0.92,
		timeout: 10000,
		loader: true,
		preload: true
	}
};

/* mobile */

if (rs.registry.myMobile)
{
	rs.modules.gallery.options.scaling = 0.98;
	rs.modules.gallery.options.loader = false;
	rs.modules.gallery.options.preload = false;
}