rs.modules.Gallery.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.Gallery.config,
		...config
	};
	const galleryList = document.querySelectorAll(CONFIG.selector);
	const imageArray = [];

	if (galleryList)
	{
		galleryList.forEach(gallery =>
		{
			gallery.querySelectorAll('a').forEach(link =>
			{
				imageArray.push(
				{
					src: link.href,
					h: Number(link.dataset.height),
					w: Number(link.dataset.width)
				});

				/* listen for click */

				link.addEventListener('click', event =>
				{
					/* override config */

					CONFIG.photoswipe.index = Number(event.currentTarget.dataset.index);

					/* handle photo */

					const template = document.querySelector(CONFIG.template);
					const photoSwipe = new window.PhotoSwipe(template, window.PhotoSwipeUI_Default, imageArray, CONFIG.photoswipe);

					photoSwipe.init();
					event.preventDefault();
				})
			});
		});
	}
};

/* run as needed */

if (rs.modules.Gallery.init && rs.modules.Gallery.dependency)
{
	rs.modules.Gallery.execute(rs.modules.Gallery.config);
}
