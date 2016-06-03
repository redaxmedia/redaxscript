/**
 * @tableofcontents
 *
 * 1. install
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. install */

	$.fn.install = function (options)
	{
		/* extend options */

		if (rs.plugins.install.options !== options)
		{
			options = $.extend({}, rs.plugins.install.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var form = $(this),
				fieldType = form.find(options.element.fieldType),
				fieldRelated = form.find(options.element.fieldRelated),
				fieldRequired = form.find(options.element.fieldRequired),
				fieldHost = form.find(options.element.fieldHost);

			/* listen for change */

			fieldType.on('change', function ()
			{
				var that = $(this),
					type = that.val(),
					host = fieldHost.attr('data-' + type);

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
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.plugins.install.init)
		{
			$(rs.plugins.install.selector).install(rs.plugins.install.options);
		}
	});
})(window.jQuery || window.Zepto);