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

			/* listen on reset */

			textareaElement.closest('form').addEventListener('reset', () =>
			{
				textareaElement.previousSibling.innerHTML = '';
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
	linkElement.setAttribute('title', control.title);

	/* listen on click */

	linkElement.addEventListener('click', event =>
	{
		const selection = window.getSelection();
		const range = selection.getRangeAt(0);
		const isRange = selection.type === 'Range';
		const targetElement = selection.focusNode.parentElement;
		const isLink = targetElement.tagName === 'A';
		const isImage = targetElement.tagName === 'IMG';

		if (control.name === 'handle-link' && isRange && !isLink || control.name === 'handle-image' && !isRange && !isImage)
		{
			rs.modules.Dialog.prompt(null, control.title)
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
			document.execCommand(control.commandArray[1], false, null);
			selection.removeAllRanges();
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
		rs.modules.ImageUpload.upload(formData)
			.then(fileArray =>
			{
				document.querySelector(OPTION.selector).previousSibling.focus();
				fileArray.map(fileValue => document.execCommand('insertImage', false, fileValue));
			})
			.catch(() => null);
	});
	return fieldElement;
};

rs.modules.VisualEditor.createContent = (textareaElement, OPTION) =>
{
	const contentElement = document.createElement('div');
	const inputEvent = new Event('input');

	contentElement.classList.add(OPTION.className.boxContent);
	contentElement.classList.add(OPTION.className.boxVisualEditor);
	contentElement.setAttribute('contenteditable', 'true');
	contentElement.innerHTML = textareaElement.value;

	/* listen on input */

	contentElement.addEventListener('input', event =>
	{
		if (event.currentTarget.innerHTML === '<br>')
		{
			event.currentTarget.innerHTML = '';
		}
		event.currentTarget.nextSibling.value = event.currentTarget.innerHTML;
		event.currentTarget.nextSibling.dispatchEvent(inputEvent);
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
	rs.modules.VisualEditor.process();
}
