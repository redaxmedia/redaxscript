/* debugger */

(function ($)
{
	/* editor */

	$.fn.debugger = function (options)
	{
		/* extend options */

		if (r.module.debugger.options !== options)
		{
			options = $.extend({}, r.module.debugger.options, options || {});
		}

		/* toggle related */

		$(this).on('click', function ()
		{
			$(this).find(options.related).toggle();
		});
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.module.debugger.startup)
	{
		$(r.module.debugger.selector).debugger(r.module.editor.options);
	}
});