rs.modules.Experiments =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof window.cxApi === 'object'
};
