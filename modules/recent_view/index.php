<?php

/**
 * recent view
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 *
 * @param integer $limit
 * @return string
 */

function recent_view($limit = '')
{
	$recent_view_log = recent_view_logger();
	if ($recent_view_log)
	{
		$output = '<ul class="list_recent_view">';
		foreach ($recent_view_log as $value)
		{
			/* break if limit reached */

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

/**
 * recent view logger
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function recent_view_logger()
{
	if (FULL_ROUTE)
	{
		if (end($_SESSION[ROOT . '/recent_view']) != FULL_ROUTE)
		{
			$_SESSION[ROOT . '/recent_view'][] = FULL_ROUTE;
		}
	}
	$output = array_reverse($_SESSION[ROOT . '/recent_view']);
	return $output;
}

