rs.modules.Gallery =
{
	init: true,
	dependency: typeof window.PhotoSwipe === 'function' && typeof window.PhotoSwipeUI_Default === 'function',
	optionArray:
	{
		selector: 'ul.rs-js-gallery',
		template: 'div.pswp',
		ui: window.PhotoSwipeUI_Default,
		photoswipe:
		{
			index: 0,
			bgOpacity: 0.8
		}
	}
};
