rs.modules.VisualEditor.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.VisualEditor.getOption(),
		...optionArray
	};
	const textareaList = document.querySelectorAll(OPTION.selector);

	if (textareaList)
	{
		textareaList.forEach(textareaElement =>
		{
			textareaElement.before(rs.modules.VisualEditor.createToolbar(OPTION));
			textareaElement.before(rs.modules.VisualEditor.createUpload(OPTION));
			textareaElement.before(rs.modules.VisualEditor.createContent(textareaElement, OPTION));
			textareaElement.style.display = 'none';

			/* listen on input */

			textareaElement.addEventListener('input', event =>
			{
				textareaElement.previousSibling.innerHTML = event.currentTarget.value;
			});

			/* listen on reset */

			textareaElement.closest('form').addEventListener('reset', () =>
			{
				textareaElement.previousSibling.innerHTML = null;
			});
		});
	}
};

rs.modules.VisualEditor.createToolbar = OPTION =>
{
	const listElement = document.createElement('ul');

	listElement.classList.add(OPTION.className.listVisualEditor);
	OPTION.controlArray.map(control => listElement.appendChild(rs.modules.VisualEditor.createControl(control, OPTION)));
	return listElement;
};

rs.modules.VisualEditor.createControl = (control, OPTION) =>
{
	const itemElement = document.createElement('li');
	const linkElement = document.createElement('a');
	const inputEvent = new Event('input');

	linkElement.classList.add(OPTION.className.linkVisualEditor);
	linkElement.setAttribute('data-name', control.name);
	linkElement.setAttribute('title', control.title ? control.title : control.titleArray[0]);

	/* listen on click */

	linkElement.addEventListener('click', event =>
	{
		const selection = window.getSelection();
		const range = selection.getRangeAt(0);
		const isRange = selection.type === 'Range';
		const isLink = rs.modules.VisualEditor.selectionHasTag(selection, 'a');
		const isImage = rs.modules.VisualEditor.selectionHasTag(selection, 'img');

		if (control.name === 'toggle')
		{
			rs.modules.VisualEditor.toggle(OPTION);
		}
		else if (control.name === 'handle-link' && isRange && !isLink || control.name === 'handle-image' && !isRange && !isImage)
		{
			rs.modules.Dialog.prompt(null, control.titleArray[0])
				.then(response =>
				{
					selection.removeAllRanges();
					selection.addRange(range);
					if (response.action === 'ok')
					{
						document.execCommand(control.commandArray[0], false, response.value);
						document.querySelector(OPTION.selector).previousSibling.dispatchEvent(inputEvent);
						selection.removeAllRanges();
					}
				})
				.catch(() => null);
		}
		else if (control.name === 'handle-link' && isRange && isLink || control.name === 'handle-image' && isRange && isImage)
		{
			rs.modules.Dialog.confirm(null, control.titleArray[1])
				.then(response =>
				{
					if (response.action === 'ok')
					{
						document.execCommand(control.commandArray[1], false, null);
						selection.removeAllRanges();
					}
				})
				.catch(() => null);
		}
		else if (control.name === 'upload-image')
		{
			document.querySelector(OPTION.element.fieldUpload).click();
		}
		else
		{
			document.execCommand(control.command, false, control.value);
		}
		event.preventDefault();
	});
	itemElement.appendChild(linkElement);
	return itemElement;
};

rs.modules.VisualEditor.toggle = OPTION =>
{
	const textareaElement = document.querySelector(OPTION.selector);

	textareaElement.style.display = textareaElement.style.display ? null : 'none';
	textareaElement.previousSibling.style.display = textareaElement.previousSibling.style.display ? null : 'none';
};

rs.modules.VisualEditor.selectionHasTag = (selection, tag) =>
{
	const targetElement = selection.anchorNode.parentElement;
	const tagArray = selection.anchorNode.childNodes ? Array.from(selection.anchorNode.childNodes).map(element => element.tagName) : [];

	if (tagArray.length)
	{
		return tagArray.includes(tag.toUpperCase());
	}
	return targetElement.tagName === tag.toUpperCase();
};

rs.modules.VisualEditor.createUpload = OPTION =>
{
	const formData = new FormData();
	const fieldElement = document.createElement('input');

	fieldElement.setAttribute('type', 'file');
	fieldElement.setAttribute('multiple', 'multiple');
	fieldElement.setAttribute('accept', OPTION.mimeTypeArray.join(', '));
	fieldElement.classList.add(OPTION.className.fieldUpload);
	fieldElement.style.display = 'none';

	/* listen on change */

	fieldElement.addEventListener('change', () =>
	{
		Object.keys(fieldElement.files).map(fileValue => formData.append(fileValue, fieldElement.files[fileValue]));
		if (typeof rs.modules.ImageUpload === 'object')
		{
			rs.modules.ImageUpload.upload(formData)
				.then(fileArray =>
				{
					document.querySelector(OPTION.selector).previousSibling.focus();
					fileArray.map(fileValue => document.execCommand('insertImage', false, fileValue));
				})
				.catch(() => null);
		}
		else
		{
			rs.modules.Dialog.alert(rs.language.something_wrong + rs.language.point);
		}
	});
	return fieldElement;
};

rs.modules.VisualEditor.createContent = (textareaElement, OPTION) =>
{
	const contentElement = document.createElement('div');
	const validateEvent = new Event('validate');

	contentElement.classList.add(OPTION.className.boxVisualEditor);
	contentElement.setAttribute('contenteditable', 'true');
	contentElement.innerHTML = textareaElement.value;

	/* listen on input */

	contentElement.addEventListener('input', event =>
	{
		if (event.currentTarget.innerHTML === '<br>')
		{
			event.currentTarget.innerHTML = null;
		}
		event.currentTarget.nextSibling.value = event.currentTarget.innerHTML;
		event.currentTarget.nextSibling.dispatchEvent(validateEvent);
	});

	/* listen on keydown */

	contentElement.addEventListener('keydown', event =>
	{
		if (event.key === 'Enter')
		{
			window.document.execCommand('insertLineBreak', false, null);
			event.preventDefault();
		}
	});
	return contentElement;
};

rs.modules.VisualEditor.getOption = () =>
{
	if (rs.modules.VisualEditor.frontend.init)
	{
		return rs.modules.VisualEditor.frontend.optionArray;
	}
	if (rs.modules.VisualEditor.backend.init)
	{
		return rs.modules.VisualEditor.backend.optionArray;
	}
};

/* run as needed */

if (rs.modules.VisualEditor.frontend.init || rs.modules.VisualEditor.backend.init)
{
	window.addEventListener('load', () => rs.modules.VisualEditor.process());
}
