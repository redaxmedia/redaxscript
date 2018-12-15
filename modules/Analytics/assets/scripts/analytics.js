rs.modules.Analytics.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Analytics.optionArray,
		...optionArray
	};

	/* handle view */

	window.ga('create', OPTION.analytics);
	window.ga('send', 'pageview');

	/* handle track */

	Object.keys(OPTION.selector).forEach(trackEvent =>
	{
		const elementList = document.querySelectorAll(OPTION.selector[trackEvent]);

		if (elementList)
		{
			elementList.forEach(element => element.addEventListener(trackEvent, event =>
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
			}));
		}
	});
};

/* run as needed */

if (rs.modules.Analytics.init && rs.modules.Analytics.dependency)
{
	rs.modules.Analytics.process(rs.modules.Analytics.optionArray);
}
