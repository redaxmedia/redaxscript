(function ($)
{
	/* seo tube */

	$.fn.seoTube = function ()
	{
		var text;

		if (typeof seoTube == 'object')
		{
			/* fill article fields */

			if (seoTube.video.title)
			{
				$('#title').val(seoTube.video.title);
				$('#alias').val($.fn.cleanAlias(seoTube.video.title));
			}
			if (seoTube.video.id)
			{
				text = '<function>seo_tube_player|' + seoTube.video.id + '</function>' + r.constant.EOL + r.constant.EOL;
			}
			if (seoTube.video.description)
			{
				$('#description').val(seoTube.video.description);
				if (seoTube.constant.SEO_TUBE_DESCRIPTION_PARAGRAPH > 0)
				{
					text += '<p class="description_seo_tube">' + seoTube.video.description + '</p>' + r.constant.EOL + r.constant.EOL;
				}
			}
			if (seoTube.constant.SEO_TUBE_COMMENT_FEED > 0)
			{
				text += '<function>feed_reader|' + seoTube.constant.SEO_TUBE_GDATA_URL + '/' + seoTube.video.id + '/comments->' + seoTube.constant.SEO_TUBE_COMMENT_LIMIT + '</function>';
			}
			if (text)
			{
				$('#text').val(text);
			}
		}
	};
})(jQuery);

$(function () 
{
	/* startup */

	if (r.constant.TABLE_PARAMETER == 'articles' && (r.constant.ADMIN_PARAMETER == 'new' || r.constant.ADMIN_PARAMETER == 'edit'))
	{
		$.fn.seoTube();
	}
});