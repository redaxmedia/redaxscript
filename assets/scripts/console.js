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
		$(window).on('resize', function ()
		{
			var stuff = $('div.rs-console-box-default').width() - $('label.rs-console-label-default').width() - 1;

			$('html, body').scrollTop($(document).height() - $(window).height());
			$('input.rs-console-field-text').width(stuff);
		}).trigger('resize');
		$('form.rs-console-form-default').on('submit', function (event)
		{
			$.post(location.href,
			{
				argv: $('input.rs-console-field-text').val()
			})
			.done(function (response)
			{
				$('div.rs-console-box-default').append(response);
			})
			.always(function ()
			{
				var stuff = $('label.rs-console-label-default').text(),
					argv = $('input.rs-console-field-text').val();

				$('div.rs-console-box-default').append(stuff + ' ' + argv + '\r\n');
				$('input.rs-console-field-text').val('');
				$('html, body').scrollTop($(document).height() - $(window).height());
				$(window).trigger('resize');
			});
			event.preventDefault();
		});
	};

	/* @section 2. init */

	$(function ()
	{
		$.fn.console();
	});
})(window.jQuery || window.Zepto);