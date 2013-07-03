/**
 * @tableofcontents
 *
 * 1. auto resize
 * 2. check required
 * 3. check search
 * 4. enable tab
 * 5. note required
 * 6. unmask password
 * 7. startup
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

			$(this).on('focus input', function (event)
			{
				var textarea = this;

				/* check limit */

				if (textarea.rows < options.limit)
				{
					/* resize on focus */

					if (event.type === 'focus')
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
				}
			}).css('overflow', options.overflow).css('resize', 'none');
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
						fieldValue = $.trim(fieldNative.value);
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

					/* auto focus on submit */

					if (event.type === 'submit' && options.autoFocus)
					{
						fieldRequiredAll.filter('.js_note_error').first().focus();
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
				var form = $(this),
					field = form.find(options.required),
					fieldValue = $.trim(field.val()),
					fieldPlaceholder = field.attr('placeholder'),
					inputIncorrect = l.input_incorrect + l.exclamation_mark,
					timeout;

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

	/* @section 4. enable tab */

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
				noteRequired = $('<div class="' + options.classString + '">').insertBefore(formRelated).hide(),
				timeout;

			/* listen for error and success */

			form.on('error success', function (event)
			{
				/* handle error */

				if (event.type === 'error')
				{
					noteRequired.html(l.input_empty + l.point).removeClass('note_success').addClass('note_error').stop(1).slideDown(options.duration);
				}

				/* else handle success */

				else
				{
					noteRequired.html(l.ok + l.point).removeClass('note_error').addClass('note_success');
					clearTimeout(timeout);
					timeout = setTimeout(function ()
					{
						noteRequired.stop(1).slideUp(options.duration);
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
		if (r.plugins.checkSearch.startup && r.support.placeholder === true)
		{
			$(r.plugins.checkSearch.selector).checkSearch(r.plugins.checkSearch.options);
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
})(window.jQuery || window.Zepto);