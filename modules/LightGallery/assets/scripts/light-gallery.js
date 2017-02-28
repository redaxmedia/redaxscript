/**
 * @tableofcontents
 *
 * 1. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. init */

	$(function ()
	{
		if (rs.modules.lightGallery.init)
		{
			$(rs.modules.lightGallery.selector).lightGallery(rs.modules.lightGallery.options);
		}
	});
})(window.jQuery);