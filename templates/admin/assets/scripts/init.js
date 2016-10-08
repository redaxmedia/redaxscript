/**
 * @tableofcontents
 *
 * 1. generate alias
 * 2. panel
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. generate alias */

rs.plugins.generateAlias =
{
	init: true,
	selector: 'form input.rs-admin-js-generate-alias-input, form input.rs-admin-js-generate-alias-output',
	options:
	{
		element:
		{
			related: 'input.rs-admin-js-generate-alias-output'
		}
	}
};

/* @section 2. panel */

rs.plugins.panel =
{
	init: true,
	selector: 'ul.rs-admin-js-list-panel',
	options:
	{
		element:
		{
			panelItem: 'li.rs-admin-js-item-panel'
		},
		timeout: 1000,
		duration: 200,
		vibrate: 100
	}
};