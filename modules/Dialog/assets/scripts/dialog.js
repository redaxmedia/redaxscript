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
		linkList.forEach(link =>
		{
			link.addEventListener('click', event =>
			{
				fetch(OPTION.confirmRoute,
					{
						credentials: 'same-origin',
						method: 'GET',
						headers:
						{
							'Content-Type': 'text/html',
							'X-Requested-With': 'XMLHttpRequest'
						}
					})
					.then(response => response.text())
					.then(html => rs.modules.Dialog.create(html).then(dialog =>
					{
						dialog.querySelector(OPTION.element.buttonOk).addEventListener('click', () => window.location = link.href);
						dialog.querySelector(OPTION.element.buttonCancel).addEventListener('click', () => rs.modules.Dialog.destroy(dialog));
					}));
				event.preventDefault();
			});
		});
	}
};

rs.modules.Dialog.create = html =>
{
	return new Promise((resolve, reject) =>
	{
		if (html)
		{
			let dialog = document.createElement('div');

			dialog.innerHTML = html;
			dialog = dialog.firstChild;
			document.body.appendChild(dialog);
			resolve(dialog);
		}
		reject();
	});
};

rs.modules.Dialog.destroy = dialog =>
{
	return new Promise((resolve, reject) =>
	{
		if (dialog)
		{
			dialog.parentElement.removeChild(dialog);
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
