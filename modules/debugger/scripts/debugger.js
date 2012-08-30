$(function ()
{
	/* toggle del tags */

	$('div.js_box_debugger').click(function ()
	{
		$(this).find('del').toggle();
	});

	/* append jquery version and document elements */

	$('div.js_box_debugger:first ul').append('<li>jquery_version:<span>' + $().jquery + '</span></li><li>document:<span>' + $('*').length + ' elements</span></li>');
});