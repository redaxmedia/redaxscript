<?php

/**
 * admin panel list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_panel_list()
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminPanelStart');

	/* define access variables */

	if ($registry->get('categoriesNew') || $registry->get('categoriesEdit') || $registry->get('categoriesDelete'))
	{
		$categories_access = $contents_access = 1;
	}
	if ($registry->get('articlesNew') || $registry->get('articlesEdit') || $registry->get('articlesDelete'))
	{
		$articles_access = $contents_access = 1;
	}
	if ($registry->get('extrasNew') || $registry->get('extrasEdit') || $registry->get('extrasDelete'))
	{
		$extras_access = $contents_access = 1;
	}
	if ($registry->get('commentsNew') || $registry->get('commentsEdit') || $registry->get('commentsDelete'))
	{
		$comments_access = $contents_access = 1;
	}
	if ($registry->get('usersNew') || $registry->get('usersEdit') || $registry->get('usersDelete'))
	{
		$users_access = $access_access = 1;
	}
	if ($registry->get('groupsNew') || $registry->get('groupsEdit') || $registry->get('groupsDelete'))
	{
		$groups_access = $access_access = 1;
	}
	if ($registry->get('modulesInstall') || $registry->get('modulesEdit') || $registry->get('modulesUninstall'))
	{
		$modules_access = $system_access = 1;
	}
	if ($registry->get('settingsEdit'))
	{
		$settings_access = $system_access = 1;
	}

	/* collect contents output */

	$counter = 1;
	if ($contents_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-content"><span class="rs-admin-text-panel rs-admin-text-content">' . $language->get('contents') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-contents">';
		if ($categories_access == 1)
		{
			$output .= '<li><span class="rs-admin-text-panel-group"><a href="' . $registry->get('parameterRoute') . 'admin/view/categories" class="rs-admin-link-panel' . ($registry->get('categoriesNew') ? ' rs-admin-link-view' : null) . '">' . $language->get('categories') . '</a>';
			if ($registry->get('categoriesNew'))
			{
				$output .= '<a title="' . $language->get('category_new') . '" href="' . $registry->get('parameterRoute') . 'admin/new/categories" class="rs-admin-link-panel' . ($registry->get('categoriesNew') ? ' rs-admin-link-new' : null) . '">' . $language->get('category_new') . '</a>';
			}
			$output .= '</span></li>';
		}
		if ($articles_access == 1)
		{
			$output .= '<li><span class="rs-admin-text-panel-group"><a href="' . $registry->get('parameterRoute') . 'admin/view/articles" class="rs-admin-link-panel' . ($registry->get('articlesNew') ? ' rs-admin-link-view' : null) . '">' . $language->get('articles') . '</a>';
			if ($registry->get('articlesNew'))
			{
				$output .= '<a title="' . $language->get('article_new') . '" href="' . $registry->get('parameterRoute') . 'admin/new/articles" class="rs-admin-link-panel' . ($registry->get('articlesNew') ? ' rs-admin-link-new' : null) . '">' . $language->get('article_new') . '</a>';
			}
			$output .= '</span></li>';
		}
		if ($extras_access == 1)
		{
			$output .= '<li><span class="rs-admin-text-panel-group"><a href="' . $registry->get('parameterRoute') . 'admin/view/extras" class="rs-admin-link-panel' . ($registry->get('extrasNew') ? ' rs-admin-link-view' : null) . '">' . $language->get('extras') . '</a>';
			if ($registry->get('extrasNew'))
			{
				$output .= '<a title="' . $language->get('extra_new') . '" href="' . $registry->get('parameterRoute') . 'admin/new/extras" class="rs-admin-link-panel' . ($registry->get('extrasNew') ? ' rs-admin-link-new' : null) . '">' . $language->get('extra_new') . '</a>';
			}
			$output .= '</span></li>';
		}
		if ($comments_access == 1)
		{
			$output .= '<li><span class="rs-admin-text-panel-group"><a href="' . $registry->get('parameterRoute') . 'admin/view/comments" class="rs-admin-link-panel' . ($registry->get('commentsNew') ? ' rs-admin-link-view' : null) . '">' . $language->get('comments') . '</a>';
			if ($registry->get('commentsNew'))
			{
				$output .= '<a title="' . $language->get('comment_new') . '" href="' . $registry->get('parameterRoute') . 'admin/new/comments" class="rs-admin-link-panel' . ($registry->get('commentsNew') ? ' rs-admin-link-new' : null) . '">' . $language->get('comment_new') . '</a>';
			}
			$output .= '</span></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect access output */

	if ($access_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-access"><span class="rs-admin-text-panel rs-admin-text-access">' . $language->get('access') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-access">';
		if ($registry->get('myId'))
		{
			$output .= '<li><a href="' . $registry->get('parameterRoute') . 'admin/edit/users/' . $registry->get('myId') . '" class="rs-admin-link-panel">' . $language->get('profile') . '</a></li>';
		}
		if ($users_access == 1)
		{
			$output .= '<li><span class="rs-admin-text-panel-group"><a href="' . $registry->get('parameterRoute') . 'admin/view/users" class="rs-admin-link-panel' . ($registry->get('usersNew') ? ' rs-admin-link-view' : null) . '">' . $language->get('users') . '</a>';
			if ($registry->get('usersNew'))
			{
				$output .= '<a title="' . $language->get('user_new') . '" href="' . $registry->get('parameterRoute') . 'admin/new/users" class="rs-admin-link-panel' . ($registry->get('usersNew') ? ' rs-admin-link-new' : null) . '">' . $language->get('user_new') . '</a>';
			}
			$output .= '</span></li>';
		}
		if ($groups_access == 1)
		{
			$output .= '<li><span class="rs-admin-text-panel-group"><a href="' . $registry->get('parameterRoute') . 'admin/view/groups" class="rs-admin-link-panel' . ($registry->get('groupsNew') ? ' rs-admin-link-view' : null) . '">' . $language->get('groups') . '</a>';
			if ($registry->get('groupsNew'))
			{
				$output .= '<a title="' . $language->get('group_new') . '" href="' . $registry->get('parameterRoute') . 'admin/new/groups" class="rs-admin-link-panel' . ($registry->get('groupsNew') ? ' rs-admin-link-new' : null) . '">' . $language->get('group_new') . '</a>';
			}
			$output .= '</span></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect system output */

	if ($system_access == 1)
	{
		$counter++;
		$outputModule = null;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-system"><span class="rs-admin-text-panel rs-admin-text-system">' . $language->get('system') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-panel-children-system">';
		if ($modules_access == 1)
		{
			$output .= '<li><a href="' . $registry->get('parameterRoute') . 'admin/view/modules" class="rs-admin-link-panel">' . $language->get('modules') . '</a>';
			$moduleArray = Redaxscript\Module\Hook::collect('adminPanelModule');
			if ($moduleArray)
			{
				foreach ($moduleArray as $key => $value)
				{
					$outputModule .= '<li><a href="' . $registry->get('parameterRoute') . $value . '" class="rs-admin-link-panel">' . $key. '</a></li>';
				}
				$output .= '<ul class="rs-admin-js-list-panel-children rs-admin-list-panel-children">' . $outputModule . '</ul>';
			}
			$output .= '</li>';
		}
		if ($settings_access == 1)
		{
			$output .= '<li><a href="' . $registry->get('parameterRoute') . 'admin/edit/settings" class="rs-admin-link-panel">' . $language->get('settings') . '</a></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect profile output */

	if ($registry->get('myId'))
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-profile"><a href="' . $registry->get('parameterRoute') . 'admin/edit/users/' . $registry->get('myId') . '" class="rs-admin-link-panel rs-admin-link-profile">' . $language->get('profile') . '</a></li>';
	}

	/* collect notification output */

	$outputNotification = null;
	$counterNotification = 0;
	$moduleLastKey = null;
	$notificationSystemArray = [];
	$notificationHasArray = [];
	$orderArray =
	[
		'success',
		'info',
		'warning',
		'error'
	];
	if ($registry->get('myId') == 1)
	{
		$notificationSystemArray =
		[
			'error' =>
			[
				$language->get('system') =>
				[
					!is_dir('cache') ? $language->get('directory_not_found') . $language->get('colon') . ' cache' . $language->get('point') : null
				]
			],
			'warning' =>
			[
				$language->get('system') =>
				[
					is_file('console.php') ? $language->get('file_remove') . ' console.php' . $language->get('point') : null,
					is_file('install.php') ? $language->get('file_remove') . ' install.php' . $language->get('point') : null,
					is_writable('config.php') ? $language->get('file_permission_revoke') . ' config.php' . $language->get('point') : null
				]
			]
		];
	}
	$notificationModuleArray = Redaxscript\Module\Hook::collect('adminPanelNotification');
	if ($notificationModuleArray)
	{
		$notificationArray = array_merge_recursive($notificationModuleArray, $notificationSystemArray);
	}
	else
	{
		$notificationArray = $notificationSystemArray;
	}
	foreach ($notificationArray as $typeKey => $typeValue)
	{
		foreach ($typeValue as $notificationKey => $notificationValue)
		{
			if (array_filter($notificationValue))
			{
				$outputNotification .= '<li class="rs-admin-item-panel-notification rs-admin-item-note rs-admin-is-' . $typeKey . '">';
				$moduleLastKey = null;
				foreach ($notificationValue as $value)
				{
					if ($moduleLastKey !== $notificationKey)
					{
						$outputNotification .= '<h3 class="rs-admin-title-panel-notification">' . $notificationKey . '</h3>';
					}
					$moduleLastKey = $notificationKey;
					if (is_array($value) && array_key_exists('text', $value) && array_key_exists('attr', $value))
					{
						$notificationHasArray[$typeKey] = 'rs-admin-has-' . $typeKey;
						$outputNotification .= '<a href="' . $value['attr']['href'] . '" target="' . $value['attr']['target'] . '" class="rs-admin-link-panel-notification">' . $value['text'] . '</a>';
					}
					else if ($value)
					{
						$notificationHasArray[$typeKey] = 'rs-admin-has-' . $typeKey;
						$outputNotification .= '<span class="rs-admin-text-panel-notification">' . $value . '</span>';
					}
					if (is_string($value))
					{
						$counterNotification++;
					}
				}
				$outputNotification .= '</li>';
			}
		}
	}
	uksort($notificationHasArray, function($key1, $key2) use ($orderArray)
	{
		return (array_search($key1, $orderArray) > array_search($key2, $orderArray));
	});
	if ($counterNotification)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-notification ' . implode(' ', $notificationHasArray) . '"><span class="rs-admin-text-panel rs-admin-text-notification">' . ($counterNotification ? '<small class="rs-admin-text-notification-counter">' . $counterNotification . '</small>' : null) . $language->get('notifications') . '</span>';
		$output .= '<ul class="rs-admin-list-panel-children rs-admin-list-panel-notification">' . $outputNotification . '</ul></li>';
	}

	/* collect logout */

	$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-logout"><a href="' . $registry->get('parameterRoute') . 'logout" class="rs-admin-link-panel rs-admin-link-logout">' . $language->get('logout') . '</a></li>';

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-js-list-panel rs-admin-list-panel rs-admin-has-column' . $counter . ' rs-admin-fn-clearfix">' . $output . '</ul>';
	}
	$output .= Redaxscript\Module\Hook::trigger('adminPanelEnd');
	echo $output;
}

/**
 * admin dock
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 *
 * @param string $table
 * @param integer $id
 * @return string
 */

function admin_dock($table, $id)
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminDockStart');

	/* define access variables */

	$edit = $registry->get($table . 'Edit');
	$delete = $registry->get($table . 'Delete');

	/* collect output */

	if ($edit == 1 || $delete == 1)
	{
		$output .= '<div class="rs-admin-wrapper-dock"><div class="rs-admin-js-dock rs-admin-box-dock rs-admin-fn-clearfix">';
		if ($edit == 1)
		{
			$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-link-dock rs-admin-link-unpublish" data-description="'. $language->get('unpublish') . '">' . $language->get('unpublish') . '</a>';
			$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/' . $table . '/' . $id . '" class="rs-admin-link-dock rs-admin-link-edit" data-description="'. $language->get('edit') . '">' . $language->get('edit') . '</a>';
		}
		if ($delete == 1)
		{
			$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/delete/' . $table . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-js-confirm rs-admin-link-dock rs-admin-link-delete" data-description="'. $language->get('delete') . '">' . $language->get('delete') . '</a>';
		}
		$output .= '</div></div>';
	}
	$output .= Redaxscript\Module\Hook::trigger('adminDockEnd');
	return $output;
}

/**
 * admin control
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 *
 * @param string $type
 * @param string $table
 * @param integer $id
 * @param string $alias
 * @param integer $status
 * @param string $new
 * @param string $edit
 * @param string $delete
 * @return string
 */

function admin_control($type, $table, $id, $alias, $status, $new, $edit, $delete)
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('adminControlStart');

	/* define access variables */

	if ($type == 'access' && $id == 1)
	{
		$delete = 0;
	}
	if ($type == 'modules_not_installed')
	{
		$edit = $delete = 0;
	}

	/* collect modules output */

	if ($new == 1 && $type == 'modules_not_installed')
	{
		$output .= '<li class="rs-admin-item-control rs-admin-item-install"><a href="' . $registry->get('parameterRoute') . 'admin/install/' . $table . '/' . $alias . '/' . $registry->get('token') . '">' . $language->get('install') . '</a></li>';
	}

	/* collect contents output */

	if ($type == 'contents')
	{
		if ($status == 2)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-future-posting"><span>' . $language->get('future_posting') . '</span></li>';
		}
		if ($edit == 1)
		{
			if ($status == 1)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-unpublish"><a href="' . $registry->get('parameterRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('unpublish') . '</a></li>';
			}
			else if ($status == 0)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-publish"><a href="' . $registry->get('parameterRoute') . 'admin/publish/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('publish') . '</a></li>';
			}
		}
	}

	/* collect access and system output */

	if ($edit == 1 && ($type == 'access' && $id > 1 || $type == 'modules_installed'))
	{
		if ($status == 1)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-disable"><a href="' . $registry->get('parameterRoute') . 'admin/disable/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('disable') . '</a></li>';
		}
		else if ($status == 0)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . $registry->get('parameterRoute') . 'admin/enable/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('enable') . '</a></li>';
		}
	}

	/* collect general edit and delete output */

	if ($edit == 1)
	{
		$output .= '<li class="rs-admin-item-control rs-admin-item-edit"><a href="' . $registry->get('parameterRoute') . 'admin/edit/' . $table . '/' . $id . '">' . $language->get('edit') . '</a></li>';
	}
	if ($delete == 1)
	{
		if ($type == 'modules_installed')
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-uninstall"><a href="' . $registry->get('parameterRoute') . 'admin/uninstall/' . $table . '/' . $alias . '/' . $registry->get('token') . '" class="rs-admin-js-confirm">' . $language->get('uninstall') . '</a></li>';
		}
		else
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-delete"><a href="' . $registry->get('parameterRoute') . 'admin/delete/' . $table . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-js-confirm">' . $language->get('delete') . '</a></li>';
		}
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-list-control">' . $output . '</ul>';
	}
	$output .= Redaxscript\Module\Hook::trigger('adminControlEnd');
	return $output;
}