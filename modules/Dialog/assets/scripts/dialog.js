rs.modules.Dialog.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Dialog.optionArray,
		...optionArray
	};
	const linkList = document.querySelectorAll(OPTION.selector);

	if (linkList)
	{
		linkList.forEach(linkElement =>
		{
			linkElement.addEventListener('click', event =>
			{
				rs.modules.Dialog.open(OPTION.confirmRoute)
					.then(html => rs.modules.Dialog.create(html)
						.then(dialogElement =>
						{
							dialogElement.querySelector(OPTION.element.buttonOk).addEventListener('click', () => window.location = linkElement.href);
							dialogElement.querySelector(OPTION.element.buttonCancel).addEventListener('click', () => rs.modules.Dialog.destroy(dialogElement));
						})
					)
					.catch(() => null);
					event.preventDefault();
			});
		});
	}
};

rs.modules.Dialog.open = route =>
{
	return fetch(route,
	{
		credentials: 'same-origin',
		method: 'GET',
		headers:
		{
			'Content-Type': 'text/html',
			'X-Requested-With': 'XMLHttpRequest'
		}
	})
	.then(response => response.text());
};

rs.modules.Dialog.create = html =>
{
	return new Promise((resolve, reject) =>
	{
		if (html)
		{
			let dialogElement = document.createElement('div');

			dialogElement.innerHTML = html;
			dialogElement = dialogElement.firstChild;
			document.body.appendChild(dialogElement);
			resolve(dialogElement);
		}
		reject();
	});
};

rs.modules.Dialog.destroy = dialogElement =>
{
	return new Promise((resolve, reject) =>
	{
		if (dialogElement)
		{
			dialogElement.parentElement.removeChild(dialogElement);
			resolve();
		}
		reject();
	});
};

/* run as needed */

if (rs.modules.Dialog.frontend.init)
{
	rs.modules.Dialog.process(rs.modules.Dialog.frontend.optionArray);
}
if (rs.modules.Dialog.backend.init)
{
	rs.modules.Dialog.process(rs.modules.Dialog.backend.optionArray);
}
