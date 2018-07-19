rs.templates.install.behavior.listen = config =>
{
	const CONFIG = {...rs.templates.install.behavior.config, ...config};
	const form = document.querySelector(CONFIG.selector);
	const fieldType = form.querySelector(CONFIG.element.fieldType);
	const fieldToggle = form.querySelectorAll(CONFIG.element.fieldToggle);

	form.setAttribute('novalidate', 'novalidate');

	/* listen on change */

	fieldType.addEventListener('change', () =>
	{
		fieldToggle.forEach(field => field.parentElement.style.display = fieldType.value === 'sqlite' ? 'none' : null);
	});
	fieldType.dispatchEvent(new Event('change'));
};

/* run as needed */

if (rs.templates.install.behavior.init)
{
	rs.templates.install.behavior.listen(rs.templates.install.behavior.config);
}