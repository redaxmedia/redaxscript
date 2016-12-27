/**
 * @tableofcontents
 *
 * 1. alias
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, getSlug)
{
	'use strict';

	/* @section 1. alias */

	$.fn.alias = function (options)
	{
		/* extend options */

		if (rs.plugins.alias.options !== options)
		{
			options = $.extend({}, rs.plugins.alias.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for change and input */

			$(this).on('change input', function ()
			{
				var field = $(this),
					form = field.closest('form'),
					related = form.find(options.element.related),
					fieldValue = $.trim(field.val()),
					aliasValue = '';

				/* clean alias from value */

				if (fieldValue)
				{
					aliasValue = getSlug(fieldValue);
					if (aliasValue)
					{
						related.val(aliasValue).add(field).attr('data-related', 'alias').trigger('related');
					}
				}

				/* else clear related value */

				else
				{
					related.val(null);
				}
			});
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.plugins.alias.init)
		{
			$(rs.plugins.alias.selector).alias(rs.plugins.alias.options);
		}
	});
})(window.jQuery || window.Zepto, window.getSlug);
