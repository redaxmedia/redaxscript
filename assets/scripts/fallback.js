/**
 * @tableofcontents
 *
 * 1. fallback
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. fallback */

(function (doc, $)
{
	'use strict';

	if (typeof $ === 'undefined')
	{
		var script = doc.querySelector('script[src*="jquery.min.js"]'),
			src = script.src.replace('ajax.googleapis.com', 'cdnjs.cloudflare.com');

		script.parentNode.removeChild(script);
		doc.write('<script src="' + src + '"></script>');
	}
})(document, window.jQuery);