rs.modules.Analytics =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof window.ga === 'function',
	config:
	{
		selector:
		{
			click: 'a.rs-js-track-click, button.rs-js-track-click'
		},
		analytics:
		{
			trackingId: 'UA-00000000-0',
			cookieDomain: 'auto',
			anonymizeIp: true
		}
	}
};
