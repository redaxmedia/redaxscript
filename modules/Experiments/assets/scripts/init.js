/**
 * @tableofcontents
 *
 * 1. experiments
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. experiments */

rs.modules.experiments =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof cxApi === 'object'
};
