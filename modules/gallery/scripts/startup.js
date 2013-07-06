/**
 * @tableofcontents
 *
 * 1. gallery
 *
 * @since 2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. gallery */

r.modules.gallery =
{
	startup: true,
	selector: 'ul.js_list_gallery li a',
	options:
	{
		element:
		{
			gallery: 'div.js_gallery',
			galleryLoader: 'img.js_gallery_loader',
			galleryMeta: 'div.js_gallery_meta',
			galleryPagination: 'span.js_gallery_pagination',
			galleryDivider: 'span.js_gallery_divider',
			galleryArtist: 'span.js_gallery_artist',
			galleryDescription: 'span.js_gallery_description',
			galleryLabel: 'span.js_gallery_label',
			galleryOverlay: 'div.js_gallery_overlay',
			buttonPrevious: 'a.js_gallery_previous',
			buttonNext: 'a.js_gallery_next'
		},
		classString:
		{
			gallery: 'js_gallery gallery',
			galleryReady: 'js_gallery_ready gallery_ready',
			galleryLoader: 'js_gallery_loader gallery_loader',
			galleryMeta: 'js_gallery_meta gallery_meta',
			galleryPagination: 'js_gallery_pagination gallery_pagination',
			galleryDivider: 'js_gallery_divider gallery_divider',
			galleryArtist: 'js_gallery_artist gallery_artist',
			galleryDescription: 'js_gallery_description gallery_description',
			galleryLabel: 'js_gallery_label gallery_label',
			galleryOverlay: 'js_gallery_overlay gallery_overlay',
			buttonPrevious: 'js_gallery_previous gallery_previous',
			buttonNext: 'js_gallery_next gallery_next'
		},
		timeout:
		{
			loader: 1000,
			image: 10000
		},
		interval: 500,
		loader: 'modules/gallery/images/loader.gif',
		scaling: 0.9,
		minWidth: 300,
		autoResize: true,
		preload:
		{
			startup: true,
			opacity: 0.6,
			duration: 'slow'
		}
	}
};

/* disable preload */

if (r.constants.MY_MOBILE)
{
	r.modules.gallery.options.scaling = 0.8;
	r.modules.gallery.options.preload.startup = false;
}