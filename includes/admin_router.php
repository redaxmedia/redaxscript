<?php
use Redaxscript\Language;

/**
 * admin router
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_router()
{
	$messenger = new \Redaxscript\Messenger();
	Redaxscript\Hook::trigger('adminRouterStart');
	if (Redaxscript\Registry::get('adminRouterBreak') == 1)
	{
		return;
	}

	/* call last update */

	if (FIRST_PARAMETER == 'admin' && ADMIN_PARAMETER == '' || ADMIN_PARAMETER == 'view' && TABLE_PARAMETER == 'users' || UPDATE == '')
	{
		admin_last_update();
	}

	/* validate routing */

	switch (true)
	{
		case ADMIN_PARAMETER && in_array(ADMIN_PARAMETER, array('new', 'view', 'edit', 'up', 'down', 'sort', 'publish', 'unpublish', 'enable', 'disable', 'install', 'uninstall', 'delete', 'process', 'update')) == '':
		case ADMIN_PARAMETER == 'process' && $_POST['new'] == '' && $_POST['edit'] == '':
		case ADMIN_PARAMETER == 'update' && $_POST['update'] == '';
		case ADMIN_PARAMETER && in_array(TABLE_PARAMETER, array('categories', 'articles', 'extras', 'comments', 'groups', 'users', 'modules', 'settings')) == '':
		case ALIAS_PARAMETER == '' && (ADMIN_PARAMETER == 'install' || ADMIN_PARAMETER == 'uninstall'):
		case ID_PARAMETER == '' && in_array(ADMIN_PARAMETER, array('edit', 'up', 'down', 'publish', 'unpublish', 'enable', 'disable')) && TABLE_PARAMETER != 'settings':
		case is_numeric(ID_PARAMETER) && Redaxscript\Db::forTablePrefi1x(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->id == '':

			/* show error */

			$messenger->setAction(Language::get('back'), 'admin');
			echo $messenger->error(Language::get('something_wrong'));
			return;
	}

	/* define access variables */

	if (ADMIN_PARAMETER && TABLE_PARAMETER)
	{
		if (TABLE_PARAMETER == 'modules')
		{
			$install = MODULES_INSTALL;
			$uninstall = MODULES_UNINSTALL;
		}
		else if (TABLE_PARAMETER != 'settings')
		{
			$new = TABLE_NEW;
			if (TABLE_PARAMETER == 'comments')
			{
				$articles_total = Redaxscript\Db::forTablePrefix('articles')->count();
				$articles_comments_disable = Redaxscript\Db::forTablePrefix('articles')->where('comments', 0)->count();
				if ($articles_total == $articles_comments_disable)
				{
					$new = 0;
				}
			}
			$delete = TABLE_DELETE;
		}
		$edit = TABLE_EDIT;
	}
	if ($edit == 1 || $delete == 1)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$access = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findOne()->access;
		$check_access = $accessValidator->validate($access, MY_GROUPS);
	}

	/* validate access */

	switch (true)
	{
		case ADMIN_PARAMETER == 'new' && $new == 0:
		case ADMIN_PARAMETER == 'view' && in_array(TABLE_PARAMETER, array('categories', 'articles', 'extras', 'comments', 'groups', 'users')) && $new == 0 && $edit == 0 && $delete == 0:
		case ADMIN_PARAMETER == 'view' && TABLE_PARAMETER == 'settings':
		case ADMIN_PARAMETER == 'view' && TABLE_PARAMETER == 'modules' && $edit == 0 && $install == 0 && $uninstall == 0:
		case ADMIN_PARAMETER == 'edit' && $edit == 0 && USERS_EXCEPTION == 0:
		case in_array(ADMIN_PARAMETER, array('up', 'down', 'sort', 'publish', 'unpublish', 'enable', 'disable')) && $edit == 0:
		case ADMIN_PARAMETER == 'install' && $install == 0:
		case ADMIN_PARAMETER == 'uninstall' && $uninstall == 0:
		case ADMIN_PARAMETER == 'delete' && $delete == 0 && USERS_EXCEPTION == 0:
		case ADMIN_PARAMETER == 'process' && $_POST['new'] && $new == 0:
		case ADMIN_PARAMETER == 'process' && $_POST['edit'] && $edit == 0 && USERS_EXCEPTION == 0:
		case ADMIN_PARAMETER == 'process' && $_POST['groups'] && GROUPS_EDIT == 0:
		case ADMIN_PARAMETER == 'update' && $edit == 0:
		case ID_PARAMETER == 1 && (ADMIN_PARAMETER == 'disable' || ADMIN_PARAMETER == 'delete') && (TABLE_PARAMETER == 'groups' || TABLE_PARAMETER == 'users'):
		case is_numeric(ID_PARAMETER) && TABLE_PARAMETER && $check_access == 0 && USERS_EXCEPTION == 0:

			/* show error */

			$messenger->setAction(Language::get('back'), 'admin');
			echo $messenger->error(Language::get('error_occurred'), Language::get('access_no'));
			return;
	}

	/* check token */

	if (in_array(ADMIN_PARAMETER, array('up', 'down', 'sort', 'publish', 'unpublish', 'enable', 'disable', 'install', 'uninstall', 'delete')) && TOKEN_PARAMETER == '')
	{
		/* show error */

		$messenger->setAction(Language::get('back'), 'admin');
		echo $messenger->error(Language::get('error_occurred'), Language::get('token_no'));
		return;
	}

	/* admin routing */

	if (FIRST_PARAMETER == 'admin' && ADMIN_PARAMETER == '')
	{
		admin_notification();
		contents();
	}
	switch (ADMIN_PARAMETER)
	{
		case 'new':
			if (TABLE_PARAMETER == 'categories')
			{
				$categoryForm = new Redaxscript\Admin\View\CategoryForm();
				echo $categoryForm;
			}
			if (TABLE_PARAMETER == 'articles')
			{
				$articleForm = new Redaxscript\Admin\View\ArticleForm();
				echo $articleForm;
			}
			if (TABLE_PARAMETER == 'extras')
			{
				$extraForm = new Redaxscript\Admin\View\ExtraForm();
				echo $extraForm;
			}
			if (TABLE_PARAMETER == 'comments')
			{
				$commentForm = new Redaxscript\Admin\View\CommentForm();
				echo $commentForm;
			}
			if (TABLE_PARAMETER == 'groups')
			{
				$groupForm = new Redaxscript\Admin\View\GroupForm();
				echo $groupForm;
			}
			if (TABLE_PARAMETER == 'users')
			{
				$userForm = new Redaxscript\Admin\View\UserForm();
				echo $userForm;
			}
			if (in_array(TABLE_PARAMETER, array('categories', 'articles', 'extras', 'comments')))
			{
				admin_contents_form();
			}
			if (in_array(TABLE_PARAMETER, array('groups', 'users')))
			{
				call_user_func('admin_' . TABLE_PARAMETER . '_form');
			}
			return;
		case 'view':
			if (in_array(TABLE_PARAMETER, array('categories', 'articles', 'extras', 'comments')))
			{
				admin_contents_list();
			}
			if (in_array(TABLE_PARAMETER, array('groups', 'users', 'modules')))
			{
				call_user_func('admin_' . TABLE_PARAMETER . '_list');
			}
			return;
		case 'edit':
			if (TABLE_PARAMETER == 'categories')
			{
				$categoryForm = new Redaxscript\Admin\View\CategoryForm();
				echo $categoryForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'articles')
			{
				$articleForm = new Redaxscript\Admin\View\ArticleForm();
				echo $articleForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'extras')
			{
				$extraForm = new Redaxscript\Admin\View\ExtraForm();
				echo $extraForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'comments')
			{
				$commentForm = new Redaxscript\Admin\View\CommentForm();
				echo $commentForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'groups')
			{
				$groupForm = new Redaxscript\Admin\View\GroupForm();
				echo $groupForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'users')
			{
				$userForm = new Redaxscript\Admin\View\UserForm();
				echo $userForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'modules')
			{
				$moduleForm = new Redaxscript\Admin\View\ModuleForm();
				echo $moduleForm->render(ID_PARAMETER);
			}
			if (TABLE_PARAMETER == 'settings')
			{
				$settingForm = new Redaxscript\Admin\View\SettingForm();
				echo $settingForm->render(ID_PARAMETER);
			}
			if (in_array(TABLE_PARAMETER, array('categories', 'articles', 'extras', 'comments')))
			{
				admin_contents_form();
			}
			if (in_array(TABLE_PARAMETER, array('groups', 'users', 'modules', 'settings')))
			{
				call_user_func('admin_' . TABLE_PARAMETER . '_form');
			}
			return;
		case 'up':
		case 'down':
			admin_move();
			return;
		case 'sort':
			admin_sort();
			return;
		case 'publish':
		case 'enable':
			admin_status(1);
			return;
		case 'unpublish':
		case 'disable':
			admin_status(0);
			return;
		case 'install':
		case 'uninstall':
			admin_install();
			return;
		case 'delete':
		case 'process':
		case 'update':
			call_user_func('admin_' . ADMIN_PARAMETER);
			return;
	}
	Redaxscript\Hook::trigger('adminRouterEnd');
}