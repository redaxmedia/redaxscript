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
	$output = Redaxscript\Hook::trigger('adminContentListStart');

	/* define access variables */

	$tableParameter = Redaxscript\Registry::get('tableParameter');
	$table_new = Redaxscript\Registry::get('tableNew');
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

	$output .= '<h2 class="rs-admin-title-content">' . Redaxscript\Language::get($tableParameter) . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($table_new == 1)
	{
		$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/' . Redaxscript\Registry::get('tableParameter') . '" class="rs-admin-button-default rs-admin-button-plus">' . Redaxscript\Language::get($wording_single . '_new') . '</a>';
	}
	if (Redaxscript\Registry::get('tableEdit') == 1 && $num_rows)
	{
		$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/sort/' . Redaxscript\Registry::get('tableParameter') . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-button-default rs-admin-button-sort">' . Redaxscript\Language::get('sort') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-' . $wording_single . '">';

	/* collect thead */

	$output .= '<thead><tr><th class="rs-admin-col-title">' . Redaxscript\Language::get('title') . '</th><th class="rs-admin-col-alias">';
	if ($tableParameter == 'comments')
	{
		$output .= Redaxscript\Language::get('identifier');
	}
	else
	{
		$output .= Redaxscript\Language::get('alias');
	}
	$output .= '</th>';
	if ($tableParameter != 'extras')
	{
		$output .= '<th class="rs-admin-col-parent">' . Redaxscript\Language::get($wording_parent) . '</th>';
	}
	$output .= '<th class="rs-admin-col-rank">' . Redaxscript\Language::get('rank') . '</th></tr></thead>';

	/* collect tfoot */

	$output .= '<tfoot><tr><td>' . Redaxscript\Language::get('title') . '</td><td>';
	if ($tableParameter == 'comments')
	{
		$output .= Redaxscript\Language::get('identifier');
	}
	else
	{
		$output .= Redaxscript\Language::get('alias');
	}
	$output .= '</td>';
	if ($tableParameter != 'extras')
	{
		$output .= '<td>' . Redaxscript\Language::get($wording_parent) . '</td>';
	}
	$output .= '<td class="rs-admin-col-rank">' . Redaxscript\Language::get('rank') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = Redaxscript\Language::get($wording_single . '_no') . Redaxscript\Language::get('point');
	}
	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, Redaxscript\Registry::get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}

				/* prepare name */

				if ($tableParameter == 'comments')
				{
					$name = $author . Redaxscript\Language::get('colon') . ' ' . strip_tags($text);
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
							$output .= Redaxscript\Language::get('none');
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
							$output .= Redaxscript\Language::get('uncategorized');
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
							$output .= Redaxscript\Language::get('none');
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
				if ($language)
				{
					$output .= '<img class="rs-admin-image-flag rs-admin-is-' . $language . '" title="' . Redaxscript\Language::get($language) . '" />';
				}
				if ($status == 1)
				{
					$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . $route . '" class="rs-admin-link-default">' . $name . '</a>';
				}
				else
				{
					$output .= $name;
				}

				/* collect control output */

				$output .= admin_control('contents', $tableParameter, $id, $alias, $status, Redaxscript\Registry::get('tableNew'), Redaxscript\Registry::get('tableEdit'), Redaxscript\Registry::get('tableDelete'));

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
							$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/categories/' . $parent . '" class="rs-admin-link-default">' . $parent_title . '</a>';
						}
						else
						{
							$output .= Redaxscript\Language::get('none');
						}
					}
					if ($tableParameter == 'articles')
					{
						if ($category)
						{
							$category_title = Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
							$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/categories/' . $category . '" class="rs-admin-link-default">' . $category_title . '</a>';
						}
						else
						{
							$output .= Redaxscript\Language::get('uncategorized');
						}
					}
					if ($tableParameter == 'comments')
					{
						if ($article)
						{
							$article_title = Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
							$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/articles/' . $article . '" class="rs-admin-link-default">' . $article_title . '</a>';
						}
						else
						{
							$output .= Redaxscript\Language::get('none');
						}
					}
					$output .= '</td>';
				}
				$output .= '<td class="rs-admin-col-rank">';

				/* collect control output */

				if (Redaxscript\Registry::get('tableEdit') == 1)
				{
					$rank_desc = Redaxscript\Db::forTablePrefix($tableParameter)->max('rank');
					if ($rank > 1)
					{
						$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/up/' . Redaxscript\Registry::get('tableParameter') . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-button-moveup">' . Redaxscript\Language::get('up') . '</a>';
					}
					else
					{
						$output .= '<a class="rs-admin-button-moveup rs-admin-is-disabled">' . Redaxscript\Language::get('up') . '</a>';
					}
					if ($rank < $rank_desc)
					{
						$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/down/' . Redaxscript\Registry::get('tableParameter') . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-button-movedown">' . Redaxscript\Language::get('down') . '</a>';
					}
					else
					{
						$output .= '<a class="rs-admin-button-movedown rs-admin-is-disabled">' . Redaxscript\Language::get('down') . '</a>';
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
			$error = Redaxscript\Language::get('access_no') . Redaxscript\Language::get('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="4">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Hook::trigger('adminContentListEnd');
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
	$output = Redaxscript\Hook::trigger('adminGroupListStart');

	/* query groups */

	$result = Redaxscript\Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . Redaxscript\Language::get('groups') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if (Redaxscript\Registry::get('groupsNew'))
	{
		$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/groups" class="rs-admin-button-default rs-admin-button-plus">' . Redaxscript\Language::get('group_new') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-default-group">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . Redaxscript\Language::get('name') . '</th><th class="rs-admin-col-alias">' . Redaxscript\Language::get('alias') . '</th><th class="rs-admin-col-filter">' . Redaxscript\Language::get('filter') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . Redaxscript\Language::get('name') . '</td><td>' . Redaxscript\Language::get('alias') . '</td><td>' . Redaxscript\Language::get('filter') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = Redaxscript\Language::get('group_no') . Redaxscript\Language::get('point');
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
					$$key = stripslashes($value);
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

			$output .= admin_control('access', 'groups', $id, $alias, $status, Redaxscript\Registry::get('tableNew'), Redaxscript\Registry::get('tableEdit'), Redaxscript\Registry::get('tableDelete'));

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
	$output .= Redaxscript\Hook::trigger('adminGroupListEnd');
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
	$output = Redaxscript\Hook::trigger('adminUserListStart');

	/* query users */

	$result = Redaxscript\Db::forTablePrefix('users')->orderByDesc('last')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . Redaxscript\Language::get('users') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if (Redaxscript\Registry::get('usersNew'))
	{
		$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/users" class="rs-admin-button-default rs-admin-button-plus">' . Redaxscript\Language::get('user_new') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-user">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . Redaxscript\Language::get('name') . '</th><th class="rs-admin-col-user">' . Redaxscript\Language::get('user') . '</th><th class="rs-admin-col-group">' . Redaxscript\Language::get('groups') . '</th><th class="rs-admin-col-session">' . Redaxscript\Language::get('session') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . Redaxscript\Language::get('name') . '</td><td>' . Redaxscript\Language::get('user') . '</td><td>' . Redaxscript\Language::get('groups') . '</td><td>' . Redaxscript\Language::get('session') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = Redaxscript\Language::get('user_no') . Redaxscript\Language::get('point');
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
					$$key = stripslashes($value);
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
			if ($language)
			{
				$output .= '<img class="rs-admin-image-flag rs-admin-is-' . $language . '" title="' . Redaxscript\Language::get($language) . '" />';
			}
			$output .= $name;

			/* collect control output */

			$output .= admin_control('access', 'users', $id, $alias, $status, Redaxscript\Registry::get('tableNew'), Redaxscript\Registry::get('tableEdit'), Redaxscript\Registry::get('tableDelete'));

			/* collect user and parent output */

			$output .= '</td><td>' . $user . '</td><td>';
			if ($groups)
			{
				$groups_array = explode(', ', $groups);
				$groups_array_keys = array_keys($groups_array);
				$groups_array_last = end($groups_array_keys);
				foreach ($groups_array as $key => $value)
				{
					$group_alias = Redaxscript\Db::forTablePrefix('groups')->where('id', $value)->findOne()->alias;
					if ($group_alias)
					{
						$group_name = Redaxscript\Db::forTablePrefix('groups')->where('id', $value)->findOne()->name;
						$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/groups/' . $value . '" class="rs-admin-link-default">' . $group_name . '</a>';
						if ($groups_array_last != $key)
						{
							$output .= ', ';
						}
					}
				}
			}
			else
			{
				$output .= Redaxscript\Language::get('none');
			}
			$output .= '</td><td>';
			if ($first == $last)
			{
				$output .= Redaxscript\Language::get('none');
			}
			else
			{
				$minute_ago = date('Y-m-d H:i:s', strtotime('-1 minute'));
				$day_ago = date('Y-m-d H:i:s', strtotime('-1 day'));
				if ($last > $minute_ago)
				{
					$output .= Redaxscript\Language::get('online');
				}
				else if ($last > $day_ago)
				{
					$time = date(Redaxscript\Db::getSetting('time'), strtotime($last));
					$output .= Redaxscript\Language::get('today') . ' ' . Redaxscript\Language::get('at') . ' ' . $time;
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
	$output .= Redaxscript\Hook::trigger('adminUserListEnd');
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
	$output = Redaxscript\Hook::trigger('adminModuleListStart');

	/* query modules */

	$result = Redaxscript\Db::forTablePrefix('modules')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . Redaxscript\Language::get('modules') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-module">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . Redaxscript\Language::get('name') . '</th><th class="rs-admin-col-alias">' . Redaxscript\Language::get('alias') . '</th><th class="rs-admin-col-version">' . Redaxscript\Language::get('version') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . Redaxscript\Language::get('name') . '</td><td>' . Redaxscript\Language::get('alias') . '</td><td>' . Redaxscript\Language::get('version') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = Redaxscript\Language::get('module_no') . Redaxscript\Language::get('point');
	}
	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$output .= '<tbody>';
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, Redaxscript\Registry::get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
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

				$output .= admin_control('modules_installed', 'modules', $id, $alias, $status, Redaxscript\Registry::get('tableInstall'), Redaxscript\Registry::get('tableEdit'), Redaxscript\Registry::get('tableUninstall'));

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
			$error = Redaxscript\Language::get('access_no') . Redaxscript\Language::get('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}

	/* modules not installed */

	if (Redaxscript\Registry::get('modulesInstall') == 1)
	{
		/* modules directory */

		$modules_directory = new Redaxscript\Directory();
		$modules_directory->init('modules');
		$modules_directory_array = $modules_directory->getArray();
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
			$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="3">' . Redaxscript\Language::get('install') . '</td></tr>';
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

				$output .= admin_control('modules_not_installed', 'modules', $id, $alias, $status, Redaxscript\Registry::get('tableInstall'), Redaxscript\Registry::get('tableEdit'), Redaxscript\Registry::get('tableUninstall'));
				$output .= '</td></tr>';
			}
			$output .= '</tbody>';
		}
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Hook::trigger('adminModuleListEnd');
	echo $output;
}