(function ($)
{
	/* logo effect */

	$.fn.logoEffect = function (options)
	{
		/* extend options */

		if (r.plugin.logoEffect.options !== options)
		{
			options = $.extend({}, r.plugin.logoEffect.options, options || {});
		}

		var related = $(options.related);

		/* website logo effect on hover */

		$(this).hover(function ()
		{
			related.stop(1).fadeTo(options.duration, 0);
		}, function ()
		{
			related.delay(options.duration).fadeTo(options.duration * 2, 1);
		});
	};
})(jQuery);

$(function ()
{
	/* startup */

	if (r.plugin.logoEffect.startup)
	{
		$(r.plugin.logoEffect.selector).logoEffect(r.plugin.logoEffect.options);
	}
});