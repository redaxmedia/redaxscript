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
				text = '<function>seo_tube_player|' + video.id + '</function>' + r.constants.EOL + r.constants.EOL;
			}

			/* video description */

			if (video.description)
			{
				relatedDescription.val(video.description);
				if (constant.SEO_TUBE_DESCRIPTION_PARAGRAPH > 0)
				{
					text += '<p class="text_seo_tube">' + video.description + '</p>' + r.constants.EOL + r.constants.EOL;
				}
			}

			/* video related feed */

			if (constant.SEO_TUBE_COMMENT_FEED > 0)
			{
				text += '<function>feed_reader|' + constant.SEO_TUBE_GDATA_URL + '/' + video.id + '/comments->' + constant.SEO_TUBE_COMMENT_LIMIT + '</function>';
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
})(jQuery);