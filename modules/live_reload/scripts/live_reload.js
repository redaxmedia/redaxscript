/**
 * @tableofcontents
 *
 * 1. live reload
 * 2. startup
 *
 * @since 2.0
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
			liveReloadBox = $('<div class="' + options.classString.liveReloadBox + '"></div>').hide().appendTo(body),
			dataOld = '',
			intervalReload = '';

		/* interval reload */

		intervalReload = setInterval(function ()
		{
			$.ajax(
			{
				url: r.constants.REWRITE_ROUTE + options.url,
				success: function (data)
				{
					if (data === dataOld)
					{
						liveReloadBox.html('<div class="box_note note_info">' + l.live_reload_live_reload + '</div>').toggle();
					}
					else if (dataOld.length)
					{
						style.text('<!-- /* <![cdata[ */ ' + data + ' /* ]]> */ -->');
						liveReloadBox.html('<div class="box_note note_success">' + l.live_reload_updated + '</div>').show();
					}
					dataOld = data;
				}
			});
		}, options.duration);
	};

	/* @section 2. startup */

	$(function ()
	{
		$.fn.liveReload(r.modules.liveReload.options);
	});
})(window.jQuery || window.Zepto);