rs.modules.UnmaskPassword.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.UnmaskPassword.getOption(),
		...optionArray
	};
	const fieldList = document.querySelectorAll(OPTION.selector);

	if (fieldList)
	{
		fieldList.forEach(field =>
		{
			const wrapper = document.createElement('span');
			const button = document.createElement('button');

			wrapper.classList.add(OPTION.className.wrapper);
			button.classList.add(OPTION.className.button);
			field.classList.add(OPTION.className.field);
			button.setAttribute('type', 'button');
			field.parentNode.insertBefore(wrapper, field);
			wrapper.appendChild(field);
			wrapper.appendChild(button);

			/* listen on click */

			button.addEventListener('click', () =>
			{
				button.classList.toggle(OPTION.className.active);
				if (field.getAttribute('type') === 'password')
				{
					field.setAttribute('type', 'text');
				}
				else
				{
					field.setAttribute('type', 'password');
				}
			});
		});
	}
};

rs.modules.UnmaskPassword.getOption = () =>
{
	if (rs.modules.UnmaskPassword.frontend.init)
	{
		return rs.modules.UnmaskPassword.frontend.optionArray;
	}
	if (rs.modules.UnmaskPassword.backend.init)
	{
		return rs.modules.UnmaskPassword.backend.optionArray;
	}
};

/* run as needed */

if (rs.modules.UnmaskPassword.frontend.init || rs.modules.UnmaskPassword.backend.init)
{
	rs.modules.UnmaskPassword.process();
}
