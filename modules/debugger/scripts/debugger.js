(function ($)
{
	'use strict';

	/* debugger */

	$.fn.debugger = function (options)
	{
		/* extend options */

		if (r.module.debugger.options !== options)
		{
			options = $.extend({}, r.module.debugger.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function ()
			{
				$(this).find(options.related).toggle();
			});
		});
	};

	/* startup */

	$(function ()
	{
		if (r.module.debugger.startup)
		{
			$(r.module.debugger.selector).debugger(r.module.debugger.options);
		}
	});
})(jQuery);