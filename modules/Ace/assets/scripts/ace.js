rs.modules.Ace.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.Ace.config,
		...config
	};
	const textareaList = document.querySelectorAll(CONFIG.selector);

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

			editor.setOptions(CONFIG.ace);
			editorSession.setValue(textarea.value);
			editorSession.on('change', () => textarea.value = editorSession.getValue());
		});
	}
};

/* run as needed */

if (rs.modules.Ace.init && rs.modules.Ace.dependency)
{
	rs.modules.Ace.execute(rs.modules.Ace.config);
}
