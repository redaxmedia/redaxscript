/**
 * @tableofcontents
 *
 * 1. console
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. install */

	$.fn.console = function ()
	{
		var box = $('div.rs-console-box-default'),
			label = $('label.rs-console-label-default'),
			field = $('input.rs-console-field-text');

		$(this).on('submit', function (event)
		{
			$.post(location.href,
			{
				argv: field.val()
			})
			.done(function (response)
			{
				box.append(response);
			})
			.always(function ()
			{
				var stuff = label.text(),
					argv = field.val();

				box.append(stuff + ' ' + argv + '\r\n');
				field.val('');
				$(window).trigger('resize');
			});
			event.preventDefault();
		});
		$(window).on('resize', function ()
		{
			var stuff = box.width() - label.width() - 1;

			$('html, body').scrollTop($(document).height() - $(window).height());
			field.width(stuff);
		}).trigger('resize');
	};

	/* @section 2. init */

	$(function ()
	{
		$('form.rs-console-form-default').console();
	});
})(window.jQuery || window.Zepto);