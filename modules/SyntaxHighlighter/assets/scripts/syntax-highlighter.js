rs.modules.SyntaxHighlighter.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.SyntaxHighlighter.config,
		...config
	};
	const codeList = document.querySelectorAll(CONFIG.selector);

	if (codeList)
	{
		window.hljs.configure(CONFIG.hljs);
		codeList.forEach(code => window.hljs.highlightBlock(code));
	}
};

/* run as needed */

if (rs.modules.SyntaxHighlighter.init && rs.modules.SyntaxHighlighter.dependency)
{
	rs.modules.SyntaxHighlighter.execute(rs.modules.SyntaxHighlighter.config);
}
