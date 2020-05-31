rs.modules.VisualEditor.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.VisualEditor.optionArray,
		...optionArray
	};
	const textareaList = document.querySelectorAll(OPTION.selector);

	if (textareaList)
	{
		textareaList.forEach(textarea =>
		{
			textarea.before(rs.modules.VisualEditor.createToolbar(OPTION));
			textarea.before(rs.modules.VisualEditor.createContent(textarea, OPTION));
			textarea.style.display = 'none';
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

	linkElement.classList.add(OPTION.className.linkVisualEditor);
	linkElement.setAttribute('data-name', control.name);
	linkElement.setAttribute('title', control.title);

	/* listen on down */

	linkElement.addEventListener('pointerdown', event =>
	{
		document.execCommand(control.command, false, control.value);
		event.preventDefault();
	});
	itemElement.appendChild(linkElement);
	return itemElement;
};

rs.modules.VisualEditor.createContent = (textarea, OPTION) =>
{
	const contentElement = document.createElement('div');

	contentElement.classList.add(OPTION.className.boxContent);
	contentElement.classList.add(OPTION.className.boxVisualEditor);
	contentElement.setAttribute('contenteditable', 'true');
	contentElement.innerHTML = textarea.textContent;

	/* listen on input */

	contentElement.addEventListener('input', event =>
	{
		if (event.currentTarget.innerHTML === '<br>')
		{
			event.currentTarget.innerHTML = '';
		}
		event.currentTarget.nextSibling.textContent = event.currentTarget.innerHTML;
		event.currentTarget.nextSibling.dispatchEvent(new Event('input'));
	});
	return contentElement;
};

/* run as needed */

if (rs.modules.VisualEditor.frontend.init)
{
	rs.modules.VisualEditor.process(rs.modules.VisualEditor.frontend.optionArray);
}
if (rs.modules.VisualEditor.backend.init)
{
	rs.modules.VisualEditor.process(rs.modules.VisualEditor.backend.optionArray);
}
