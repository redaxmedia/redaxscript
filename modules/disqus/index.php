<?php

/**
 * disqus loader start
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function disqus_loader_start()
{
	if (ARTICLE)
	{
		global $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/disqus/scripts/startup.js';
	}
}

/**
 * disqus render start
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function disqus_render_start()
{
	if (ARTICLE)
	{
		define('COMMENTS_REPLACE', 1);
	}
}

/**
 * disqus comments replace
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function disqus_comments_replace()
{
	$output = DISQUS_TARGET . PHP_EOL;
	$output .= '<script src="' . DISQUS_EMBED_URL . '"></script>' . PHP_EOL;
	echo $output;
}
?>