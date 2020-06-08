rs.modules.ConfirmAction.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.ConfirmAction.getOption(),
		...optionArray
	};
	const linkList = document.querySelectorAll(OPTION.selector);

	if (linkList)
	{
		linkList.forEach(linkElement =>
		{
			linkElement.addEventListener('click', event =>
			{
				rs.modules.Dialog.confirm(rs.language._confirm_action.continue_question)
					.then(response =>
					{
						if (response.action === 'ok')
						{
							window.location = linkElement.href;
						}
					})
					.catch(() => null);
				event.preventDefault();
			});
		});
	}
};

rs.modules.ConfirmAction.getOption = () =>
{
	if (rs.modules.ConfirmAction.frontend.init)
	{
		return rs.modules.ConfirmAction.frontend.optionArray;
	}
	if (rs.modules.ConfirmAction.backend.init)
	{
		return rs.modules.ConfirmAction.backend.optionArray;
	}
};

/* run as needed */

if (rs.modules.ConfirmAction.frontend.init || rs.modules.ConfirmAction.backend.init)
{
	rs.modules.ConfirmAction.process();
}

