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
	selector: 'ul.js_list_gallery',
	options:
	{
		classString:
		{
			gallery: 'js_gallery gallery',
			galleryLoader: 'js_gallery_loader gallery_loader',
			galleryMeta: 'js_gallery_meta gallery_meta',
			galleryPagination: 'js_gallery_pagination gallery_pagination',
			galleryArtist: 'js_gallery_artist gallery_artist',
			galleryDescription: 'js_gallery_description gallery_description',
			galleryOverlay: 'js_gallery_overlay gallery_overlay',
			controlPrevious: 'js_gallery_previous gallery_control gallery_control_previous',
			controlNext: 'js_gallery_next gallery_control gallery_control_next'
		},
		timeout: 10000,
		loader: true,
		preload: true
	}
};

/* disable preload */

if (r.constants.MY_MOBILE)
{
	r.modules.gallery.options.preload = false;
}