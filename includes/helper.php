<?php

/**
 * helper class
 *
 * @since 2.0
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Helper
 * @author Henry Ruhs
 */

function helper_class()
{
	$output = MY_BROWSER . MY_BROWSER_VERSION;
	if (MY_ENGINE)
	{
		if ($output)
		{
			$output .= ' ';
		}
		$output .= MY_ENGINE;
	}
	if ($output)
	{
		$output .= ' ';
	}
	if (MY_MOBILE)
	{
		$output .= 'mobile';
		if (MY_MOBILE != 'mobile')
		{
			$output .= ' ' . MY_MOBILE;
		}
	}
	else if (MY_TABLET)
	{
		$output .= 'tablet';
		if (MY_TABLET != 'tablet')
		{
			$output .= ' ' . MY_TABLET;
		}
	}
	else if (MY_DESKTOP)
	{
		$output .= 'desktop ' . MY_DESKTOP;
	}
	if (LANGUAGE == 'ar' || LANGUAGE == 'fa' || LANGUAGE == 'he')
	{
		if ($output)
		{
			$output .= ' ';
		}
		$output .= 'rtl';
	}
	echo $output;
}

/**
 * helper subset
 *
 * @since 2.0
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Helper
 * @author Henry Ruhs
 */

function helper_subset()
{
	if (LANGUAGE == 'bg' || LANGUAGE == 'ru')
	{
		$output = 'cyrillic';
	}
	else if (LANGUAGE == 'vi')
	{
		$output = 'vietnamese';
	}
	else
	{
		$output = 'latin';
	}
	echo $output;
}
?>
