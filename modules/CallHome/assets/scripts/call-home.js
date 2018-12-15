rs.modules.CallHome.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.CallHome.optionArray,
		...optionArray
	};

	/* handle view */

	window.ga('create', OPTION.analytics);
	window.ga('send', 'pageview');

	/* handle track */

	window.ga('send',
	{
		hitType: 'event',
		eventCategory: 'call-home',
		eventAction: rs.version,
		eventLabel: rs.baseUrl
	});
};

/* run as needed */

if (rs.modules.CallHome.init && rs.modules.CallHome.dependency)
{
	rs.modules.CallHome.process(rs.modules.CallHome.optionArray);
}
