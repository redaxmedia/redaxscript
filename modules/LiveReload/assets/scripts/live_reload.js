/**
 * @tableofcontents
 *
 * 1. live reload
 * 2. init
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

		if (rs.modules.liveReload.options !== options)
		{
			options = $.extend({}, rs.modules.liveReload.options, options || {});
		}

		var head = $('head'),
			body = $('body'),
			style = head.children('style[media="all"]'),
			liveReloadBox = $('<div>').addClass(options.className.liveReloadBox),
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
					url: rs.registry.parameterRoute + options.url,
					success: function (data)
					{
						if (data === dataOld)
						{
							liveReloadBox.html('<div class="rs-box-note rs-note-info">' + rs.language._live_reload.live_reload + '</div>').toggle();
						}
						else if (dataOld.length)
						{
							style.text('<!-- /* <![cdata[ */ ' + data + ' /* ]]> */ -->');
							liveReloadBox.html('<div class="rs-box-note rs-note-success">' + rs.language._live_reload.updated + '</div>').show();
						}
						dataOld = data;
					}
				});
			}
		}, options.duration);
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.liveReload.init)
		{
			$.fn.liveReload(rs.modules.liveReload.options);
		}
	});
})(window.jQuery || window.Zepto);
