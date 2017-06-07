<?php

/**
 * admin contents list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_contents_list()
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminContentListStart');

	/* define access variables */

	$tableParameter = $registry->get('tableParameter');
	$table_new = $registry->get('tableNew');
	if ($tableParameter == 'comments')
	{
		$articles_total = Redaxscript\Db::forTablePrefix('articles')->count();
		$articles_comments_disable = Redaxscript\Db::forTablePrefix('articles')->where('comments', 0)->count();
		if ($articles_total == $articles_comments_disable)
		{
			$table_new = 0;
		}
	}

	/* switch table */

	switch ($tableParameter)
	{
		case 'categories':
			$wording_single = 'category';
			$wording_parent = 'category_parent';
			break;
		case 'articles':
			$wording_single = 'article';
			$wording_parent = 'category';
			break;
		case 'extras':
			$wording_single = 'extra';
			break;
		case 'comments':
			$wording_single = 'comment';
			$wording_parent = 'article';
			break;
	}

	/* query contents */

	$result = Redaxscript\Db::forTablePrefix($tableParameter)->orderByAsc('rank')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get($tableParameter) . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($table_new == 1)
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/new/' . $registry->get('tableParameter') . '" class="rs-admin-button-default rs-admin-button-create">' . $language->get($wording_single . '_new') . '</a>';
	}
	if ($registry->get('tableEdit') == 1 && $num_rows)
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/sort/' . $registry->get('tableParameter') . '/' . $registry->get('token') . '" class="rs-admin-button-default">' . $language->get('sort') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-' . $wording_single . '">';

	/* collect thead */

	$output .= '<thead><tr><th class="rs-admin-col-title">' . $language->get('title') . '</th><th class="rs-admin-col-alias">';
	if ($tableParameter == 'comments')
	{
		$output .= $language->get('identifier');
	}
	else
	{
		$output .= $language->get('alias');
	}
	$output .= '</th>';
	if ($tableParameter != 'extras')
	{
		$output .= '<th class="rs-admin-col-parent">' . $language->get($wording_parent) . '</th>';
	}
	$output .= '<th class="rs-admin-col-rank">' . $language->get('rank') . '</th></tr></thead>';

	/* collect tfoot */

	$output .= '<tfoot><tr><td>' . $language->get('title') . '</td><td>';
	if ($tableParameter == 'comments')
	{
		$output .= $language->get('identifier');
	}
	else
	{
		$output .= $language->get('alias');
	}
	$output .= '</td>';
	if ($tableParameter != 'extras')
	{
		$output .= '<td>' . $language->get($wording_parent) . '</td>';
	}
	$output .= '<td class="rs-admin-col-rank">' . $language->get('rank') . '</td></tr></tfoot>';
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
							if ($key !== 'language')
							{
								$$key = stripslashes($value);
							}
						}
					}
				}

				/* prepare name */

				if ($tableParameter == 'comments')
				{
					$name = $author . $language->get('colon') . ' ' . strip_tags($text);
				}
				else
				{
					$name = $title;
				}

				/* build class string */

				if ($status == 1)
				{
					$class_status = null;
				}
				else
				{
					$class_status = 'rs-admin-is-disabled';
				}

				/* build route */

				if ($tableParameter != 'extras' && $status == 1)
				{
					if ($tableParameter == 'categories' && $parent == 0 || $tableParameter == 'articles' && $category == 0)
					{
						$route = $alias;
					}
					else
					{
						$route = build_route($tableParameter, $id);
					}
				}
				else
				{
					$route = null;
				}

				/* collect tbody output */

				if ($tableParameter == 'categories')
				{
					if ($before != $parent)
					{
						$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="4">';
						if ($parent)
						{
							$output .= Redaxscript\Db::forTablePrefix('categories')->where('id', $parent)->findOne()->title;
						}
						else
						{
							$output .= $language->get('none');
						}
						$output .= '</td></tr>';
					}
					$before = $parent;
				}
				if ($tableParameter == 'articles')
				{
					if ($before != $category)
					{
						$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="4">';
						if ($category)
						{
							$output .= Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
						}
						else
						{
							$output .= $language->get('uncategorized');
						}
						$output .= '</td></tr>';
					}
					$before = $category;
				}
				if ($tableParameter == 'comments')
				{
					if ($before != $article)
					{
						$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="4">';
						if ($article)
						{
							$output .= Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
						}
						else
						{
							$output .= $language->get('none');
						}
						$output .= '</td></tr>';
					}
					$before = $article;
				}

				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				if ($class_status)
				{
					$output .= ' class="' . $class_status . '"';
				}
				$output .= '><td>';

				if ($status == 1)
				{
					$output .= '<a href="' . $registry->get('parameterRoute') . $route . '" class="rs-admin-link-view';
					if ($r['language'])
					{
						$output .= ' rs-admin-has-language" data-language="' . $r['language'];
					}
					$output .= '">' . $name . '</a>';
				}
				else
				{
					$output .= $name;
				}

				/* collect control output */

				$output .= admin_control('contents', $tableParameter, $id, $alias, $status, $registry->get('tableNew'), $registry->get('tableEdit'), $registry->get('tableDelete'));

				/* collect alias and id output */

				$output .= '</td><td>';
				if ($tableParameter == 'comments')
				{
					$output .= $id;
				}
				else
				{
					$output .= $alias;
				}
				$output .= '</td>';

				/* collect parent output */

				if ($tableParameter != 'extras')
				{
					$output .= '<td>';
					if ($tableParameter == 'categories')
					{
						if ($parent)
						{
							$parent_title = Redaxscript\Db::forTablePrefix('categories')->where('id', $parent)->findOne()->title;
							$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/categories/' . $parent . '" class="rs-admin-link-parent">' . $parent_title . '</a>';
						}
						else
						{
							$output .= $language->get('none');
						}
					}
					if ($tableParameter == 'articles')
					{
						if ($category)
						{
							$category_title = Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
							$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/categories/' . $category . '" class="rs-admin-link-parent">' . $category_title . '</a>';
						}
						else
						{
							$output .= $language->get('uncategorized');
						}
					}
					if ($tableParameter == 'comments')
					{
						if ($article)
						{
							$article_title = Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
							$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/articles/' . $article . '" class="rs-admin-link-parent">' . $article_title . '</a>';
						}
						else
						{
							$output .= $language->get('none');
						}
					}
					$output .= '</td>';
				}
				$output .= '<td class="rs-admin-col-rank">';

				/* collect control output */

				if ($registry->get('tableEdit') == 1)
				{
					$rank_desc = Redaxscript\Db::forTablePrefix($tableParameter)->max('rank');
					if ($rank > 1)
					{
						$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/up/' . $registry->get('tableParameter') . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-button-moveup">' . $language->get('up') . '</a>';
					}
					else
					{
						$output .= '<a class="rs-admin-button-moveup rs-admin-is-disabled">' . $language->get('up') . '</a>';
					}
					if ($rank < $rank_desc)
					{
						$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/down/' . $registry->get('tableParameter') . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-button-movedown">' . $language->get('down') . '</a>';
					}
					else
					{
						$output .= '<a class="rs-admin-button-movedown rs-admin-is-disabled">' . $language->get('down') . '</a>';
					}
					$output .= '</td>';
				}
				$output .= '</tr>';

				/* collect tbody output */

				if ($tableParameter == 'categories')
				{
					if ($before != $parent)
					{
						$output .= '</tbody>';
					}
				}
				if ($tableParameter == 'articles')
				{
					if ($before != $category)
					{
						$output .= '</tbody>';
					}
				}
				if ($tableParameter == 'comments')
				{
					if ($before != $article)
					{
						$output .= '</tbody>';
					}
				}
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

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="4">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Module\Hook::trigger('adminContentListEnd');
	echo $output;
}

/**
 * admin groups list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_groups_list()
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminGroupListStart');

	/* query groups */

	$result = Redaxscript\Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get('groups') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($registry->get('groupsNew'))
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/new/groups" class="rs-admin-button-default rs-admin-button-create">' . $language->get('group_new') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-default-group">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . $language->get('name') . '</th><th class="rs-admin-col-alias">' . $language->get('alias') . '</th><th class="rs-admin-col-filter">' . $language->get('filter') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . $language->get('name') . '</td><td>' . $language->get('alias') . '</td><td>' . $language->get('filter') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = $language->get('group_no') . $language->get('point');
	}
	else if ($result)
	{
		$output .= '<tbody>';
		foreach ($result as $r)
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

			if ($status == 1)
			{
				$class_status = null;
			}
			else
			{
				$class_status = 'rs-admin-is-disabled';
			}

			/* collect table row */

			$output .= '<tr';
			if ($alias)
			{
				$output .= ' id="' . $alias . '"';
			}
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td>' . $name;

			/* collect control output */

			$output .= admin_control('access', 'groups', $id, $alias, $status, $registry->get('tableNew'), $registry->get('tableEdit'), $registry->get('tableDelete'));

			/* collect alias and filter output */

			$output .= '</td><td>' . $alias . '</td><td>' . $filter . '</td></tr>';
		}
		$output .= '</tbody>';
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Module\Hook::trigger('adminGroupListEnd');
	echo $output;
}

/**
 * admin users list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_users_list()
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminUserListStart');

	/* query users */

	$result = Redaxscript\Db::forTablePrefix('users')->orderByDesc('last')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get('users') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($registry->get('usersNew'))
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/new/users" class="rs-admin-button-default rs-admin-button-create">' . $language->get('user_new') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-user">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . $language->get('name') . '</th><th class="rs-admin-col-user">' . $language->get('user') . '</th><th class="rs-admin-col-group">' . $language->get('groups') . '</th><th class="rs-admin-col-session">' . $language->get('session') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . $language->get('name') . '</td><td>' . $language->get('user') . '</td><td>' . $language->get('groups') . '</td><td>' . $language->get('session') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = $language->get('user_no') . $language->get('point');
	}
	else if ($result)
	{
		$output .= '<tbody>';
		foreach ($result as $r)
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

			if ($status == 1)
			{
				$class_status = null;
			}
			else
			{
				$class_status = 'rs-admin-is-disabled';
			}

			/* collect table row */

			$output .= '<tr';
			if ($user)
			{
				$output .= ' id="' . $user . '"';
			}
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td>';
			if ($r['language'])
			{
				$output .= '<span class="rs-admin-has-language" data-language="' . $r['language'] . '">';
			}
			$output .= $name;
			if ($r['language'])
			{
				$output .= '</span>';
			}

			/* collect control output */

			$output .= admin_control('access', 'users', $id, $alias, $status, $registry->get('tableNew'), $registry->get('tableEdit'), $registry->get('tableDelete'));

			/* collect user and parent output */

			$output .= '</td><td>' . $user . '</td><td>';
			if ($groups)
			{
				$groups_array = array_filter(explode(', ', $groups));
				$groups_array_keys = array_keys($groups_array);
				$groups_array_last = end($groups_array_keys);
				foreach ($groups_array as $key => $value)
				{
					$group_alias = Redaxscript\Db::forTablePrefix('groups')->where('id', $value)->findOne()->alias;
					if ($group_alias)
					{
						$group_name = Redaxscript\Db::forTablePrefix('groups')->where('id', $value)->findOne()->name;
						$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/groups/' . $value . '" class="rs-admin-link-parent">' . $group_name . '</a>';
						if ($groups_array_last != $key)
						{
							$output .= ', ';
						}
					}
				}
			}
			else
			{
				$output .= $language->get('none');
			}
			$output .= '</td><td>';
			if ($first == $last)
			{
				$output .= $language->get('none');
			}
			else
			{
				$minute_ago = date('Y-m-d H:i:s', strtotime('-1 minute'));
				$day_ago = date('Y-m-d H:i:s', strtotime('-1 day'));
				if ($last > $minute_ago)
				{
					$output .= $language->get('online');
				}
				else if ($last > $day_ago)
				{
					$time = date(Redaxscript\Db::getSetting('time'), strtotime($last));
					$output .= $language->get('today') . ' ' . $language->get('at') . ' ' . $time;
				}
				else
				{
					$date = date(Redaxscript\Db::getSetting('date'), strtotime($last));
					$output .= $date;
				}
			}
			$output .= '</td></tr>';
		}
		$output .= '</tbody>';
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Module\Hook::trigger('adminUserListEnd');
	echo $output;
}

/**
 * admin modules list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_modules_list()
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminModuleListStart');

	/* query modules */

	$result = Redaxscript\Db::forTablePrefix('modules')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get('modules') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-module">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . $language->get('name') . '</th><th class="rs-admin-col-alias">' . $language->get('alias') . '</th><th class="rs-admin-col-version">' . $language->get('version') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . $language->get('name') . '</td><td>' . $language->get('alias') . '</td><td>' . $language->get('version') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = $language->get('module_no') . $language->get('point');
	}
	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$output .= '<tbody>';
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
				$modules_installed_array[] = $alias;

				/* build class string */

				if ($status == 1)
				{
					$class_status = null;
				}
				else
				{
					$class_status = 'rs-admin-is-disabled';
				}

				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				if ($class_status)
				{
					$output .= ' class="' . $class_status . '"';
				}
				$output .= '><td>' . $name;

				/* collect control output */

				$output .= admin_control('modules_installed', 'modules', $id, $alias, $status, $registry->get('tableInstall'), $registry->get('tableEdit'), $registry->get('tableUninstall'));

				/* collect alias and version output */

				$output .= '</td><td>' . $alias . '</td><td>' . $version . '</td></tr>';
			}
			else
			{
				$counter++;
			}
		}
		$output .= '</tbody>';

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = $language->get('access_no') . $language->get('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}

	/* modules not installed */

	if ($registry->get('modulesInstall') == 1)
	{
		/* modules directory */

		$modules_directory = new Redaxscript\Filesystem\Filesystem();
		$modules_directory->init('modules');
		$modules_directory_array = $modules_directory->getSortArray();
		if ($modules_directory_array && $modules_installed_array)
		{
			$modules_not_installed_array = array_diff($modules_directory_array, $modules_installed_array);
		}
		else if ($modules_directory_array)
		{
			$modules_not_installed_array = $modules_directory_array;
		}
		if ($modules_not_installed_array)
		{
			$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="3">' . $language->get('install') . '</td></tr>';
			foreach ($modules_not_installed_array as $alias)
			{
				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				$output .= '><td colspan="3">' . $alias;

				/* collect control output */

				$output .= admin_control('modules_not_installed', 'modules', $id, $alias, $status, $registry->get('tableInstall'), $registry->get('tableEdit'), $registry->get('tableUninstall'));
				$output .= '</td></tr>';
			}
			$output .= '</tbody>';
		}
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Module\Hook::trigger('adminModuleListEnd');
	echo $output;
}