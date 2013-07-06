/**
 * @tableofcontents
 *
 * 1. seo tube
 * 2. startup
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
			constant = r.modules.seoTube.constant,
			eol = '\n',
			tab = '\t',
			text;

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
				text = '<function>' + eol + '{' + eol + tab + '"seo_tube_player":' + eol + tab + '{' + eol + tab + tab + '"video_id": "' + video.id + '"' + eol + tab + '}' + eol + '}' + eol + '</function>' + eol + eol;
			}

			/* video description */

			if (video.description)
			{
				relatedDescription.val(video.description);
				if (constant.SEO_TUBE_DESCRIPTION_PARAGRAPH > 0)
				{
					text += '<p class="text_seo_tube">' + video.description + '</p>' + eol + eol;
				}
			}

			/* video related feed */

			if (constant.SEO_TUBE_COMMENT_FEED > 0)
			{
				text += eol + '<function>' + eol + '{' + eol + tab + '"feed_reader":' + eol + tab + '{' + eol + tab + tab + '"url": "' + constant.SEO_TUBE_GDATA_URL + '/' + video.id + '/comments"';
				if (constant.SEO_TUBE_COMMENT_LIMIT)
				{
					text += ',' + eol + tab + tab + '"filter": "",' + eol + tab + tab + '"limit": "' + constant.SEO_TUBE_COMMENT_LIMIT + '"';
				}
				text += eol + tab + '}' + eol + '}' + eol + '</function>' + eol + eol;
			}
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