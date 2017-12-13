/**
 * @tableofcontents
 *
 * 1. install
 */

/** @section 1. install */

rs.plugins.install =
{
	init: true,
	selector: 'form.rs-install-js-form',
	options:
	{
		element:
		{
			fieldType: '#db-type',
			fieldRelated: '#db-name, #db-user, #db-password',
			fieldRequired: '#db-name, #db-user',
			fieldHost: '#db-host'
		}
	}
};