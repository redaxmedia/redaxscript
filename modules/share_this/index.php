<?php

/**
 * share this loader start
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
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
 * @category Modules
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
 * @category Modules
 * @author Henry Ruhs
 *
 * @return string
 */

function share_this($route = '')
{
	$code = 'target="_blank" rel="nofollow"';
	$networks = array(
		'facebook' => array(
			'url' => 'facebook.com/sharer.php?u=',
			'code' => ' data-height="280"'
		),
		'googleplusone' => array(
			'url' => 'plusone.google.com/_/+1/confirm?url='
		),
		'twitter' => array(
			'url' => 'twitter.com/share?url=',
			'code' => ' data-height="340"'
		),
		'pinterest' => array(
			'url' => 'pinterest.com/pin/create/button/?url=',
			'code' => ' data-height="500" data-width="800"'
		),
		'tumblr' => array(
			'url' => 'tumblr.com/share'
		),
		'linkedin' => array(
			'url' => 'linkedin.com/shareArticle?url=',
			'code' => ' data-width="850"'
		),
		'stumbleupon' => array(
			'url' => 'stumbleupon.com/submit?url=',
			'code' => ' data-width="850"'
		),
		'delicious' => array(
			'url' => 'del.icio.us/post?url=',
			'code' => ' data-height="380"'
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
?>