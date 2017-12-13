/**
 * @tableofcontents
 *
 * 1. ace
 * 2. init
 */

(function ($, ace)
{
	'use strict';

	/** @section 1. ace */

	$.fn.ace = function (options)
	{
		/* extend options */

		if (rs.modules.ace.options !== options)
		{
			options = $.extend({}, rs.modules.ace.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var textarea = $(this),
				editor;

			textarea.hide();
			$('<div>').attr('id', 'editor').insertBefore(textarea);
			editor = ace.edit('editor');

			/* set the options */

			editor.setOptions(options.ace);

			/* transport to editor */

			editor.getSession().setValue(textarea.val());

			/* listen for change */

			editor.getSession().on('change', function ()
			{
				textarea.val(editor.getSession().getValue());
			});
		});
	};

	/** @section 2. init */

	$(function ()
	{
		if (rs.modules.ace.init && rs.modules.ace.dependency)
		{
			$(rs.modules.ace.selector).ace(rs.modules.ace.options);
		}
	});
})(window.jQuery, window.ace);
