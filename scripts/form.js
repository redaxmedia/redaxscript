(function ($)
{
	/* check required */

	$.fn.checkRequired = function (options)
	{
		/* extend options */

		if (r.plugin.checkRequired.options != options)
		{
			options = $.extend({}, r.plugin.checkRequired.options, options || {});
		}

		$(this).each(function ()
		{
			var form = $(this),
				noteErrorClasses = 'js_note_error note_error';

			/* remove required attribute */

			$(options.required).each(function ()
			{
				$(this).removeAttr('required');
			});

			/* validate required fields on submit */

			form.submit(function ()
			{
				form.find(options.required).each(function ()
				{
					var fieldRequired = $(this),
						fieldRequiredTag = fieldRequired.context.tagName,
						fieldRequiredValue;

					/* check for tag */

					if (fieldRequiredTag == 'DIV')
					{
						fieldRequiredValue = $.trim(fieldRequired.text());
						noteErrorClasses += ' box_note';
					}
					else
					{
						fieldRequiredValue = $.trim(fieldRequired[0].value);
					}

					/* check for value */

					if (fieldRequiredValue == '')
					{
						fieldRequired.addClass(noteErrorClasses);
					}
				});

				/* trigger error event */

				if (form.find(options.required).hasClass('js_note_error'))
				{
					form.trigger('error');
					return false;
				}
			});

			/* validate required fields on change, input and keyup */

			form.find(options.required).on('change input keyup', function ()
			{
				var fieldRequired = $(this),
					fieldRequiredTag = fieldRequired.context.tagName,
					fieldRequiredValue;

				/* check for tag */

				if (fieldRequiredTag == 'DIV')
				{
					fieldRequiredValue = $.trim(fieldRequired.text());
					noteErrorClasses += ' box_note';

				}
				else
				{
					fieldRequiredValue = $.trim(fieldRequired[0].value);
				}

				/* check for value */

				if (fieldRequiredValue)
				{
					fieldRequired.removeClass(noteErrorClasses);
				}

				/* trigger success event */

				if (form.find(options.required).hasClass('js_note_error') == 0)
				{
					form.trigger('success');
				}
			});
		});
	};

	/* check search */

	$.fn.checkSearch = function (options)
	{
		/* extend options */

		if (r.plugin.checkSearch.options != options)
		{
			options = $.extend({}, r.plugin.checkSearch.options, options || {});
		}

		$(this).submit(function ()
		{
			var field = $(this).find(options.required)[0],
				fieldValue = $.trim(field.value),
				fieldDefaultValue = field.defaultValue,
				inputIncorrect = l.input_incorrect + l.exclamation_mark;

			/* prematurely terminate search */

			if (fieldValue == '' || fieldValue == fieldDefaultValue || fieldValue == inputIncorrect)
			{
				field.value = inputIncorrect;
				setTimeout(function ()
				{
					field.value = fieldDefaultValue;
				}, options.duration);
				return false;
			}
		});
	};

	/* note required */

	$.fn.noteRequired = function (options)
	{
		/* extend options */

		if (r.plugin.noteRequired.options != options)
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

			if (event.type == 'focusin' && fieldValue == fieldValueDefault)
			{
				field.value = '';
			}
			else if (fieldValue == '')
			{
				field.value = fieldValueDefault;
			}
		});
	};

	/* autoresize textarea rows */

	$.fn.autoResize = function (options)
	{
		/* extend options */

		if (r.plugin.autoResize.options != options)
		{
			options = $.extend({}, r.plugin.autoResize.options, options || {});
		}

		$(this).css('overflow', options.overflow).on('ready input', function (event)
		{
			var textarea = this;

			if (event.type == 'ready')
			{
				while (textarea.clientHeight < textarea.scrollHeight)
				{
					textarea.rows += options.summand++;
				}
			}
			while (textarea.clientHeight == textarea.scrollHeight && textarea.rows > 1)
			{
				textarea.rows -= 1;
			}
			while (textarea.clientHeight < textarea.scrollHeight)
			{
				textarea.rows += 1;
			}
		}).trigger('ready');
	};
})(jQuery);

$(function ()
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
	if (r.plugin.autoResize.startup)
	{
		$(r.plugin.autoResize.selector).autoResize(r.plugin.autoResize.options);
	}
});