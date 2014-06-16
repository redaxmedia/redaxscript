/**
 * @tableofcontents
 *
 * 1. live reload
 * 2. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. live reload */

	$.fn.liveReload = function (options)
	{
		/* extend options */

		if (r.modules.liveReload.options !== options)
		{
			options = $.extend({}, r.modules.liveReload.options, options || {});
		}

		var head = $('head'),
			body = $('body'),
			style = head.children('style[media="all"]'),
			liveReloadBox = $('<div>').addClass(options.classString.liveReloadBox),
			dataOld = '',
			intervalReload = '';

		/* append live reload box */

		liveReloadBox.hide().appendTo(body);

		/* interval reload */

		intervalReload = setInterval(function ()
		{
			if (typeof document.visibilityState === 'undefined' || document.visibilityState === 'visible')
			{
				$.ajax(
				{
					url: r.constants.REWRITE_ROUTE + options.url,
					success: function (data)
					{
						if (data === dataOld)
						{
							liveReloadBox.html('<div class="box_note note_info">' + l.live_reload.live_reload + '</div>').toggle();
						}
						else if (dataOld.length)
						{
							style.text('<!-- /* <![cdata[ */ ' + data + ' /* ]]> */ -->');
							liveReloadBox.html('<div class="box_note note_success">' + l.live_reload.updated + '</div>').show();
						}
						dataOld = data;
					}
				});
			}
		}, options.duration);
	};

	/* @section 2. startup */

	$(function ()
	{
		$.fn.liveReload(r.modules.liveReload.options);
	});
})(window.jQuery || window.Zepto);