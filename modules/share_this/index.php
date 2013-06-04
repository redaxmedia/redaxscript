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
	$code = 'rel="nofollow" target="_blank"';

	/* collect output */

	if ($route)
	{
		$output = '<div class="wrapper_share_this clear_fix"><ul class="list_share_this">';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_facebook', 'Facebook', 'facebook.com/sharer.php?u=' . $route, '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_google', 'Google', 'plusone.google.com/_/+1/confirm?url=' . $route, '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_twitter', 'Twitter', 'twitter.com/share?url=' . $route, '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_pinterest', 'Pinterest', 'pinterest.com/pin/create/button/?url=' . $route, '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_tumblr', 'Tumblr', 'tumblr.com/share', '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_linkedin', 'Linkedin', 'linkedin.com/shareArticle?url=' . $route, '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_stumbleupon', 'Stumbleupon', 'stumbleupon.com/submit?url=' . $route, '', $code) . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_delicious', 'Delicious', 'del.icio.us/post?url=' . $route, '', $code) . '</li>';
		$output .= '</ul></div>';
		return $output;
	}
}
?>