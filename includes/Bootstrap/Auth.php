<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Auth as BaseAuth;

/**
 * children class to boot the auth
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Auth extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	public function autorun() : void
	{
		$this->_setUser();
		$this->_setPermission();
		$this->_setTable();
	}

	/**
	 * set the user
	 *
	 * @since 3.1.0
	 */

	protected function _setUser() : void
	{
		$auth = new BaseAuth($this->_request);
		$auth->init();

		/* set the registry */

		if ($auth->getStatus())
		{
			$this->_registry->set('myId', $auth->getUser('id'));
			$this->_registry->set('myName', $auth->getUser('name'));
			$this->_registry->set('myUser', $auth->getUser('user'));
			$this->_registry->set('myEmail', $auth->getUser('email'));
			$this->_registry->set('myLanguage', $auth->getUser('language'));
			$this->_registry->set('myGroups', $auth->getUser('groups'));
		}
	}

	/**
	 * set the permission
	 *
	 * @since 3.1.0
	 */

	protected function _setPermission() : void
	{
		$auth = new BaseAuth($this->_request);
		$auth->init();

		/* set the registry */

		if ($auth->getStatus())
		{
			$this->_registry->set('categoriesNew', $auth->getPermissionNew('categories'));
			$this->_registry->set('categoriesEdit', $auth->getPermissionEdit('categories'));
			$this->_registry->set('categoriesDelete', $auth->getPermissionDelete('categories'));
			$this->_registry->set('articlesNew', $auth->getPermissionNew('articles'));
			$this->_registry->set('articlesEdit', $auth->getPermissionEdit('articles'));
			$this->_registry->set('articlesDelete', $auth->getPermissionDelete('articles'));
			$this->_registry->set('extrasNew', $auth->getPermissionNew('extras'));
			$this->_registry->set('extrasEdit', $auth->getPermissionEdit('extras'));
			$this->_registry->set('extrasDelete', $auth->getPermissionDelete('extras'));
			$this->_registry->set('commentsNew', $auth->getPermissionNew('comments'));
			$this->_registry->set('commentsEdit', $auth->getPermissionEdit('comments'));
			$this->_registry->set('commentsDelete', $auth->getPermissionDelete('comments'));
			$this->_registry->set('groupsNew', $auth->getPermissionNew('groups'));
			$this->_registry->set('groupsEdit', $auth->getPermissionEdit('groups'));
			$this->_registry->set('groupsDelete', $auth->getPermissionDelete('groups'));
			$this->_registry->set('usersNew', $auth->getPermissionNew('users'));
			$this->_registry->set('usersEdit', $auth->getPermissionEdit('users'));
			$this->_registry->set('usersDelete', $auth->getPermissionDelete('users'));
			$this->_registry->set('modulesInstall', $auth->getPermissionInstall('modules'));
			$this->_registry->set('modulesEdit', $auth->getPermissionEdit('modules'));
			$this->_registry->set('modulesUninstall', $auth->getPermissionUninstall('modules'));
			$this->_registry->set('settingsEdit', $auth->getPermissionEdit('settings'));
		}
		$this->_registry->set('filter', $auth->getFilter());
	}

	/**
	 * set the table
	 *
	 * @since 3.1.0
	 */

	protected function _setTable() : void
	{
		$tableParameter = $this->_registry->get('tableParameter');

		/* set the registry */

		$this->_registry->set('tableNew', $this->_registry->get($tableParameter . 'New'));
		$this->_registry->set('tableInstall', $this->_registry->get($tableParameter . 'Install'));
		$this->_registry->set('tableEdit', $this->_registry->get($tableParameter . 'Edit'));
		$this->_registry->set('tableDelete', $this->_registry->get($tableParameter . 'Delete'));
		$this->_registry->set('tableUninstall', $this->_registry->get($tableParameter . 'Uninstall'));
	}
}
