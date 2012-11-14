<?php

/**
 * share this loader start
 */

function share_this_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/share_this/styles/share_this.css';
}

/**
 * share this article end
 *
 * @return string
 */

function share_this_article_end()
{
	if (LAST_TABLE == 'articles')
	{
		$route = ROOT . '/' . REWRITE_ROUTE . FULL_ROUTE;
		$output = share_this($route);
		return $output;
	}
}

/**
 * share this
 *
 * @return string
 */

function share_this($route = '')
{
	if ($route)
	{
		$output = '<div class="wrapper_share_this clear_fix"><ul class="list_share_this">';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_facebook', 'Facebook', 'http://facebook.com/sharer.php?u=' . $route, '', 'rel="nofollow" target="_blank"') . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_twitter', 'Twitter', 'http://twitter.com/share?url=' . $route, '', 'rel="nofollow" target="_blank"') . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_google', 'Google', 'http://plusone.google.com/_/+1/confirm?url=' . $route, '', 'rel="nofollow" target="_blank"') . '</li>';
		$output .= '</ul></div>';
		return $output;
	}
}
?>