/**
 * @tableofcontents
 *
 * 1. install
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. install */

	$(function ()
	{
		var fieldType = $('#type'),
			fieldRelated = $('#d_name, #d_user, #d_password'),
			fieldRequired = $('#d_name, #d_user'),
			fieldHost = $('#d_host');

		/* listen for change */

		fieldType.on('change', function ()
		{
			var that = $(this),
				type = that.val(),
				host = fieldHost.attr('data-' + type) || fieldHost.attr('data-default');

            fieldHost.val(host);

			/* hide related */

			if (type === 'sqlite')
			{
				fieldRequired.removeAttr('required').removeClass('js_note_error note_error');
				fieldRelated.parent().hide();
			}

			/* else show related */

			else
			{
				fieldRequired.attr('required', 'required');
				fieldRelated.parent().show();
			}
		}).trigger('change');
	});
})(window.jQuery || window.Zepto);
