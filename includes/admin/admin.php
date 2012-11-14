<?php

/**
 * admin panel list
 */

function admin_panel_list()
{
	hook(__FUNCTION__ . '_start');

	/* define access variables */

	switch (true)
	{
		case CATEGORIES_NEW == 1:
		case CATEGORIES_EDIT == 1:
		case CATEGORIES_DELETE == 1:
			$categories_access = 1;
		case ARTICLES_NEW == 1:
		case ARTICLES_EDIT == 1:
		case ARTICLES_DELETE == 1:
			$articles_access = 1;
		case EXTRAS_NEW == 1:
		case EXTRAS_EDIT == 1:
		case EXTRAS_DELETE == 1:
			$extras_access = 1;
		case COMMENTS_NEW == 1:
		case COMMENTS_EDIT == 1:
		case COMMENTS_DELETE == 1:
			$comments_access = 1;
			$contents_access = 1;
		case USERS_NEW == 1:
		case USERS_EDIT == 1:
		case USERS_DELETE == 1:
			$users_access = 1;
		case GROUPS_NEW == 1:
		case GROUPS_EDIT == 1:
		case GROUPS_DELETE == 1:
			$groups_access = 1;
			$access_access = 1;
		case MODULES_INSTALL == 1:
		case MODULES_EDIT == 1:
		case MODULES_UNINSTALL == 1:
			$modules_access = 1;
		case SETTINGS_EDIT == 1:
			$settings_access = 1;
			$system_access = 1;
			break;
	}

	/* collect contents output */

	if ($contents_access)
	{
		$output = '<li class="item_contents">' . l('contents') . '<ul class="list_children list_contents">';
		if ($categories_access == 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('categories'), 'admin/view/categories') . '</li>';
		}
		if ($articles_access = 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('articles'), 'admin/view/articles') . '</li>';
		}
		if ($extras_access = 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('extras'), 'admin/view/extras') . '</li>';
		}
		if ($comments_access = 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('comments'), 'admin/view/comments') . '</li>';
		}
		$output .= '</ul></li>';
	}

	/* collect access output */

	if ($access_access)
	{
		$output .= '<li class="item_access">' . l('access') . '<ul class="list_children list_access">';
		if (MY_ID)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('profile'), 'admin/edit/users/' . MY_ID) . '</li>';
		}
		if ($users_access == 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('users'), 'admin/view/users') . '</li>';
		}
		if ($groups_access == 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('groups'), 'admin/view/groups') . '</li>';
		}
		$output .= '</ul></li>';
	}
	else if (MY_ID)
	{
		$output .= '<li>' . anchor_element('internal', '', '', l('profile'), 'admin/edit/users/' . MY_ID) . '</li>';
	}

	/* collect panel modules */

	$admin_panel_list_modules = hook('admin_panel_list_modules');

	/* collect system output */

	if ($system_access || $admin_panel_list_modules)
	{
		$output .= '<li class="item_system">' . l('system') . '<ul class="list_children list_stystem">';
		if ($modules_access == 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('modules'), 'admin/view/modules');
			if ($admin_panel_list_modules)
			{
				$output .= '<ul>' . $admin_panel_list_modules . '</ul>';
			}
			$output .= '</li>';
		}
		if ($settings_access == 1)
		{
			$output .= '<li>' . anchor_element('internal', '', '', l('settings'), 'admin/edit/settings') . '</li>';
		}
		$output .= '</ul></li>';
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="js_dropdown list_dropdown list_panel_admin">' . $output . '</ul>';
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/**
 * admin dock
 *
 * @param string $table
 * @param integer $id
 * @return string
 */

function admin_dock($table = '', $id = '')
{
	hook(__FUNCTION__ . '_start');

	/* define access variables */

	$edit = constant(strtoupper($table) . '_EDIT');
	$delete = constant(strtoupper($table) . '_DELETE');

	/* collect output */

	if ($edit == 1 || $delete == 1)
	{
		$output = '<div class="placeholder_dock_admin clear_fix"><div class="js_dock_admin box_dock_admin">';
		if ($edit == 1)
		{
			$output .= anchor_element('internal', '', 'js_link_dock_admin link_dock_admin link_unpublish', l('unpublish'), 'admin/unpublish/' . $table . '/' . $id . '/' . TOKEN);
			$output .= '<span class="divider">' . s('divider') . '</span>';
			$output .= anchor_element('internal', '', 'js_link_dock_admin link_dock_admin link_edit', l('edit'), 'admin/edit/' . $table . '/' . $id);
		}
		if ($edit == 1 && $delete == 1)
		{
			$output .= '<span class="divider">' . s('divider') . '</span>';
		}
		if ($delete == 1)
		{
			$output .= anchor_element('internal', '', 'js_confirm js_link_dock_admin link_dock_admin link_delete', l('delete'), 'admin/delete/' . $table . '/' . $id . '/' . TOKEN);
		}
		$output .= '</div></div>';
	}
	return $output;
	hook(__FUNCTION__ . '_end');
}

/**
 * admin notification
 */

function admin_notification()
{
	hook(__FUNCTION__ . '_start');

	/* insecure file warning */

	if (MY_ID == 1)
	{
		if (file_exists('install.php'))
		{
			$output = '<div class="box_note note_warning">' . l('file_remove') . l('colon') . ' install.php' . l('point') . '</div>';
		}
		if (is_writable('config.php'))
		{
			$output .= '<div class="box_note note_warning">' . l('file_permission_revoke') . l('colon') . ' config.php' . l('point') . '</div>';
		}
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>