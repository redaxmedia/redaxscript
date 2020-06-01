rs.modules.CodeEditor.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.CodeEditor.optionArray,
		...optionArray
	};
	const textareaList = document.querySelectorAll(OPTION.selector);

	if (textareaList)
	{
		textareaList.forEach(textareaElement =>
		{
			const boxElement = document.createElement('div');
			const editor = window.ace.edit(boxElement);
			const editorSession = editor.getSession();

			/* handle textarea */

			textareaElement.before(boxElement);
			textareaElement.style.display = 'none';

			/* handle editor */

			editor.setOptions(OPTION.ace);
			editorSession.setValue(textareaElement.value);
			editorSession.on('change', () => textareaElement.value = editorSession.getValue());
		});
	}
};

/* run as needed */

if (rs.modules.CodeEditor.init && rs.modules.CodeEditor.dependency)
{
	rs.modules.CodeEditor.process(rs.modules.CodeEditor.optionArray);
}
