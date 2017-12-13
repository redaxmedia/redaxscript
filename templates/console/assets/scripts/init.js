/**
 * @tableofcontents
 *
 * 1. console
 */

/** @section 1. console */

rs.plugins.console =
{
	init: true,
	selector: 'form.rs-console-js-form',
	options:
	{
		element:
		{
			consoleBox: 'div.rs-console-js-box',
			consoleLabel: 'label.rs-console-js-label',
			consoleField: 'input.rs-console-js-field',
			root: 'html, body'
		},
		eol: '\n'
	}
};