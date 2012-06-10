<?php

/* recent view */

function recent_view($limit = '')
{
	$recent_view_log = recent_view_logger();
	if ($recent_view_log)
	{
		$output = '<ul class="list_recent_view">';
		foreach ($recent_view_log as $value)
		{
			if (++$counter > $limit && $limit)
			{
				break;
			}
			$output .= '<li>' . anchor_element('internal', '', '', $value, $value) . '</li>';
		}
		$output .= '</ul>';
	}
	return $output;
}

/* recent view logger */

function recent_view_logger()
{
	if (FULL_STRING)
	{
		if (end($_SESSION[ROOT . '/recent_view']) != FULL_STRING)
		{
			$_SESSION[ROOT . '/recent_view'][] = FULL_STRING;
		}
	}
	$output = array_reverse($_SESSION[ROOT . '/recent_view']);
	return $output;
}
?>