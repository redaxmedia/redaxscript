/**
 * @tableofcontents
 *
 * 1. experiments
 * 2. init
 */

(function ($, cxApi)
{
	'use strict';

	/** @section 1. experiments */

	$.fn.experiments = function ()
	{
		var html = $('html'),
			id = cxApi.chooseVariation(),
			variation = rs.variation || [];

		if (typeof id === 'number')
		{
			html.addClass('rs-is-variation-' + id);

			/* init as needed */

			if (typeof variation[id] === 'function')
			{
				variation[id]();
			}
		}
	};

	/** @section 2. init */

	$(function ()
	{
		if (rs.modules.experiments.init && rs.modules.experiments.dependency)
		{
			$.fn.experiments();
		}
	});
})(window.jQuery, window.cxApi);
