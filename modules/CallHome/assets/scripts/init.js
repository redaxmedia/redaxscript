/**
 * @tableofcontents
 *
 * 1. call home
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. call home */

rs.modules.callHome =
{
	init: rs.registry.loggedIn === rs.registry.token && rs.registry.firstParameter === 'admin' && !rs.registry.adminParameter,
	dependency: typeof _gaq === 'object',
	options:
	{
		id: 'UA-16122280-10',
		url: 'none'
	}
};
