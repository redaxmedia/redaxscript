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
			liveReloadBox = $('<div class="' + options.classString.liveReloadBox + '"></div>').appendTo(body),
			noteBox = $('<div class="box_note note_info">' + l.live_reload_live_reload + '</div>').appendTo(liveReloadBox),
			dataOld = '', intervalReload;

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
						noteBox.removeClass('note_success').addClass('note_info').text(l.live_reload_live_reload);
					}
					else if (dataOld.length)
					{
						style.text('<!-- /* <![cdata[ */ ' + data + ' /* ]]> */ -->');
						noteBox.removeClass('note_info').addClass('note_success').text(l.live_reload_updated);
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