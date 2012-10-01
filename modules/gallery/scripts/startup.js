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
			galleryOverlay: 'div.js_gallery_overlay',
			buttonPrevious: 'a.js_gallery_previous',
			buttonNext: 'a.js_gallery_next'
		},
		classString:
		{
			gallery: 'js_gallery gallery',
			galleryLoader: 'js_gallery_loader gallery_loader',
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
		preload:
		{
			startup: true,
			opacity: 0.6,
			duration: 'slow'
		}
	}
};