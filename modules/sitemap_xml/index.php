<?php

/* sitemap xml render start */

function sitemap_xml_render_start()
{
	if (FIRST_PARAMETER == 'sitemap_xml')
	{
		header('content-type: application/xml');
		sitemap_xml();
		define('RENDER_BREAK', 1);
	}
}

/* sitemap xml */

function sitemap_xml()
{
	/* query categories */

	$categories_query = 'SELECT id, alias, parent FROM ' . PREFIX . 'categories WHERE status = 1 && access = 0 ORDER BY rank ASC';
	$categories_result = mysql_query($categories_query);

	/* collect output */

	$output = '<?xml version="1.0" encoding="' . s('charset') . '"?>' . PHP_EOL;
	$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
	$output .= '<url><loc>' . ROOT . '</loc><lastmod>' . TODAY . '</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>' . PHP_EOL;
	if ($categories_result)
	{
		while ($r = mysql_fetch_assoc($categories_result))
		{
			if ($r)
			{
				foreach ($r as $key => $value)
				{
					$$key = stripslashes($value);
				}
			}

			/* build string */

			if ($parent == 0)
			{
				$string = $alias;
			}
			else
			{
				$string = build_string('categories', $id);
			}

			/* collect output */

			$output .= '<url><loc>' . ROOT . '/' . REWRITE_STRING . $string . '</loc><lastmod>' . TODAY . '</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>' . PHP_EOL;
		}
	}

	/* query articles */

	$articles_query = 'SELECT id, alias, category FROM ' . PREFIX . 'articles WHERE status = 1 && access = 0 ORDER BY rank ASC';
	$articles_result = mysql_query($articles_query);

	/* collect output */

	if ($articles_result)
	{
		while ($r = mysql_fetch_assoc($articles_result))
		{
			if ($r)
			{
				foreach ($r as $key => $value)
				{
					$$key = stripslashes($value);
				}
			}

			/* build string */

			if ($category == 0)
			{
				$string = $alias;
			}
			else
			{
				$string = build_string('articles', $id);
			}
			$output .= '<url><loc>' . ROOT . '/' . REWRITE_STRING . $string . '</loc><lastmod>' . TODAY . '</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>' . PHP_EOL;
		}
	}
	$output .= '</urlset>';
	echo $output;
}
?>