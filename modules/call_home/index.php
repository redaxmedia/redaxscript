<?php

/* call home loader start */

function call_home_loader_start()
{
	global $loader_modules_scripts;
	$loader_modules_scripts[] = 'modules/call_home/scripts/startup.js';
}

/* call home scripts start */

function call_home_scripts_start()
{
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin')
	{
		$output = '<script type="text/javascript" src="http://google-analytics.com/ga.js"></script>' . PHP_EOL;
		echo $output;
	}
}

/* call home admin notification start */

function call_home_admin_notification_start()
{
	/* get contents */

	$url_version = 'http://service.' . l('redaxscript_website') . '/version/' . clean_alias(l('redaxscript_version'));
	$contents = file_get_contents($url_version);

	/* collect output */

	if ($contents)
	{
		$output = $contents;
	}
	else
	{
		$output = '<div class="box_note note_error">' . l('call_home_server_not_found') . '</div>';
	}
	echo $output;
}

/* call home admin notification end */

function call_home_admin_notification_end()
{
	$url = 'http://service.' . l('redaxscript_website') . '/news';
	$contents = file_get_contents($url);

	/* collect output */

	if ($contents)
	{
		$output = $contents;
	}
	echo $output;
}
?>