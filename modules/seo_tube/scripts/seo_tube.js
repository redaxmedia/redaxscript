/**
 * @tableofcontents
 *
 * 1. seo tube
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

	/* @section 1. seo tube */

	$.fn.seoTube = function (options)
	{
		/* extend options */

		if (r.modules.seoTube.options !== options)
		{
			options = $.extend({}, r.modules.seoTube.options, options || {});
		}

		var relatedTitle = $(options.related.title),
			relatedAlias = $(options.related.alias),
			relatedDescription = $(options.related.description),
			relatedText = $(options.related.text),
			video = r.modules.seoTube.video,
			constants = r.modules.seoTube.constants,
			eol = options.eol,
			indent = options.indent,
			text = '';

		if (typeof video === 'object')
		{
			/* fill article fields */

			if (video.title)
			{
				relatedTitle.val(video.title);
				relatedAlias.val($.fn.cleanAlias(video.title));
			}
			if (video.id)
			{
				text = '<function>' + eol + '{' + eol + indent + '"seo_tube_player":' + eol + indent + '{' + eol + indent + indent + '"video_id": "' + video.id + '"' + eol + indent + '}' + eol + '}' + eol + '</function>' + eol + eol;
			}

			/* video description */

			if (video.description)
			{
				relatedDescription.val(video.description);
				if (constants.SEO_TUBE_DESCRIPTION_PARAGRAPH > 0)
				{
					text += '<p class="text_seo_tube">' + video.description + '</p>' + eol + eol;
				}
			}

			/* video related text */

			if (text)
			{
				relatedText.val(text);
			}
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.seoTube.startup && r.constants.TABLE_PARAMETER === 'articles' && (r.constants.ADMIN_PARAMETER === 'new' || r.constants.ADMIN_PARAMETER === 'edit'))
		{
			$.fn.seoTube();
		}
	});
})(window.jQuery || window.Zepto);