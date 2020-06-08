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
		galleryList.forEach(galleryElement =>
		{
			galleryElement.querySelectorAll('a').forEach(linkElement =>
			{
				/* collect images */

				imageArray.push(
				{
					src: linkElement.href,
					h: Number(linkElement.dataset.height),
					w: Number(linkElement.dataset.width)
				});

				/* listen for click */

				linkElement.addEventListener('click', event =>
				{
					const photoSwipe = new window.PhotoSwipe(galleryTemplate, OPTION.ui, imageArray,
					[
						...OPTION.photoswipe,
						...
						{
							index: Number(linkElement.dataset.index)
						}
					]);

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
	rs.modules.Gallery.process();
}
