/**
 * @tableofcontents
 *
 * 1. auto resize
 * 2. check required
 * 3. check search
 * 4. clear focus
 * 5. enable tab
 * 6. note required
 * 7. unmask password
 * 8. startup
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
			/* listen for ready and input */

			$(this).on('ready input', function (event)
			{
				var textarea = this;

				/* on ready resize */

				if (event.type === 'ready')
				{
					while (textarea.clientHeight < textarea.scrollHeight)
					{
						textarea.rows += options.summand++;
					}
				}

				/* general resize */

				while (textarea.clientHeight === textarea.scrollHeight && textarea.rows > 1)
				{
					textarea.rows -= 1;
				}
				while (textarea.clientHeight < textarea.scrollHeight)
				{
					textarea.rows += 1;
				}
			}).css('overflow', options.overflow).css('resize', 'none').trigger('ready');
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

				/* check needed elements only */

				if (event.type === 'related')
				{
					fieldRequired = fieldRequired.filter('[data-related]').removeAttr('data-related');
				}
				else if (event.type === 'change' || event.type === 'input')
				{
					fieldRequired = fieldRequired.filter(':focus');
				}

				/* check required fields */

				fieldRequired.each(function ()
				{
					var field = $(this),
						fieldTag = field.context.tagName,
						noteErrorClasses = 'js_note_error note_error',
						fieldValue;

					/* check for tag */

					if (fieldTag === 'DIV')
					{
						fieldValue = $.trim(field.html());
						noteErrorClasses += ' box_note';
					}
					else
					{
						fieldValue = $.trim(field[0].value);
						noteErrorClasses += ' field_note';
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
					buttonSubmit.attr('disabled', 'disabled').addClass('field_disabled');

					/* auto focus */

					if (options.autoFocus)
					{
						fieldRequiredAll.filter('.js_note_error:first').focus();
					}
					event.preventDefault();
				}

				/* else trigger success */

				else
				{
					form.trigger('success');
					buttonSubmit.removeAttr('disabled').removeClass('field_disabled');
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
				var field = $(this).find(options.required)[0],
					fieldValue = $.trim(field.value),
					fieldDefaultValue = field.defaultValue,
					inputIncorrect = l.input_incorrect + l.exclamation_mark;

				/* prematurely terminate search */

				if (fieldValue === '' || fieldValue === fieldDefaultValue || fieldValue === inputIncorrect)
				{
					field.value = inputIncorrect;
					setTimeout(function ()
					{
						field.value = fieldDefaultValue;
					}, options.duration);
					event.preventDefault();
				}
			});
		});
	};

	/* @section 4. clear focus */

	$.fn.clearFocus = function ()
	{
		/* return this */

		return this.each(function ()
		{
			/* listen for focusin and focusout */

			$(this).on('focusin focusout', function (event)
			{
				var field = this,
					fieldValue = $.trim(field.value),
					fieldValueDefault = field.defaultValue;

				if (event.type === 'focusin' && fieldValue === fieldValueDefault)
				{
					field.value = '';
				}
				else if (fieldValue === '')
				{
					field.value = fieldValueDefault;
				}
			});
		});
	};

	/* @section 5. enable tab */

	$.fn.enableTab = function (options)
	{
		/* extend options */

		if (r.plugins.enableTab.options !== options)
		{
			options = $.extend({}, r.plugins.enableTab.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for keydown */

			$(this).on('keydown', function (event)
			{
				var textarea = this,
					textareaValue = this.value,
					selection = document.selection,
					selectionStart = textarea.selectionStart,
					selectionRange;

				if (event.which === 9)
				{
					if (typeof selectionStart === 'number')
					{
						textarea.value = textareaValue.slice(0, selectionStart) + options.insertion + textareaValue.slice(textarea.selectionEnd);
						textarea.selectionEnd = textarea.selectionStart = selectionStart + options.insertion.length;
					}

					/* else fallback */

					else if (typeof selection === 'object')
					{
						selectionRange = selection.createRange();
						selectionRange.text = options.insertion;
						selectionRange.collapse(false);
						selectionRange.select();
					}
					event.preventDefault();
				}
			});
		});
	};

	/* @section 6. note required */

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
				noteRequired = $('<div class="' + options.classString + '">').insertBefore(formRelated).hide(),
				timeout;

			/* listen for error and success */

			form.on('error success', function (event)
			{
				clearTimeout(timeout);

				/* handle error */

				if (event.type === 'error')
				{
					noteRequired.html(l.input_empty + l.point).removeClass('note_success').addClass('note_error').slideDown(options.duration);
				}

				/* else handle success */

				else
				{
					noteRequired.html(l.ok + l.point).removeClass('note_error').addClass('note_success');
					timeout = setTimeout(function ()
					{
						noteRequired.slideUp(options.duration);
					}, options.timeout);
				}
			});
		});
	};

	/* @section 7. unmask password */

	$.fn.unmaskPassword = function ()
	{
		/* return this */

		return this.each(function ()
		{
			/* listen for keydown and focusout */

			$(this).on('keydown focusout', function (event)
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

	/* @section 8. startup */

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
		if (r.plugins.checkSearch.startup)
		{
			$(r.plugins.checkSearch.selector).checkSearch(r.plugins.checkSearch.options);
		}
		if (r.plugins.clearFocus.startup)
		{
			$(r.plugins.clearFocus.selector).clearFocus();
		}
		if (r.plugins.enableTab.startup)
		{
			$(r.plugins.enableTab.selector).enableTab();
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
})(jQuery);