/* gallery */

r.module.gallery =
{
	startup: true,
	selector: 'ul.js_list_gallery li a',
	options:
	{
		element:
		{
			gallery: 'div.js_gallery',
			galleryLoader: 'img.js_gallery_loader',
			galleryOverlay: 'div.js_gallery_overlay'
		},
		classString:
		{
			gallery: 'js_gallery gallery',
			galleryLoader: 'js_gallery_loader gallery_loader',
			galleryOverlay: 'js_gallery_overlay gallery_overlay'
		},
		timeout:
		{
			loader: 1000,
			image: 10000
		},
		loader: 'modules/gallery/images/loader.gif',
		scaling: 0.9
	}
};