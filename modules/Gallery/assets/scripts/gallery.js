rs.modules.Gallery.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Gallery.optionArray,
		...optionArray
	};
	const galleryList = document.querySelectorAll(OPTION.selector);
	const galleryTemplate = document.querySelector(optionArray.template);
	const imageArray = [];

	if (galleryList)
	{
		galleryList.forEach(gallery =>
		{
			gallery.querySelectorAll('a').forEach(link =>
			{
				/* collect images */

				imageArray.push(
				{
					src: link.href,
					h: Number(link.dataset.height),
					w: Number(link.dataset.width)
				});

				/* listen for click */

				link.addEventListener('click', event =>
				{
					OPTION.photoswipe.index = Number(event.currentTarget.dataset.index);
					const photoSwipe = new window.PhotoSwipe(galleryTemplate, OPTION.ui, imageArray, OPTION.photoswipe);

					photoSwipe.init();
					event.preventDefault();
				});
			});
		});
	}
};

/* run as needed */

if (rs.modules.Gallery.init && rs.modules.Gallery.dependency)
{
	rs.modules.Gallery.process(rs.modules.Gallery.optionArray);
}
