/**
 * @tableofcontents
 *
 * 1. validator
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. validator */

rs.modules.validator =
{
	init: true,
	options:
	{
		className:
		{
			validatorBox: 'rs-box-validator',
			validatorDescription: 'rs-description-validator',
			validatorMessage: 'rs-message-validator'
		},
		url: 'http://validator.nu',
		parser: 'html5',
		level: 'all'
	}
};