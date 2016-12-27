/**
 * @tableofcontents
 *
 * 1. generate alias
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

	/* @section 1. generate alias */

	$.fn.generateAlias = function (options)
	{
		/* extend options */

		if (rs.plugins.generateAlias.options !== options)
		{
			options = $.extend({}, rs.plugins.generateAlias.options, options || {});
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
		if (rs.plugins.generateAlias.init)
		{
			$(rs.plugins.generateAlias.selector).generateAlias(rs.plugins.generateAlias.options);
		}
	});
})(window.jQuery || window.Zepto, window.getSlug);
