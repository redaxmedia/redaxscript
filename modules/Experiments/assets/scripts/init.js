/**
 * @tableofcontents
 *
 * 1. experiments
 */

/** @section 1. experiments */

rs.modules.experiments =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof cxApi === 'object'
};
