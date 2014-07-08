/**
 * @tableofcontents
 *
 * 1. auto resize
 * 2. enable indent
 * 3. unmask password
 * 4. validate form
 * 5. validate search
 * 6. startup
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. auto resize */

	$.fn.autoResize = function (options)
	{
		/* extend options */

		if (r.plugins.autoResize.options !== options)
		{
			options = $.extend({}, r.plugins.autoResize.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for focus and input */

			$(this).on('focus input', function ()
			{
				var textarea = this,
					value = textarea.value,
					newlines = value.split(options.eol).length;

				/* newlines hack */

				if (textarea.rows < newlines)
				{
					textarea.rows = newlines;
				}

				/* general resize */

				while (textarea.clientHeight === textarea.scrollHeight && textarea.rows > 1)
				{
					textarea.rows -= 1;
				}
				while (textarea.clientHeight < textarea.scrollHeight && textarea.rows < options.limit)
				{
					textarea.rows += 1;
				}
			}).css({
				overflow: options.overflow,
				resize: options.resize
			});
		});
	};

	/* @section 2. enable indent */

	$.fn.enableIndent = function (options)
	{
		/* extend options */

		if (r.plugins.enableIndent.options !== options)
		{
			options = $.extend({}, r.plugins.enableIndent.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for keydown */

			$(this).on('keydown', function (event)
			{
				var textarea = this,
					textareaValue = textarea.value,
					selectionStart = textarea.selectionStart,
					selectionEnd = textarea.selectionEnd,
					selectionText = textareaValue.slice(selectionStart, selectionEnd),
					selectionBefore = textareaValue.slice(0, selectionStart),
					selectionAfter = textareaValue.slice(selectionEnd),
					eol = options.eol,
					indent = options.indent,
					counter = 0;

				if ('selectionStart' in textarea)
				{
					if (event.which === 9)
					{
						/* remove indent */

						if (event.shiftKey)
						{
							/* if selection */

							if (selectionText.length)
							{
								textarea.value = selectionBefore + selectionText.replace(window.RegExp(eol + indent, 'g'), function ()
								{
									counter++;
									return eol;
								}).replace(indent, function ()
								{
									counter++;
									return '';
								}) + selectionAfter;
								textarea.selectionEnd = selectionEnd - (counter * indent.length);
								textarea.selectionStart = selectionStart;
							}

							/* else without selection */

							else if (textareaValue.slice(selectionStart - indent.length).indexOf(indent) === 0)
							{
								textarea.value = textareaValue.slice(0, selectionStart - indent.length) + textareaValue.slice(selectionStart);
								textarea.selectionStart = textarea.selectionEnd = selectionStart - indent.length;
							}
						}

						/* else add indent */

						else
						{
							/* if selection */

							if (selectionText.length)
							{
								textarea.value = selectionBefore + indent + selectionText.replace(window.RegExp(eol, 'g'), function ()
								{
									counter++;
									return eol + indent;
								}) + selectionAfter;
								counter++;
								textarea.selectionEnd = selectionEnd + (counter * indent.length);
								textarea.selectionStart = selectionStart;
							}

							/* else without selection */

							else
							{
								textarea.value = selectionBefore + indent + selectionText + selectionAfter;
								textarea.selectionStart = textarea.selectionEnd = selectionStart + indent.length;
							}
						}
						event.preventDefault();
					}
				}
			});
		});
	};

	/* @section 3. unmask password */

	$.fn.unmaskPassword = function ()
	{
		/* return this */

		return this.each(function ()
		{
			/* listen for keydown and blur */

			$(this).on('keydown blur', function (event)
			{
				var field = this;

				if (event.ctrlKey && event.altKey)
				{
					field.type = 'text';
				}
				else
				{
					field.type = 'password';
				}
			});
		});
	};

	/* @section 4. validate form */

	$.fn.validateForm = function (options)
	{
		/* extend options */

		if (r.plugins.validateForm.options !== options)
		{
			options = $.extend({}, r.plugins.validateForm.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* validate form */

			$(this).on('submit input related', function (event)
			{
				var form = $(this),
					buttonSubmit = form.find(options.element.buttonSubmit),
					field = form.find(options.element.field),
					fieldAll = field;

				/* filter related fields */

				if (event.type === 'related')
				{
					field = field.filter('[data-related]').removeAttr('data-related');
				}

				/* else focused fields */

				else if (event.type === 'input')
				{
					field = field.filter(':focus');
				}

				/* validate fields */

				field.each(function ()
				{
					var that = $(this),
						thatNative = that[0],
						thatContenteditable = that.attr('contenteditable'),
						thatLabel = that.siblings('label'),
						className = 'js_note_error field_note note_error',
						validity = 'valid',
						thatValue = '',
						thatRequired = '',
						message = '';

					/* contenteditable field */

					if (thatContenteditable)
					{
						thatValue = that.html();

						/* check empty value */

						if (!thatValue)
						{
							validity = 'invalid';
							message = l.input_empty + l.point;
						}
					}

					/* missing support */

					else if (!r.support.checkValidity)
					{
						thatValue = that.val();
						thatRequired = that.attr('required');

						/* check required value */

						if (thatRequired && !thatValue)
						{
							validity = 'invalid';
							message = l.input_empty + l.point;
						}
					}

					/* use native validation */

					else if (!thatNative.checkValidity())
					{
						validity = 'invalid';
						message = thatNative.validationMessage;
					}

					/* handle invalid */

					if (validity === 'invalid')
					{
						that.addClass(className).trigger('invalid');
						if (message && options.message)
						{
							thatLabel.addClass('label_message').attr('data-message', message);
						}
					}

					/* else handle valid */

					else
					{
						that.removeClass(className).trigger('valid');
						if (options.message)
						{
							thatLabel.removeClass('label_message').removeAttr('data-message');
						}
					}
				});

				/* trigger error and prevent submit */

				if (fieldAll.hasClass('js_note_error'))
				{
					form.trigger('error');
					buttonSubmit.attr('disabled', 'disabled');

					/* auto focus on submit */

					if (event.type === 'submit' && options.autoFocus)
					{
						fieldAll.filter('.js_note_error').first().focus();
					}

					/* haptic feedback */

					if (event.type === 'submit' && r.support.vibrate && typeof options.vibrate === 'number')
					{
						window.navigator.vibrate(options.vibrate);
					}
					event.preventDefault();
				}

				/* else trigger success */

				else
				{
					form.trigger('success');
					buttonSubmit.removeAttr('disabled');
				}
			}).attr('novalidate', 'novalidate');
		});
	};

	/* @section 5. validate search */

	$.fn.validateSearch = function (options)
	{
		/* extend options */

		if (r.plugins.validateSearch.options !== options)
		{
			options = $.extend({}, r.plugins.validateSearch.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for submit */

			$(this).on('submit', function (event)
			{
				var form = $(this),
					field = form.find(options.element.field),
					fieldValue = field.val(),
					fieldPlaceholder = field.attr('placeholder'),
					message = l.input_incorrect + l.exclamation_mark,
					timeout = '';

				/* prevent multiple timeout */

				if (fieldPlaceholder === message)
				{
					clearTimeout(timeout);
					event.preventDefault();
				}

				/* else prematurely terminate search */

				else if (fieldValue.length < 3)
				{
					field.val('').attr('placeholder', message);
					timeout = setTimeout(function ()
					{
						field.attr('placeholder', fieldPlaceholder).focus();
					}, options.duration);
					event.preventDefault();
				}
			});
		});
	};

	/* @section 6. startup */

	$(function ()
	{
		if (r.plugins.autoResize.startup)
		{
			$(r.plugins.autoResize.selector).autoResize(r.plugins.autoResize.options);
		}
		if (r.plugins.enableIndent.startup)
		{
			$(r.plugins.enableIndent.selector).enableIndent();
		}
		if (r.plugins.unmaskPassword.startup)
		{
			$(r.plugins.unmaskPassword.selector).unmaskPassword();
		}
		if (r.plugins.validateForm.startup)
		{
			$(r.plugins.validateForm.selector).validateForm(r.plugins.validateForm.options);
		}
		if (r.plugins.validateSearch.startup && r.support.input.placeholder)
		{
			$(r.plugins.validateSearch.selector).validateSearch(r.plugins.validateSearch.options);
		}
	});
})(window.jQuery || window.Zepto);