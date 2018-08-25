rs.modules.TextareaResizer.execute = config =>
{
	const CONFIG = {...rs.modules.TextareaResizer.config, ...config};
	const textareaList = document.querySelectorAll(CONFIG.selector);

	if (textareaList)
	{
		window.autosize(textareaList);
	}
};

/* run as needed */

if (rs.modules.TextareaResizer.frontend.init && rs.modules.TextareaResizer.frontend.dependency)
{
	rs.modules.TextareaResizer.execute(rs.modules.TextareaResizer.frontend.config);
}
if (rs.modules.TextareaResizer.backend.init && rs.modules.TextareaResizer.backend.dependency)
{
	rs.modules.TextareaResizer.execute(rs.modules.TextareaResizer.backend.config);
}
