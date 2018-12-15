rs.modules.Gallery.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Gallery.optionArray,
		...optionArray
	};
	const galleryList = document.querySelectorAll(OPTION.selector);
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

					OPTION.photoswipe.index = Number(event.currentTarget.dataset.index);

					/* handle photo */

					const template = document.querySelector(OPTION.template);
					const photoSwipe = new window.PhotoSwipe(template, window.PhotoSwipeUI_Default, imageArray, OPTION.photoswipe);

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
	rs.modules.Gallery.process(rs.modules.Gallery.optionArray);
}
