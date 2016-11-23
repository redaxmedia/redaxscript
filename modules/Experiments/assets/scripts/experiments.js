/**
 * @tableofcontents
 *
 * 1. experiments
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, cxApi)
{
	'use strict';

	/* @section 1. experiments */

	$.fn.experiments = function ()
	{
		var id = cxApi.chooseVariation();

		/* start as needed */

		if (typeof rs.modules.experiments.variation[id] === 'function')
		{
			rs.modules.experiments.variation[id](id);
		}
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.experiments.init && rs.modules.experiments.dependency)
		{
			$.fn.experiments();
		}
	});
})(window.jQuery || window.Zepto, window.cxApi);
