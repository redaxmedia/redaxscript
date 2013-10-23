/**
 * @tableofcontents
 *
 * 1. auto resize
 * 2. check required
 * 3. check search
 * 4. enable indent
 * 5. note required
 * 6. unmask password
 * 7. startup
 *
 * @since 2.0.0
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

	/* @section 2. check required */

	$.fn.checkRequired = function (options)
	{
		/* extend options */

		if (r.plugins.checkRequired.options !== options)
		{
			options = $.extend({}, r.plugins.checkRequired.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* validate form */

			$(this).on('submit change input related', function (event)
			{
				var form = $(this),
					buttonSubmit = form.find(options.elements.buttonSubmit),
					fieldRequired = form.find(options.elements.fieldRequired),
					fieldRequiredAll = fieldRequired;

				/* check related elements */

				if (event.type === 'related')
				{
					fieldRequired = fieldRequired.filter('[data-related]').removeAttr('data-related');
				}

				/* else those who changed */

				else if (event.type === 'change' || event.type === 'input')
				{
					fieldRequired = fieldRequired.filter(':focus');
				}

				/* check required fields */

				fieldRequired.each(function ()
				{
					var field = $(this),
						fieldNative = field[0],
						fieldTag = fieldNative.tagName,
						noteErrorClasses = 'js_note_error field_note note_error',
						fieldValue = '';

					/* check for tag */

					if (fieldTag === 'DIV')
					{
						fieldValue = $.trim(field.html());
					}
					else
					{
						fieldValue = $.trim(fieldNative.value);
					}

					/* check for value */

					if (fieldValue)
					{
						field.removeClass(noteErrorClasses).trigger('valid');
					}
					else
					{
						field.addClass(noteErrorClasses).trigger('invalid');
					}
				});

				/* trigger error and prevent submit */

				if (fieldRequiredAll.hasClass('js_note_error'))
				{
					form.trigger('error');
					buttonSubmit.attr('disabled', 'disabled');

					/* auto focus on submit */

					if (event.type === 'submit' && options.autoFocus)
					{
						fieldRequiredAll.filter('.js_note_error').first().focus();
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

	/* @section 3. check search */

	$.fn.checkSearch = function (options)
	{
		/* extend options */

		if (r.plugins.checkSearch.options !== options)
		{
			options = $.extend({}, r.plugins.checkSearch.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for submit */

			$(this).on('submit', function (event)
			{
				var form = $(this),
					field = form.find(options.required),
					fieldValue = $.trim(field.val()),
					fieldPlaceholder = field.attr('placeholder'),
					inputIncorrect = l.input_incorrect + l.exclamation_mark,
					timeout = '';

				/* prevent multiple timeout */

				if (fieldPlaceholder === inputIncorrect)
				{
					clearTimeout(timeout);
					event.preventDefault();
				}

				/* else prematurely terminate search */

				else if (fieldValue.length < 3)
				{
					field.val('').attr('placeholder', inputIncorrect);
					timeout = setTimeout(function ()
					{
						field.attr('placeholder', fieldPlaceholder).focus();
					}, options.duration);
					event.preventDefault();
				}
			});
		});
	};

	/* @section 4. enable indent */

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

	/* @section 5. note required */

	$.fn.noteRequired = function (options)
	{
		/* extend options */

		if (r.plugins.noteRequired.options !== options)
		{
			options = $.extend({}, r.plugins.noteRequired.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var form = $(this),
				formRelated = form.children(options.related).first(),
				boxNote = $('<div>').addClass(options.classString.note),
				timeout = '';

			/* insert note required */

			boxNote.hide().insertBefore(formRelated);

			/* listen for error and success */

			form.on('error success', function (event)
			{
				clearTimeout(timeout);

				/* handle error */

				if (event.type === 'error')
				{
					boxNote.html(l.input_empty + l.point).removeClass('note_success').addClass('note_error').stop(1).slideDown(options.duration);
				}

				/* else handle success */

				else
				{
					boxNote.html(l.ok + l.point).removeClass('note_error').addClass('note_success');
					timeout = setTimeout(function ()
					{
						boxNote.stop(1).slideUp(options.duration);
					}, options.timeout);
				}
			});
		});
	};

	/* @section 6. unmask password */

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

	/* @section 7. startup */

	$(function ()
	{
		if (r.plugins.autoResize.startup)
		{
			$(r.plugins.autoResize.selector).autoResize(r.plugins.autoResize.options);
		}
		if (r.plugins.checkRequired.startup)
		{
			$(r.plugins.checkRequired.selector).checkRequired(r.plugins.checkRequired.options);
		}
		if (r.plugins.checkSearch.startup && r.support.input.placeholder)
		{
			$(r.plugins.checkSearch.selector).checkSearch(r.plugins.checkSearch.options);
		}
		if (r.plugins.enableIndent.startup)
		{
			$(r.plugins.enableIndent.selector).enableIndent();
		}
		if (r.plugins.noteRequired.startup)
		{
			$(r.plugins.noteRequired.selector).noteRequired(r.plugins.noteRequired.options);
		}
		if (r.plugins.unmaskPassword.startup)
		{
			$(r.plugins.unmaskPassword.selector).unmaskPassword();
		}
	});
})(window.jQuery || window.Zepto);
