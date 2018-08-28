rs.modules.Experiments =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof window.cxApi === 'object',
	config:
	{
		action:
		{
			0: () => document.documentElement.classList.add('rs-no-variation'),
			1: () => document.documentElement.classList.add('rs-is-variation-1'),
			2: () => document.documentElement.classList.add('rs-is-variation-2')
		}
	}
};
