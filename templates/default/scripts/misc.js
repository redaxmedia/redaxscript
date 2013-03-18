/**
 * @tableofcontents
 *
 * 1. logo effect
 * 2. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. logo effect */

	$.fn.logoEffect = function (options)
	{
		/* extend options */

		if (r.plugins.logoEffect.options !== options)
		{
			options = $.extend({}, r.plugins.logoEffect.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var related = $(options.related);

			/* listen for hover */

			$(this).hover(function ()
			{
				related.stop(1).fadeTo(options.duration, 0);
			}, function ()
			{
				related.delay(options.duration).fadeTo(options.duration * 2, 1);
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.plugins.logoEffect.startup)
		{
			$(r.plugins.logoEffect.selector).logoEffect(r.plugins.logoEffect.options);
		}
	});
})(jQuery);