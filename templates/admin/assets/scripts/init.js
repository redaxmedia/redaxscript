/**
 * @tableofcontents
 *
 * 1. alias
 * 2. panel
 */

/** @section 1. alias */

rs.plugins.alias =
{
	init: true,
	dependency: typeof getSlug === 'function',
	selector: 'form input.rs-admin-js-alias-input, form input.rs-admin-js-alias-output',
	options:
	{
		element:
		{
			related: 'input.rs-admin-js-alias-output'
		}
	}
};

/** @section 2. panel */

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