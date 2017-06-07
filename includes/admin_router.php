<?php

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
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$firstParameter = $registry->get('firstParameter');
	$adminParameter = $registry->get('adminParameter');
	$tableParameter = $registry->get('tableParameter');
	$idParameter = $registry->get('idParameter');
	$aliasParameter = $registry->get('aliasParameter');
	$tokenParameter = $registry->get('tokenParameter');
	$usersException = $tableParameter == 'users' && $idParameter == $registry->get('myId');
	$messenger = new Redaxscript\Admin\Messenger($registry);
	Redaxscript\Module\Hook::trigger('adminRouterStart');
	if ($registry->get('adminRouterBreak') == 1)
	{
		return;
	}

	/* last seen update */

	if (($firstParameter == 'admin' && !$adminParameter) || ($adminParameter == 'view' && $tableParameter == 'users') || $registry->get('cronUpdate'))
	{
		admin_last_update();
	}

	/* validate routing */

	switch (true)
	{
		case $adminParameter && !in_array($adminParameter, ['new', 'view', 'edit', 'up', 'down', 'sort', 'publish', 'unpublish', 'enable', 'disable', 'install', 'uninstall', 'delete', 'process', 'update']):
		case $adminParameter == 'process' && !$_POST['new'] && !$_POST['edit']:
		case $adminParameter == 'update' && !$_POST['update'];
		case $adminParameter && !in_array($tableParameter, ['categories', 'articles', 'extras', 'comments', 'groups', 'users', 'modules', 'settings']):
		case !$aliasParameter && ($adminParameter == 'install' || $adminParameter == 'uninstall'):
		case !$idParameter && in_array($adminParameter, ['edit', 'up', 'down', 'publish', 'unpublish', 'enable', 'disable']) && $tableParameter != 'settings':
		case is_numeric($idParameter) && !Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->id:

			/* show error */

			echo $messenger->setRoute($language->get('back'), 'admin')->error($language->get('something_wrong'));
			return;
	}

	/* define access variables */

	if ($adminParameter && $tableParameter)
	{
		if ($tableParameter == 'modules')
		{
			$install = $registry->get('modulesInstall');
			$uninstall = $registry->get('modulesUninstall');
		}
		else if ($tableParameter != 'settings')
		{
			$new = $registry->get('tableNew');
			if ($tableParameter == 'comments')
			{
				$articles_total = Redaxscript\Db::forTablePrefix('articles')->count();
				$articles_comments_disable = Redaxscript\Db::forTablePrefix('articles')->where('comments', 0)->count();
				if ($articles_total == $articles_comments_disable)
				{
					$new = 0;
				}
			}
			$delete = $registry->get('tableDelete');
		}
		$edit = $registry->get('tableEdit');
	}
	if ($edit == 1 || $delete == 1)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$access = Redaxscript\Db::forTablePrefix($tableParameter)->where('id', $idParameter)->findOne()->access;
		$check_access = $accessValidator->validate($access, $registry->get('myGroups'));
	}

	/* validate access */

	switch (true)
	{
		case $adminParameter == 'new' && $new == 0:
		case $adminParameter == 'view' && in_array($tableParameter, ['categories', 'articles', 'extras', 'comments', 'groups', 'users']) && $new == 0 && $edit == 0 && $delete == 0:
		case $adminParameter == 'view' && $tableParameter == 'settings':
		case $adminParameter == 'view' && $tableParameter == 'modules' && $edit == 0 && $install == 0 && $uninstall == 0:
		case $adminParameter == 'edit' && $edit == 0 && !$usersException:
		case in_array($adminParameter, ['up', 'down', 'sort', 'publish', 'unpublish', 'enable', 'disable']) && $edit == 0:
		case $adminParameter == 'install' && $install == 0:
		case $adminParameter == 'uninstall' && $uninstall == 0:
		case $adminParameter == 'delete' && $delete == 0 && !$usersException:
		case $adminParameter == 'process' && $_POST['new'] && $new == 0:
		case $adminParameter == 'process' && $_POST['edit'] && $edit == 0 && !$usersException:
		case $adminParameter == 'process' && $_POST['groups'] && !$registry->get('groupsEdit'):
		case $adminParameter == 'update' && $edit == 0:
		case $idParameter == 1 && ($adminParameter == 'disable' || $adminParameter == 'delete') && ($tableParameter == 'groups' || $tableParameter == 'users'):
		case is_numeric($idParameter) && $tableParameter && $check_access == 0 && !$usersException:

			/* show error */

			echo $messenger->setRoute($language->get('back'), 'admin')->error($language->get('error_occurred'), $language->get('access_no'));
			return;
	}

	/* check token */

	if (in_array($adminParameter, ['up', 'down', 'sort', 'publish', 'unpublish', 'enable', 'disable', 'install', 'uninstall', 'delete']) && !$tokenParameter)
	{
		/* show error */

		echo $messenger->setRoute($language->get('back'), 'admin')->error($language->get('error_occurred'), $language->get('token_no'));
		return;
	}

	/* admin routing */

	if ($firstParameter == 'admin' && !$adminParameter)
	{
		contents();
	}
	switch ($adminParameter)
	{
		case 'new':
			if ($tableParameter == 'categories')
			{
				$categoryForm = new Redaxscript\Admin\View\CategoryForm($registry, $language);
				echo $categoryForm->render();
			}
			if ($tableParameter == 'articles')
			{
				$articleForm = new Redaxscript\Admin\View\ArticleForm($registry, $language);
				echo $articleForm->render();
			}
			if ($tableParameter == 'extras')
			{
				$extraForm = new Redaxscript\Admin\View\ExtraForm($registry, $language);
				echo $extraForm->render();
			}
			if ($tableParameter == 'comments')
			{
				$commentForm = new Redaxscript\Admin\View\CommentForm($registry, $language);
				echo $commentForm->render();
			}
			if ($tableParameter == 'groups')
			{
				$groupForm = new Redaxscript\Admin\View\GroupForm($registry, $language);
				echo $groupForm->render();
			}
			if ($tableParameter == 'users')
			{
				$userForm = new Redaxscript\Admin\View\UserForm($registry, $language);
				echo $userForm->render();
			}
			return;
		case 'view':
			if (in_array($tableParameter, ['categories', 'articles', 'extras', 'comments']))
			{
				admin_contents_list();
			}
			if (in_array($tableParameter, ['groups', 'users', 'modules']))
			{
				call_user_func('admin_' . $tableParameter . '_list');
			}
			return;
		case 'edit':
			if ($tableParameter == 'categories')
			{
				$categoryForm = new Redaxscript\Admin\View\CategoryForm($registry, $language);
				echo $categoryForm->render($idParameter);
			}
			if ($tableParameter == 'articles')
			{
				$articleForm = new Redaxscript\Admin\View\ArticleForm($registry, $language);
				echo $articleForm->render($idParameter);
			}
			if ($tableParameter == 'extras')
			{
				$extraForm = new Redaxscript\Admin\View\ExtraForm($registry, $language);
				echo $extraForm->render($idParameter);
			}
			if ($tableParameter == 'comments')
			{
				$commentForm = new Redaxscript\Admin\View\CommentForm($registry, $language);
				echo $commentForm->render($idParameter);
			}
			if ($tableParameter == 'groups')
			{
				$groupForm = new Redaxscript\Admin\View\GroupForm($registry, $language);
				echo $groupForm->render($idParameter);
			}
			if ($tableParameter == 'users')
			{
				$userForm = new Redaxscript\Admin\View\UserForm($registry, $language);
				echo $userForm->render($idParameter);
			}
			if ($tableParameter == 'modules')
			{
				$moduleForm = new Redaxscript\Admin\View\ModuleForm($registry, $language);
				echo $moduleForm->render($idParameter);
			}
			if ($tableParameter == 'settings')
			{
				$settingForm = new Redaxscript\Admin\View\SettingForm($registry, $language);
				echo $settingForm->render();
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
			call_user_func('admin_' . $adminParameter);
			return;
	}
	Redaxscript\Module\Hook::trigger('adminRouterEnd');
}