/**
 * @tableofcontents
 *
 * 1. lazy load
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. lazy load */

r.modules.lazyLoad =
{
	startup: true,
	selector: 'img.js_lazy_load:visible',
	options:
	{
		classString:
		{
			lazyLoadLoader: 'lazy_load_loader'
		},
		threshold: 200,
		interval: 200
	}
};