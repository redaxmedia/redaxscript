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

r.modules.validator =
{
	startup: true,
	options:
	{
		classString:
		{
			validatorBox: 'box_validator',
			validatorDescription: 'description_validator',
			validatorMessage: 'message_validator'
		},
		parser: 'html5',
		level: 'all',
		url: 'http://validator.nu'
	}
};