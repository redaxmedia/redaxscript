rs.modules.Ace.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Ace.optionArray,
		...optionArray
	};
	const textareaList = document.querySelectorAll(OPTION.selector);

	if (textareaList)
	{
		textareaList.forEach(textarea =>
		{
			const box = document.createElement('div');
			const editor = window.ace.edit(box);
			const editorSession = editor.getSession();

			/* handle textarea */

			textarea.style.display = 'none';
			textarea.parentNode.appendChild(box);

			/* handle editor */

			editor.setOptions(OPTION.ace);
			editorSession.setValue(textarea.value);
			editorSession.on('change', () => textarea.value = editorSession.getValue());
		});
	}
};

/* run as needed */

if (rs.modules.Ace.init && rs.modules.Ace.dependency)
{
	rs.modules.Ace.process(rs.modules.Ace.optionArray);
}
