(function ($)
{
	/* seo tube */

	$.fn.seoTube = function (options)
	{
		/* extend options */

		if (r.module.seoTube.options !== options)
		{
			options = $.extend({}, r.module.seoTube.options, options || {});
		}

		var relatedTitle = $(options.related.title),
			relatedAlias = $(options.related.alias),
			relatedDescription = $(options.related.description),
			relatedText = $(options.related.text),
			video = r.module.seoTube.video,
			constant = r.module.seoTube.constant,
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
				text = '<function>seo_tube_player|' + video.id + '</function>' + r.constant.EOL + r.constant.EOL;
			}

			/* video description */

			if (video.description)
			{
				relatedDescription.val(video.description);
				if (constant.SEO_TUBE_DESCRIPTION_PARAGRAPH > 0)
				{
					text += '<p class="text_seo_tube">' + video.description + '</p>' + r.constant.EOL + r.constant.EOL;
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

	/* startup */

	$(function ()
	{
		if (r.module.seoTube.startup && r.constant.TABLE_PARAMETER === 'articles' && (r.constant.ADMIN_PARAMETER === 'new' || r.constant.ADMIN_PARAMETER === 'edit'))
		{
			$.fn.seoTube();
		}
	});
})(jQuery);