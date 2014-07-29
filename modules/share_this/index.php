<?php

/**
 * share this loader start
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function share_this_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/share_this/styles/share_this.css';
	$loader_modules_scripts[] = 'modules/share_this/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/share_this/scripts/share_this.js';
}

/**
 * share this article end
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
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
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 *
 * @return string
 */

function share_this($route = '')
{
	$code = 'target="_blank" rel="nofollow"';
	$networks = array(
		'facebook' => array(
			'url' => 'http://facebook.com/sharer.php?u='
		),
		'googleplusone' => array(
			'url' => 'http://plusone.google.com/_/+1/confirm?url='
		),
		'twitter' => array(
			'url' => 'http://twitter.com/share?url=',
			'code' => ' data-height="340"'
		),
		'pinterest' => array(
			'url' => 'http://pinterest.com/pin/create/button/?url='
		),
		'linkedin' => array(
			'url' => 'http://linkedin.com/shareArticle?url=',
			'code' => ' data-height="490" data-width="850"'
		),
		'stumbleupon' => array(
			'url' => 'http://stumbleupon.com/submit?url='
		),
		'delicious' => array(
			'url' => 'http://del.icio.us/post?url=',
			'code' => ' data-height="580"'
		)
	);

	/* collect output */

	if ($route)
	{
		$output = '<ul class="list_share_this clear_fix">';

		/* handle each network */

		foreach ($networks as $key => $value)
		{
			$output .= '<li>' . anchor_element('external', '', 'js_link_share_this link_share_this link_' . $key, ucfirst($key), $value['url'] . $route, '', $code . ' data-type="' . $key . '"' . $value['code']) . '</li>';
		}
		$output .= '</ul>';
		return $output;
	}
}

