$(function ()
{
	/* toggle del tags */

	$('div.js_box_debugger').on('click', function ()
	{
		$(this).find('del').toggle();
	});
});