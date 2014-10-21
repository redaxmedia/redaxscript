<?php

/**
 * sitemap xml render start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_xml_render_start()
{
	if (FIRST_PARAMETER == 'sitemap_xml')
	{
		header('content-type: application/xml');
		sitemap_xml();
		define('RENDER_BREAK', 1);
	}
}

/**
 * sitemap xml
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_xml()
{
	/* query categories */

	$categories_query = 'SELECT id, alias, parent FROM ' . PREFIX . 'categories WHERE status = 1 && access = 0 ORDER BY rank ASC';
	$categories_result = Redaxscript\Db::forPrefixTable('categories')->rawQuery($categories_query)->findArray();

	/* collect output */

	$output = '<?xml version="1.0" encoding="' . s('charset') . '"?>' . PHP_EOL;
	$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
	$output .= '<url><loc>' . ROOT . '</loc><lastmod>' . TODAY . '</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>' . PHP_EOL;
	if ($categories_result)
	{
		foreach ($categories_result as $r)
		{
			if ($r)
			{
				foreach ($r as $key => $value)
				{
					$$key = stripslashes($value);
				}
			}

			/* build route */

			if ($parent == 0)
			{
				$route = $alias;
			}
			else
			{
				$route = build_route('categories', $id);
			}

			/* collect output */

			$output .= '<url><loc>' . ROOT . '/' . REWRITE_ROUTE . $route . '</loc><lastmod>' . TODAY . '</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>' . PHP_EOL;
		}
	}

	/* query articles */

	$articles_query = 'SELECT id, alias, category FROM ' . PREFIX . 'articles WHERE status = 1 && access = 0 ORDER BY rank ASC';
	$articles_result = Redaxscript\Db::forPrefixTable('categories')->rawQuery($articles_query)->findArray();

	/* collect output */

	if ($articles_result)
	{
		foreach ($articles_result as $r)
		{
			if ($r)
			{
				foreach ($r as $key => $value)
				{
					$$key = stripslashes($value);
				}
			}

			/* build route */

			if ($category == 0)
			{
				$route = $alias;
			}
			else
			{
				$route = build_route('articles', $id);
			}
			$output .= '<url><loc>' . ROOT . '/' . REWRITE_ROUTE . $route . '</loc><lastmod>' . TODAY . '</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>' . PHP_EOL;
		}
	}
	$output .= '</urlset>';
	echo $output;
}
