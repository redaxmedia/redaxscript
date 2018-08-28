rs.modules.CallHome.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.CallHome.config,
		...config
	};

	/* handle view */

	window.ga('create', CONFIG.analytics);
	window.ga('send', 'pageview');

	/* handle track */

	window.ga('send',
	{
		hitType: 'event',
		eventCategory: 'call-home',
		eventAction: rs.version,
		eventLabel: rs.baseURL
	});
};

/* run as needed */

if (rs.modules.CallHome.init && rs.modules.CallHome.dependency)
{
	rs.modules.CallHome.execute(rs.modules.CallHome.config);
}
