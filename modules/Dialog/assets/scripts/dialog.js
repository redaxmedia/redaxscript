rs.modules.Dialog.open = (route, body) =>
{
	return fetch(route,
	{
		credentials: 'same-origin',
		method: 'POST',
		headers:
		{
			'Content-Type': 'text/html',
			'X-Requested-With': 'XMLHttpRequest'
		},
		body: JSON.stringify(body)
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

rs.modules.Dialog.alert = (message, title, optionArray) =>
{
	const OPTION =
	{
		...rs.modules.Dialog.getOption(),
		...optionArray
	};

	return new Promise((resolve, reject) =>
	{
		rs.modules.Dialog.open(OPTION.route.alert,
		{
			message,
			title
		})
		.then(html => rs.modules.Dialog.create(html)
			.then(dialogElement =>
			{
				dialogElement.querySelector(OPTION.element.buttonOk).addEventListener('click', () =>
				{
					rs.modules.Dialog.destroy(dialogElement)
						.then(() => resolve({
							action: 'ok'
						}))
						.catch(() => reject());
				});
			})
		)
		.catch(() => reject());
	});
};

rs.modules.Dialog.confirm = (message, title, optionArray) =>
{
	const OPTION =
	{
		...rs.modules.Dialog.getOption(),
		...optionArray
	};

	return new Promise((resolve, reject) =>
	{
		rs.modules.Dialog.open(OPTION.route.confirm,
		{
			message,
			title
		})
		.then(html => rs.modules.Dialog.create(html)
			.then(dialogElement =>
			{
				dialogElement.querySelector(OPTION.element.buttonOk).addEventListener('click', () =>
				{
					rs.modules.Dialog.destroy(dialogElement)
						.then(() => resolve(
						{
							action: 'ok'
						}))
						.catch(() => reject());
				});
				dialogElement.querySelector(OPTION.element.buttonCancel).addEventListener('click', () =>
				{
					rs.modules.Dialog.destroy(dialogElement)
						.then(() => resolve(
						{
							action: 'cancel'
						}))
						.catch(() => reject());
				});
			})
		)
		.catch(() => reject());
	});
};

rs.modules.Dialog.prompt = (message, title, optionArray) =>
{
	const OPTION =
	{
		...rs.modules.Dialog.getOption(),
		...optionArray
	};

	return new Promise((resolve, reject) =>
	{
		rs.modules.Dialog.open(OPTION.route.prompt,
		{
			message,
			title
		})
		.then(html => rs.modules.Dialog.create(html)
			.then(dialogElement =>
			{
				dialogElement.querySelector(OPTION.element.field).focus();
				dialogElement.querySelector(OPTION.element.buttonOk).addEventListener('click', () =>
				{
					rs.modules.Dialog.destroy(dialogElement)
						.then(() => resolve(
						{
							action: 'ok',
							value: dialogElement.querySelector(OPTION.element.field).value
						}))
						.catch(() => reject());
				});
				dialogElement.querySelector(OPTION.element.buttonCancel).addEventListener('click', () =>
				{
					rs.modules.Dialog.destroy(dialogElement)
						.then(() => resolve(
						{
							action: 'cancel'
						}))
						.catch(() => reject());
				});
			})
		)
		.catch(() => reject());
	});
};

rs.modules.Dialog.getOption = () =>
{
	if (rs.modules.Dialog.frontend.init)
	{
		return rs.modules.Dialog.frontend.optionArray;
	}
	if (rs.modules.Dialog.backend.init)
	{
		return rs.modules.Dialog.backend.optionArray;
	}
};
