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
	$output = Redaxscript\Hook::trigger('adminPanelStart');

	/* define access variables */

	if (CATEGORIES_NEW == 1 || CATEGORIES_EDIT == 1 || CATEGORIES_DELETE == 1)
	{
		$categories_access = $contents_access = 1;
	}
	if (ARTICLES_NEW == 1 || ARTICLES_EDIT == 1 || ARTICLES_DELETE == 1)
	{
		$articles_access = $contents_access = 1;
	}
	if (EXTRAS_NEW == 1 || EXTRAS_EDIT == 1 || EXTRAS_DELETE == 1)
	{
		$extras_access = $contents_access = 1;
	}
	if (COMMENTS_NEW == 1 || COMMENTS_EDIT == 1 || COMMENTS_DELETE == 1)
	{
		$comments_access = $contents_access = 1;
	}
	if (USERS_NEW == 1 || USERS_EDIT == 1 || USERS_DELETE == 1)
	{
		$users_access = $access_access = 1;
	}
	if (GROUPS_NEW == 1 || GROUPS_EDIT == 1 || GROUPS_DELETE == 1)
	{
		$groups_access = $access_access = 1;
	}
	if (MODULES_INSTALL == 1 || MODULES_EDIT == 1 || MODULES_UNINSTALL == 1)
	{
		$modules_access = $system_access = 1;
	}
	if (SETTINGS_EDIT == 1)
	{
		$settings_access = $system_access = 1;
	}

	/* collect contents output */

	$counter = 1;
	if ($contents_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-contents"><span>' . l('contents') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-contents">';
		if ($categories_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/categories">' . Redaxscript\Language::get('categories') . '</a></li>';
		}
		if ($articles_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/articles">' . Redaxscript\Language::get('articles') . '</a></li>';
		}
		if ($extras_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/extras">' . Redaxscript\Language::get('extras') . '</a></li>';
		}
		if ($comments_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/comments">' . Redaxscript\Language::get('comments') . '</a></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect access output */

	if ($access_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-access"><span>' . l('access') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-access">';
		if (MY_ID)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/users/' . Redaxscript\Registry::get('myId') . '">' . Redaxscript\Language::get('profile') . '</a></li>';
		}
		if ($users_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/users">' . Redaxscript\Language::get('users') . '</a></li>';
		}
		if ($groups_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/groups">' . Redaxscript\Language::get('groups') . '</a></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect system output */

	if ($system_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-system"><span>' . l('system') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-stystem">';
		if ($modules_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/view/modules">' . Redaxscript\Language::get('modules') . '</a></li>';

			/* collect modules list */

			$admin_panel_list_modules = Redaxscript\Hook::trigger('adminPanelAddModule');
			if ($admin_panel_list_modules)
			{
				$output .= '<ul class="rs-admin-js-list-panel-children rs-admin-list-panel-children">' . $admin_panel_list_modules . '</ul>';
			}
			$output .= '</li>';
		}
		if ($settings_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/edit/settings">' . Redaxscript\Language::get('settings') . '</a></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect profile */

	if (MY_USER && MY_ID)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-profile"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/edit/users/' . Redaxscript\Registry::get('myId') . '">' . Redaxscript\Language::get('profile') . '</a></li>';
	}

	/* collect logout */

	$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-logout"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'logout">' . Redaxscript\Language::get('logout') . '</a></li>';

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-js-list-panel rs-admin-list-panel rs-admin-c' . $counter . '">' . $output . '</ul>';
	}
	$output .= Redaxscript\Hook::trigger('adminPanelEnd');
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

function admin_dock($table = '', $id = '')
{
	$output = Redaxscript\Hook::trigger('adminDockStart');

	/* define access variables */

	$edit = constant(strtoupper($table) . '_EDIT');
	$delete = constant(strtoupper($table) . '_DELETE');

	/* collect output */

	if ($edit == 1 || $delete == 1)
	{
		$output .= '<div class="rs-admin-wrapper-dock"><div class="rs-admin-js-dock rs-admin-box-dock rs-admin-clearfix">';
		if ($edit == 1)
		{
			$output .= '<a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-js-link-dock rs-admin-link-dock rs-admin-link-unpublish">' . Redaxscript\Language::get('unpublish') . '</a>';
			$output .= '<a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/edit/' . $table . '/' . $id . '" class="rs-admin-js-link-dock rs-admin-link-dock rs-admin-link-edit">' . Redaxscript\Language::get('edit') . '</a>';
		}
		if ($delete == 1)
		{
			$output .= '<a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/delete/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-js-confirm rs-admin-js-link-dock rs-admin-link-dock rs-admin-link-delete">' . Redaxscript\Language::get('delete') . '</a>';
		}
		$output .= '</div></div>';
	}
	$output .= Redaxscript\Hook::trigger('adminDockEnd');
	return $output;
}

/**
 * admin notification
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_notification()
{
	$output = Redaxscript\Hook::trigger('adminNotificationStart');

	/* insecure file warning */

	if (MY_ID == 1)
	{
		if (file_exists('install.php'))
		{
			$output .= '<div class="rs-box-note rs-note-warning">' . l('file_remove') . l('colon') . ' install.php' . l('point') . '</div>';
		}
		if (is_writable('config.php'))
		{
			$output .= '<div class="rs-box-note rs-note-warning">' . l('file_permission_revoke') . l('colon') . ' config.php' . l('point') . '</div>';
		}
	}
	$output .= Redaxscript\Hook::trigger('adminNotificationEnd');
	echo $output;
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

function admin_control($type = '', $table = '', $id = '', $alias = '', $status = '', $new = '', $edit = '', $delete = '')
{
	$output = Redaxscript\Hook::trigger('adminControlStart');

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
		$output .= '<li class="rs-admin-item-control rs-admin-link-install"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/install/' . $table . '/' . $alias . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('install') . '</a></li>';
	}

	/* collect contents output */

	if ($type == 'contents')
	{
		if ($status == 2)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-future-posting"><span>' . Redaxscript\Language::get('future_posting') . '</span></li>';
		}
		if ($edit == 1)
		{
			if ($status == 1)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-unpublish"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('unpublish') . '</a></li>';
			}
			else if ($status == 0)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-publish"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/publish/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('publish') . '</a></li>';
			}
		}
	}

	/* collect access and system output */

	if ($edit == 1 && ($type == 'access' && $id > 1 || $type == 'modules_installed'))
	{
		if ($status == 1)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-disable"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/disable/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('disable') . '</a></li>';
		}
		else if ($status == 0)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/enable/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('enable') . '</a></li>';
		}
	}

	/* collect general edit and delete output */

	if ($edit == 1)
	{
		$output .= '<li class="rs-admin-item-control rs-admin-item-edit"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/edit/' . $table . '/' . $id . '">' . Redaxscript\Language::get('edit') . '</a></li>';
	}
	if ($delete == 1)
	{
		if ($type == 'modules_installed')
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/uninstall/' . $table . '/' . $alias . '/' . Redaxscript\Registry::get('token') . '" class="rs-js-confirm">' . Redaxscript\Language::get('uninstall') . '</a></li>';
		}
		else
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . Redaxscript\Registry::get('rewriteRoute') . 'admin/delete/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-js-confirm">' . Redaxscript\Language::get('delete') . '</a></li>';
		}
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-list-control">' . $output . '</ul>';
	}
	$output .= Redaxscript\Hook::trigger('adminControlEnd');
	return $output;
}