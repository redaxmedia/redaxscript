<?php
namespace Redaxscript\Admin\Router;

use Redaxscript\Admin;
use Redaxscript\Module;
use Redaxscript\Router\RouterAbstract;

/**
 * parent class to provide the admin router
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class Router extends RouterAbstract
{
	/**
	 * route the header
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	public function routeHeader() : bool
	{
		Module\Hook::trigger('adminRouteHeader');

		/* handle break */

		if ($this->_registry->get('adminRouterBreak'))
		{
			$this->_registry->set('contentError', false);
		}
		return $this->_registry->get('adminRouterBreak');
	}

	/**
	 * route the content
	 *
	 * @since 3.3.0
	 *
	 * @return string|bool
	 */

	public function routeContent()
	{
		Module\Hook::trigger('adminRouteContent');
		$firstParameter = $this->getFirst();
		$adminParameter = $this->getAdmin();
		$tableParameter = $this->getTable();

		/* handle admin */

		if ($firstParameter === 'admin')
		{
			/* handle guard */

			if ($adminParameter)
			{
				if ($this->_tokenGuard())
				{
					return $this->_errorToken();
				}
				if ($this->_authGuard())
				{
					return $this->_errorAccess();
				}
			}

			/* handle update */

			if (!$adminParameter || $adminParameter == 'view' && $tableParameter == 'users' || $this->_registry->get('cronUpdate'))
			{
				$userModel = new Admin\Model\User();
				$userModel->updateLastById($this->_registry->get('myId'));
			}

			/* handle post */

			if ($this->_request->getPost('Redaxscript\Admin\View\CategoryForm'))
			{
				$categoryController = new Admin\Controller\Category($this->_registry, $this->_request, $this->_language);
				return $categoryController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ArticleForm'))
			{
				$articleController = new Admin\Controller\Article($this->_registry, $this->_request, $this->_language);
				return $articleController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ExtraForm'))
			{
				$extraController = new Admin\Controller\Extra($this->_registry, $this->_request, $this->_language);
				return $extraController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\CommentForm'))
			{
				$commentController = new Admin\Controller\Comment($this->_registry, $this->_request, $this->_language);
				return $commentController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\UserForm'))
			{
				$userController = new Admin\Controller\User($this->_registry, $this->_request, $this->_language);
				return $userController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\GroupForm'))
			{
				$groupController = new Admin\Controller\Group($this->_registry, $this->_request, $this->_language);
				return $groupController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ModuleForm'))
			{
				$moduleController = new Admin\Controller\Module($this->_registry, $this->_request, $this->_language);
				return $moduleController->process();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\SettingForm'))
			{
				$settingController = new Admin\Controller\Setting($this->_registry, $this->_request, $this->_language);
				return $settingController->process();
			}

			/* handle route */

			if ($adminParameter === 'view')
			{
				return $this->_renderView();
			}
			if ($adminParameter === 'new')
			{
				return $this->_renderNew();
			}
			if ($adminParameter === 'edit')
			{
				return $this->_renderEdit();
			}

			/* handle common */

			$commonController = new Admin\Controller\Common($this->_registry, $this->_request, $this->_language);
			$commonArray =
			[
				'enable',
				'disable',
				'publish',
				'unpublish',
				'install',
				'uninstall',
				'delete'
			];
			if (in_array($adminParameter, $commonArray))
			{
				return $commonController->process($adminParameter);
			}
		}
		return $this->_registry->get('adminRouterBreak');
	}

	/**
	 * token guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _tokenGuard() : bool
	{
		$adminParameter = $this->getAdmin();
		$tokenParameter = $this->getToken();
		$tokenArray =
		[
			'enable',
			'disable',
			'publish',
			'unpublish',
			'install',
			'uninstall',
			'delete'
		];
		return $this->_request->getPost() && $this->_request->getPost('token') !== $this->_registry->get('token') || in_array($adminParameter, $tokenArray) && !$tokenParameter;
	}

	/**
	 * auth guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _authGuard() : bool
	{
		$adminParameter = $this->getAdmin();
		$tableParameter = $this->getTable();
		$idParameter = $this->getId();
		$editArray =
		[
			'edit',
			'view',
			'enable',
			'disable',
			'publish',
			'unpublish'
		];
		$permissionNew = $adminParameter === 'new' && $this->_registry->get('tableNew');
		$permissionEdit = in_array($adminParameter, $editArray) && $this->_registry->get('tableEdit');
		$permissionDelete = $adminParameter === 'delete' && $this->_registry->get('tableDelete');
		$permissionInstall = $adminParameter === 'install' && $this->_registry->get('tableInstall');
		$permissionUninstall = $adminParameter === 'uninstall' && $this->_registry->get('tableUninstall');
		$permissionProfile = $tableParameter === 'users' && $idParameter === $this->_registry->get('myId');
		return !$permissionNew && !$permissionEdit && !$permissionDelete && !$permissionInstall && !$permissionUninstall && !$permissionProfile;
	}

	/**
	 * render the view
	 *
	 * @since 3.3.0
	 *
	 * @return string|bool
	 */

	protected function _renderView()
	{
		$tableParameter = $this->getTable();

		/* handle table */

		if ($tableParameter == 'categories')
		{
			$categoryTable = new Admin\View\CategoryTable($this->_registry, $this->_language);
			return $categoryTable->render();
		}
		if ($tableParameter == 'articles')
		{
			$articleTable = new Admin\View\ArticleTable($this->_registry, $this->_language);
			return $articleTable->render();
		}
		if ($tableParameter == 'extras')
		{
			$extraTable = new Admin\View\ExtraTable($this->_registry, $this->_language);
			return $extraTable->render();
		}
		if ($tableParameter == 'comments')
		{
			$commentTable = new Admin\View\CommentTable($this->_registry, $this->_language);
			return $commentTable->render();
		}
		if ($tableParameter == 'users')
		{
			$userTable = new Admin\View\UserTable($this->_registry, $this->_language);
			return $userTable->render();
		}
		if ($tableParameter == 'groups')
		{
			$groupTable = new Admin\View\GroupTable($this->_registry, $this->_language);
			return $groupTable->render();
		}
		if ($tableParameter == 'modules')
		{
			$moduleTable = new Admin\View\ModuleTable($this->_registry, $this->_language);
			return $moduleTable->render();
		}
		return false;
	}

	/**
	 * render the new
	 *
	 * @since 3.3.0
	 *
	 * @return string|bool
	 */

	protected function _renderNew()
	{
		$tableParameter = $this->getTable();

		/* handle table */

		if ($tableParameter == 'categories')
		{
			$categoryForm = new Admin\View\CategoryForm($this->_registry, $this->_language);
			return $categoryForm->render();
		}
		if ($tableParameter == 'articles')
		{
			$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);
			return $articleForm->render();
		}
		if ($tableParameter == 'extras')
		{
			$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);
			return $extraForm->render();
		}
		if ($tableParameter == 'comments')
		{
			$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);
			return $commentForm->render();
		}
		if ($tableParameter == 'users')
		{
			$userForm = new Admin\View\UserForm($this->_registry, $this->_language);
			return $userForm->render();
		}
		if ($tableParameter == 'groups')
		{
			$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);
			return $groupForm->render();
		}
		return false;
	}

	/**
	 * render the edit
	 *
	 * @since 3.3.0
	 *
	 * @return string|bool
	 */

	protected function _renderEdit()
	{
		$tableParameter = $this->getTable();
		$idParameter = $this->getId();

		/* handle table */

		if ($tableParameter == 'categories' && $idParameter)
		{
			$categoryForm = new Admin\View\CategoryForm($this->_registry, $this->_language);
			return $categoryForm->render($idParameter);
		}
		if ($tableParameter == 'articles' && $idParameter)
		{
			$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);
			return $articleForm->render($idParameter);
		}
		if ($tableParameter == 'extras' && $idParameter)
		{
			$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);
			return $extraForm->render($idParameter);
		}
		if ($tableParameter == 'comments' && $idParameter)
		{
			$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);
			return $commentForm->render($idParameter);
		}
		if ($tableParameter == 'users' && $idParameter)
		{
			$userForm = new Admin\View\UserForm($this->_registry, $this->_language);
			return $userForm->render($idParameter);
		}
		if ($tableParameter == 'groups' && $idParameter)
		{
			$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);
			return $groupForm->render($idParameter);
		}
		if ($tableParameter == 'modules' && $idParameter)
		{
			$moduleForm = new Admin\View\ModuleForm($this->_registry, $this->_language);
			return $moduleForm->render($idParameter);
		}
		if ($tableParameter == 'settings')
		{
			$settingForm = new Admin\View\SettingForm($this->_registry, $this->_language);
			return $settingForm->render();
		}
		return false;
	}

	/**
	 * show the token error
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _errorToken() : string
	{
		$messenger = new Admin\Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'admin')
			->error($this->_language->get('token_incorrect'), $this->_language->get('error_occurred'));
	}

	/**
	 * show the access error
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _errorAccess() : string
	{
		$messenger = new Admin\Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'admin')
			->error($this->_language->get('access_no'), $this->_language->get('error_occurred'));
	}
}