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

		var title = $(options.element.title),
			alias = $(options.element.alias),
			description = $(options.element.description),
			text = $(options.element.text),
			video = r.modules.seoTube.video,
			constants = r.modules.seoTube.constants,
			eol = options.eol,
			indent = options.indent,
			output = '';

		/* video object exists */

		if (typeof video === 'object')
		{
			/* fill article fields */

			if (video.title)
			{
				title.val(video.title);
				alias.val($.fn.cleanAlias(video.title));
			}
			if (video.id)
			{
				output = '<function>' + eol + '{' + eol + indent + '"seo_tube_player":' + eol + indent + '{' + eol + indent + indent + '"video_id": "' + video.id + '"' + eol + indent + '}' + eol + '}' + eol + '</function>' + eol + eol;
			}

			/* video description */

			if (video.description)
			{
				description.val(video.description);
				if (constants.SEO_TUBE_DESCRIPTION_PARAGRAPH > 0)
				{
					output += '<p class="text_seo_tube">' + video.description + '</p>' + eol + eol;
				}
			}

			/* video text */

			if (text)
			{
				text.val(output);
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
