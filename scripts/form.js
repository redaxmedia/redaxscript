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

		$(this).on('submit change input', function (event)
		{
			var form = $(this),
				fieldRequired = form.find(options.required);

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
					field.removeClass(noteErrorClasses);
				}
				else
				{
					field.addClass(noteErrorClasses);
				}
			});

			/* trigger error and prevent submit */

			if (fieldRequired.hasClass('js_note_error'))
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
			formRelatedFirst = form.find(options.related).first();

		/* form validate events */

		form.on('error', function ()
		{
			form.find('div.js_note_required').remove();
			formRelatedFirst.before('<div class="js_note_required js_note_required_error box_note note_error">' + l.input_empty + l.point + '</div>');
		}).on('success', function ()
		{
			form.find('div.js_note_required_error').replaceWith('<div class="js_note_required js_note_required_success box_note note_success">' + l.ok + l.point + '</div>');
			setTimeout(function ()
			{
				form.find('div.js_note_required_success').remove();
			}, options.duration);
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