rs.modules.CallHome =
{
	init: rs.registry.loggedIn === rs.registry.token && rs.registry.firstParameter === 'admin',
	dependency: typeof window.ga === 'function',
	config:
	{
		analytics:
		{
			trackingId: 'UA-16122280-10',
			cookieDomain: 'auto',
			anonymizeIp: true
		}
	}
};
