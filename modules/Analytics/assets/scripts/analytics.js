rs.modules.Analytics.execute = config =>
{
	const CONFIG = {...rs.modules.Analytics.config, ...config};

	window.ga('create', CONFIG.analytics);

	/* handle track */

	Object.keys(CONFIG.selector).forEach(trackEvent =>
	{
		const element = document.querySelectorAll(CONFIG.selector[trackEvent]);

		element.addEventListener(trackEvent, event =>
		{
			const data = event.target.dataset;
			const category = String(data.category);
			const action = String(data.action);
			const label = data.label ? String(data.label) : null;
			const value = data.value ? String(data.value) : null;

			if (category && action)
			{
				window.ga('send',
				{
					hitType: 'event',
					eventCategory: category,
					eventAction: action,
					eventLabel: label,
					eventValue: value
				});
			}
		});
	});
};

/* run as needed */

if (rs.modules.Analytics.init && rs.modules.Analytics.dependency)
{
	rs.modules.Analytics.execute(rs.modules.Analytics.config);
}
