/**
 * @tableofcontents
 *
 * 1. init
 */

(function ($)
{
	'use strict';

	/** @section 1. init */

	$(function ()
	{
		if (rs.modules.lightGallery.init)
		{
			$(rs.modules.lightGallery.selector).lightGallery(rs.modules.lightGallery.options);
		}
	});
})(window.jQuery);