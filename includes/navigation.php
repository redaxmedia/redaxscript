<?php

/**
 * navigation list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 *
 * @param string $table
 * @param array $options
 */

function navigation_list($table, $options)
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('navigationStart');

	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			if ($key !== 'language')
			{
				$key = 'option_' . $key;
				$$key = $value;
			}
		}
	}

	/* fallback */

	if (!$option_order)
	{
		$option_order = Redaxscript\Db::getSetting('order');
	}
	if (!$option_limit)
	{
		$option_limit = Redaxscript\Db::getSetting('limit');
	}

	/* switch table */

	switch ($table)
	{
		case 'categories':
			$wording_single = 'category';
			$query_parent = 'parent';
			break;
		case 'articles':
			$wording_single = 'article';
			$query_parent = 'category';
			break;
		case 'comments':
			$wording_single = 'comment';
			$query_parent = 'article';
			break;
	}

	/* query contents */

	$contents = Redaxscript\Db::forTablePrefix($table)
		->where('status', 1)
		->whereLanguageIs($registry->get('language'));

	/* setup parent */

	if ($query_parent)
	{
		if ($option_parent)
		{
			$contents->where($query_parent, $option_parent);
		}
		else if ($table == 'categories')
		{
			$contents->whereNull($query_parent);
		}
	}

	/* setup query filter */

	if ($table == 'categories' || $table == 'articles')
	{
		/* setup filter alias option */

		if ($option_filter_alias)
		{
			$contents->whereIn('alias', $option_filter_alias);
		}

		/* setup filter rank option */

		if ($option_filter_rank)
		{
			$contents->whereIn('rank', $option_filter_rank);
		}
	}

	/* setup rank and limit */

	if ($option_order === 'asc')
	{
		$contents->orderByAsc('rank');
	}
	else
	{
		$contents->orderByDesc('rank');
	}
	$contents->limit($option_limit);

	/* query result */

	$result = $contents->findArray();
	$num_rows = count($result);
	if (!$result || !$num_rows)
	{
		$error = $language->get($wording_single . '_no') . $language->get('point');
	}
	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, $registry->get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						if ($key !== 'language')
						{
							$$key = stripslashes($value);
						}
					}
				}

				/* build class string */

				if ($registry->get('lastParameter') == $alias && $table != 'comments')
				{
					$class_string = ' class="rs-item-active"';
				}
				else
				{
					$class_string = null;
				}

				/* prepare metadata */

				if ($table == 'comments')
				{
					$description = $title = $author . $language->get('colon') . ' ' . strip_tags($text);
				}
				if (!$description)
				{
					$description = $title;
				}

				/* build route */

				if ($table == 'categories' && $parent == 0 || $table == 'articles' && $category == 0)
				{
					$route = $alias;
				}
				else
				{
					$route = build_route($table, $id);
				}

				/* collect item output */

				$output .= '<li' . $class_string . '><a href="' . $registry->get('parameterRoute') . $route . '">' . $title . '</a>';

				/* collect children list output */

				if ($table == 'categories' && $option_children == 1)
				{
					ob_start();
					navigation_list($table,
					[
						'parent' => $id,
						'class' => 'rs-list-children'
					]);
					$output .= ob_get_clean();
				}
				$output .= '</li>';
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = $language->get('access_no') . $language->get('point');
		}
	}

	/* build id string */

	if ($option_id)
	{
		$id_string = ' id="' . $option_id . '"';
	}

	/* build class string */

	if ($option_class)
	{
		$class_string = ' class="' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="rs-list-' . $table . '"';
	}

	/* handle error */

	if ($error && !$option_parent)
	{
		$output = '<ul' . $id_string . $class_string . '><li><span>' . $error . '</span></li></ul>';
	}

	/* else collect list output */

	else if ($output)
	{
		$output = '<ul' . $id_string . $class_string . '>' . $output . '</ul>';
	}
	$output .= Redaxscript\Module\Hook::trigger('navigationEnd');
	echo $output;
}

/**
 * languages list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 *
 * @param array $options
 */

function languages_list($options)
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();

	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			if ($key !== 'language')
			{
				$key = 'option_' . $key;
				$$key = $value;
			}
		}
	}

	/* languages directory */

	$languages_directory = new Redaxscript\Filesystem\Filesystem();
	$languages_directory->init('languages');
	$languages_directory_array = $languages_directory->getSortArray();

	/* collect languages output */

	foreach ($languages_directory_array as $value)
	{
		$value = substr($value, 0, 2);
		$class_string = null;
		if ($value == $registry->get('language'))
		{
			$class_string = ' class="rs-item-active"';
		}
		$output .= '<li' . $class_string . '><a href="' . $registry->get('parameterRoute') . $registry->get('fullRoute') . $registry->get('languageRoute') . $value . '" rel="nofollow">' . $language->get($value, '_index') . '</a>';
	}

	/* build id string */

	if ($option_id)
	{
		$id_string = ' id="' . $option_id . '"';
	}

	/* build class string */

	if ($option_class)
	{
		$class_string = ' class="' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="rs-list-languages"';
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul' . $id_string . $class_string . '>' . $output . '</ul>';
	}
	echo $output;
}

/**
 * templates list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 *
 * @param array $options
 */

function templates_list($options)
{
	$registry = Redaxscript\Registry::getInstance();

	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			if ($key !== 'language')
			{
				$key = 'option_' . $key;
				$$key = $value;
			}
		}
	}

	/* templates directory */

	$templates_directory = new Redaxscript\Filesystem\Filesystem();
	$templates_directory->init('templates', false,
	[
		'admin',
		'console',
		'install'
	]);
	$templates_directory_array = $templates_directory->getSortArray();

	/* collect templates output */

	foreach ($templates_directory_array as $value)
	{
		$class_string = null;
		if ($value == $registry->get('template'))
		{
			$class_string = ' class="rs-item-active"';
		}
		$output .= '<li' . $class_string . '><a href="' . $registry->get('parameterRoute') . $registry->get('fullRoute') . $registry->get('templateRoute') . $value . '" rel="nofollow">' . $value . '</a>';
	}

	/* build id string */

	if ($option_id)
	{
		$id_string = ' id="' . $option_id . '"';
	}

	/* build class string */

	if ($option_class)
	{
		$class_string = ' class="' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="rs-list-templates"';
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul' . $id_string . $class_string . '>' . $output . '</ul>';
	}
	echo $output;
}
