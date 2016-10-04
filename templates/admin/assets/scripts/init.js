/**
 * @tableofcontents
 *
 * 1. dock
 * 2. generate alias
 * 3. generate keyword
 * 4. panel
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. dock */

rs.plugins.dock =
{
	init: true,
	selector: 'div.rs-admin-js-dock',
	options:
	{
		element:
		{
			dockLink: 'a.rs-admin-js-link-dock',
			dockDescription: 'span.rs-admin-js-dock',
			dockDescriptionHTML: '<span class="rs-admin-js-dock rs-admin-text-dock"></span>'
		},
		vibrate: 100
	}
};

/* @section 2. generate alias */

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

/* @section 3. generate keyword */

rs.plugins.generateKeyword =
{
	init: true,
	selector: 'form textarea.rs-admin-js-generate-keyword-input',
	options:
	{
		element:
		{
			related: 'textarea.rs-admin-js-generate-keyword-output',
			target: 'h1, h2, h3, strong'
		},
		splitter:
		{
			text: '\n',
			keyword: ' '
		},
		delimiter: ' ',
		limit: 10
	}
};

/* @section 4. panel */

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