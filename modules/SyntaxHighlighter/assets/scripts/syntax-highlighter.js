rs.modules.SyntaxHighlighter.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.SyntaxHighlighter.optionArray,
		...optionArray
	};
	const codeList = document.querySelectorAll(OPTION.selector);

	if (codeList)
	{
		window.hljs.configure(OPTION.hljs);
		codeList.forEach(code => window.hljs.highlightBlock(code));
	}
};

/* run as needed */

if (rs.modules.SyntaxHighlighter.init && rs.modules.SyntaxHighlighter.dependency)
{
	rs.modules.SyntaxHighlighter.process(rs.modules.SyntaxHighlighter.optionArray);
}
