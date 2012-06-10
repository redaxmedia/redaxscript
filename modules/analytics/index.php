<?php

/* analytics loader start */

function analytics_loader_start()
{
	if (LOGGED_IN != TOKEN)
	{
		global $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/analytics/scripts/startup.js';
	}
}

/* analytics scripts start */

function analytics_scripts_start()
{
	if (LOGGED_IN != TOKEN)
	{
		$output = '<script type="text/javascript" src="http://google-analytics.com/ga.js"></script>' . PHP_EOL;
		echo $output;
	}
}
?>