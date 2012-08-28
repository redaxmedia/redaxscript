<?php

/* admin routing */

function admin_routing()
{
	/* call last update */

	if (FIRST_PARAMETER == 'admin' && ADMIN_PARAMETER == '' || ADMIN_PARAMETER == 'view' && TABLE_PARAMETER == 'users' || UPDATE == '')
	{
		admin_last_update();
	}

	/* validate routing */

	switch (true)
	{
		case ADMIN_PARAMETER && ADMIN_PARAMETER != 'new' && ADMIN_PARAMETER != 'view' && ADMIN_PARAMETER != 'edit' && ADMIN_PARAMETER != 'up' && ADMIN_PARAMETER != 'down' && ADMIN_PARAMETER != 'publish' && ADMIN_PARAMETER != 'enable' && ADMIN_PARAMETER != 'unpublish' && ADMIN_PARAMETER != 'disable' && ADMIN_PARAMETER != 'install' && ADMIN_PARAMETER != 'uninstall' && ADMIN_PARAMETER != 'delete' && ADMIN_PARAMETER != 'process' && ADMIN_PARAMETER != 'update':
		case ADMIN_PARAMETER == 'process' && $_POST['new'] == '' && $_POST['edit'] == '':
		case ADMIN_PARAMETER == 'update' && $_POST['update'] == '';
		case TABLE_PARAMETER && TABLE_PARAMETER != 'categories' && TABLE_PARAMETER != 'articles' && TABLE_PARAMETER != 'extras' && TABLE_PARAMETER != 'comments' && TABLE_PARAMETER != 'groups' && TABLE_PARAMETER != 'users' && TABLE_PARAMETER != 'modules' && TABLE_PARAMETER != 'settings':
		case ID_PARAMETER == '' && (ADMIN_PARAMETER == 'edit' || ADMIN_PARAMETER == 'up' || ADMIN_PARAMETER == 'down' || ADMIN_PARAMETER == 'publish' || ADMIN_PARAMETER == 'enable' || ADMIN_PARAMETER == 'unpublish' || ADMIN_PARAMETER == 'disable'):
		case is_numeric(ID_PARAMETER) && retrieve('id', TABLE_PARAMETER, 'id', ID_PARAMETER) == '':
			notification(l('something_wrong'), '', l('continue'), 'admin');
			return;
			break;
	}

	/* setup permission variables */

	if (ADMIN_PARAMETER && TABLE_PARAMETER)
	{
		if (TABLE_PARAMETER == 'modules')
		{
			$install = MODULES_INSTALL;
			$uninstall = MODULES_UNINSTALL;
		}
		else if (TABLE_PARAMETER != 'modules' && TABLE_PARAMETER != 'settings')
		{
			$new = constant(strtoupper(TABLE_PARAMETER) . '_NEW');
			if (TABLE_PARAMETER == 'comments')
			{
				$articles_total = query_total('articles');
				$articles_comments_disable = query_total('articles', 'comments', 0);
				if ($articles_total == $articles_comments_disable)
				{
					$new = 0;
				}
			}
			$delete = constant(strtoupper(TABLE_PARAMETER) . '_DELETE');
		}
		$edit = constant(strtoupper(TABLE_PARAMETER) . '_EDIT');
	}
	if ($edit == 1 || $delete == 1)
	{
		$access = retrieve('access', TABLE_PARAMETER, 'id', ID_PARAMETER);
		$check_access = check_access($access, MY_GROUPS);
	}

	/* validate access */

	switch (true)
	{
		case ADMIN_PARAMETER == 'new' && $new == 0:
		case ADMIN_PARAMETER == 'view' && TABLE_PARAMETER != 'modules' && $edit == 0 && $delete == 0:
		case ADMIN_PARAMETER == 'view' && TABLE_PARAMETER == 'modules' && $edit == 0 && $install == 0 && $uninstall == 0:
		case ADMIN_PARAMETER == 'edit' && $edit == 0 && USERS_EXCEPTION == 0:
		case ADMIN_PARAMETER == 'up' && $edit == 0:
		case ADMIN_PARAMETER == 'down' && $edit == 0:
		case ADMIN_PARAMETER == 'publish' && $edit == 0:
		case ADMIN_PARAMETER == 'enable' && $edit == 0:
		case ADMIN_PARAMETER == 'unpublish' && $edit == 0:
		case ADMIN_PARAMETER == 'disable' && $edit == 0:
		case ADMIN_PARAMETER == 'install' && $install == 0:
		case ADMIN_PARAMETER == 'uninstall' && $uninstall == 0:
		case ADMIN_PARAMETER == 'delete' && $delete == 0 && USERS_EXCEPTION == 0:
		case ADMIN_PARAMETER == 'process' && $_POST['new'] && $new == 0:
		case ADMIN_PARAMETER == 'process' && $_POST['edit'] && $edit == 0 && USERS_EXCEPTION == 0:
		case ADMIN_PARAMETER == 'update' && SETTINGS_EDIT == 0;
		case ID_PARAMETER == 1 && (ADMIN_PARAMETER == 'disable' || ADMIN_PARAMETER == 'delete') && (TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users'):
		case is_numeric(ID_PARAMETER) && TABLE_PARAMETER && $check_access == 0 && USERS_EXCEPTION == 0:
			notification(l('error_occurred'), l('access_no'), l('back'), 'admin');
			return;
			break;
	}

	/* check token */

	switch (true)
	{
		case ADMIN_PARAMETER == 'up' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'down' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'publish' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'enable' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'unpublish' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'disable' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'install' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'uninstall' && TOKEN_PARAMETER == '':
		case ADMIN_PARAMETER == 'delete' && TOKEN_PARAMETER == '':
			notification(l('error_occurred'), l('access_no'), l('back'), 'admin');
			return;
			break;
	}

	/* general routing */

	if (FIRST_PARAMETER == 'admin' && ADMIN_PARAMETER == '')
	{
		admin_notification();
		contents();	
	}
	switch (ADMIN_PARAMETER)
	{
		case 'new':
			if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
			{
				admin_contents_form();
			}
			if (TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users')
			{
				call_user_func('admin_' . TABLE_PARAMETER . '_form');
			}
			return;
			break;
		case 'view':
			if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
			{
				admin_contents_list();
			}
			if (TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users' || TABLE_PARAMETER == 'modules')
			{
				call_user_func('admin_' . TABLE_PARAMETER . '_list');
			}
			return;
			break;
		case 'edit':
			if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
			{
				admin_contents_form();
			}
			if (TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users' || TABLE_PARAMETER == 'modules' || TABLE_PARAMETER == 'settings')
			{
				call_user_func('admin_' . TABLE_PARAMETER . '_form');
			}
			return;
			break;
		case 'up':
		case 'down':
			admin_move(ADMIN_PARAMETER);
			return;
			break;
		case 'publish':
		case 'enable':
			admin_status(1);
			return;
			break;
		case 'unpublish':
		case 'disable':
			admin_status(0);
			return;
			break;
		case 'install':
		case 'uninstall':
			admin_install(ADMIN_PARAMETER);
			return;
			break;
		case 'delete':
		case 'process':
		case 'update':
			call_user_func('admin_' . ADMIN_PARAMETER);
			return;
			break;
	}
}
?>