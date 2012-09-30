(function ($)
{
	/* check required */

	$.fn.checkRequired = function (options)
	{
		/* extend options */

		if (r.plugin.checkRequired.options !== options)
		{
			options = $.extend({}, r.plugin.checkRequired.options, options || {});
		}

		/* validate required fields */

		$(this).on('submit change input related', function (event)
		{
			var form = $(this),
				fieldRequired = form.find(options.required),
				fieldRequiredAll = fieldRequired;

			/* check needed elements only */

			if (event.type === 'related')
			{
				fieldRequired = fieldRequired.filter('[data-related]').removeAttr('data-related');
			}
			else if (event.type === 'change' ||  event.type === 'input')
			{
				fieldRequired = fieldRequired.filter(':active');
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
					fieldValue = $.trim(field.text());
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
				event.preventDefault();
			}

			/* else trigger success */

			else
			{
				form.trigger('success');
			}
		}).attr('novalidate', 'novalidate');
	};

	/* check search */

	$.fn.checkSearch = function (options)
	{
		/* extend options */

		if (r.plugin.checkSearch.options !== options)
		{
			options = $.extend({}, r.plugin.checkSearch.options, options || {});
		}

		/* listen for submit */

		$(this).submit(function (event)
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
	};

	/* note required */

	$.fn.noteRequired = function (options)
	{
		/* extend options */

		if (r.plugin.noteRequired.options !== options)
		{
			options = $.extend({}, r.plugin.noteRequired.options, options || {});
		}

		var form = $(this),
			formRelatedFirst = form.find(options.related).first(),
			noteRequired = $(options.markup).insertBefore(formRelatedFirst).hide(),
			timeout;

		/* form validate events */

		form.on('error success', function (event)
		{
			clearTimeout(timeout);
			if (event.type === 'error')
			{
				noteRequired.html(l.input_empty + l.point).removeClass('note_success').addClass('note_error').show();
			}
			else
			{
				noteRequired.html(l.ok + l.point).removeClass('note_error').addClass('note_success');
				timeout = setTimeout(function ()
				{
					noteRequired.hide();
				}, options.duration);
			}
		});
	};

	/* clear fields on focus */

	$.fn.clearFocus = function ()
	{
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
	};

	/* unmask password on keydown */

	$.fn.unmaskPassword = function ()
	{
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
	};

	/* autoresize textarea rows */

	$.fn.autoResize = function (options)
	{
		/* extend options */

		if (r.plugin.autoResize.options !== options)
		{
			options = $.extend({}, r.plugin.autoResize.options, options || {});
		}

		/* listen for input */

		$(this).on('ready input', function (event)
		{
			var textarea = this;

			if (event.type === 'ready')
			{
				while (textarea.clientHeight < textarea.scrollHeight)
				{
					textarea.rows += options.summand++;
				}
			}
			while (textarea.clientHeight === textarea.scrollHeight && textarea.rows > 1)
			{
				textarea.rows -= 1;
			}
			while (textarea.clientHeight < textarea.scrollHeight)
			{
				textarea.rows += 1;
			}
		}).css('overflow', options.overflow).css('resize', 'none').trigger('ready');
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.plugin.checkRequired.startup)
	{
		$(r.plugin.checkRequired.selector).checkRequired(r.plugin.checkRequired.options);
	}
	if (r.plugin.checkSearch.startup)
	{
		$(r.plugin.checkSearch.selector).checkSearch(r.plugin.checkSearch.options);
	}
	if (r.plugin.noteRequired.startup)
	{
		$(r.plugin.noteRequired.selector).noteRequired(r.plugin.noteRequired.options);
	}
	if (r.plugin.clearFocus.startup)
	{
		$(r.plugin.clearFocus.selector).clearFocus();
	}
	if (r.plugin.unmaskPassword.startup)
	{
		$(r.plugin.unmaskPassword.selector).unmaskPassword();
	}
	if (r.plugin.autoResize.startup)
	{
		$(r.plugin.autoResize.selector).autoResize(r.plugin.autoResize.options);
	}
});