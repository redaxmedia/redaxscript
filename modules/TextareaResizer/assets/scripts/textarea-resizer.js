rs.modules.TextareaResizer.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.TextareaResizer.optionArray,
		...optionArray
	};
	const textareaList = document.querySelectorAll(OPTION.selector);

	if (textareaList)
	{
		window.autosize(textareaList);
	}
};

/* run as needed */

if (rs.modules.TextareaResizer.frontend.init && rs.modules.TextareaResizer.frontend.dependency)
{
	rs.modules.TextareaResizer.process(rs.modules.TextareaResizer.frontend.optionArray);
}
if (rs.modules.TextareaResizer.backend.init && rs.modules.TextareaResizer.backend.dependency)
{
	rs.modules.TextareaResizer.process(rs.modules.TextareaResizer.backend.optionArray);
}
