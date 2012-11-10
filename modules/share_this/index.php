<?php

/* share this loader start */

function share_this_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/share_this/styles/share_this.css';
}

/* share this article end */

function share_this_article_end()
{
	if (LAST_TABLE == 'articles')
	{
		$string = ROOT . '/' . REWRITE_ROUTE . FULL_ROUTE;
		$output = share_this($string);
		return $output;
	}
}

/* share this */

function share_this($string = '')
{
	if ($string)
	{
		$output = '<div class="wrapper_share_this clear_fix"><ul class="list_share_this">';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_facebook', 'Facebook', 'http://facebook.com/sharer.php?u=' . $string, '', 'rel="nofollow" target="_blank"') . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_twitter', 'Twitter', 'http://twitter.com/share?url=' . $string, '', 'rel="nofollow" target="_blank"') . '</li>';
		$output .= '<li>' . anchor_element('external', '', 'link_share_this link_google', 'Google', 'http://plusone.google.com/_/+1/confirm?url=' . $string, '', 'rel="nofollow" target="_blank"') . '</li>';
		$output .= '</ul></div>';
		return $output;
	}
}
?>