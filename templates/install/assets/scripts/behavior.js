rs.templates.install.behavior.listen = config =>
{
	const CONFIG =
	{
		...rs.templates.install.behavior.config,
		...config
	};
	const form = document.querySelector(CONFIG.selector);
	const fieldType = form.querySelector(CONFIG.element.fieldType);
	const fieldToggleList = form.querySelectorAll(CONFIG.element.fieldToggle);
	const changeEvent = new Event('change');

	form.setAttribute('novalidate', 'novalidate');

	/* listen on change */

	fieldType.addEventListener('change', () =>
	{
		fieldToggleList.forEach(field => field.parentElement.style.display = fieldType.value === 'sqlite' ? 'none' : null);
	});
	fieldType.dispatchEvent(changeEvent);
};

/* run as needed */

if (rs.templates.install.behavior.init)
{
	rs.templates.install.behavior.listen(rs.templates.install.behavior.config);
}
