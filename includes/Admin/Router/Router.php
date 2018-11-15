<?php
namespace Redaxscript\Admin\Router;

use Redaxscript\Admin;
use Redaxscript\Header;
use Redaxscript\Module;
use Redaxscript\Router\RouterAbstract;
use function in_array;

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
		$adminParameter = $this->getAdmin();

		/* handle break */

		if ($this->_registry->get('adminRouterBreak'))
		{
			Header::responseCode(202);
		}

		/* handle guard */

		if ($adminParameter && ($this->_tokenGuard() || $this->_authGuard()))
		{
			Header::responseCode(403);
		}
		return (bool)$this->_registry->get('adminRouterBreak');
	}

	/**
	 * route the content
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	public function routeContent() : ?string
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

			if (!$adminParameter || $adminParameter === 'view' && $tableParameter === 'users' || $this->_registry->get('cronUpdate'))
			{
				$this->_updateLast();
			}

			/* handle post */

			if ($this->_request->getPost('Redaxscript\Admin\View\CategoryForm'))
			{
				return $this->_processCategory();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ArticleForm'))
			{
				return $this->_processArticle();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ExtraForm'))
			{
				return $this->_processExtra();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\CommentForm'))
			{
				return $this->_processComment();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\UserForm'))
			{
				return $this->_processUser();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\GroupForm'))
			{
				return $this->_processGroup();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ModuleForm'))
			{
				return $this->_processModule();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\SettingForm'))
			{
				return $this->_processSetting();
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
			return $this->_processCommon();
		}
		if ($this->_registry->get('adminRouterBreak'))
		{
			return '<!-- adminRouterBreak -->';
		}
		return null;
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
	 * update last
	 *
	 * @since 4.00
	 */

	protected function _updateLast()
	{
		$userModel = new Admin\Model\User();
		if ($this->_registry->get('myId'))
		{
			$userModel->updateLastById($this->_registry->get('myId'), $this->_registry->get('now'));
		}
	}

	/**
	 * process the category
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processCategory() : string
	{
		$categoryController = new Admin\Controller\Category($this->_registry, $this->_request, $this->_language, $this->_config);
		return $categoryController->process($this->_request->getPost('Redaxscript\Admin\View\CategoryForm'));
	}

	/**
	 * process the article
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processArticle() : string
	{
		$articleController = new Admin\Controller\Article($this->_registry, $this->_request, $this->_language, $this->_config);
		return $articleController->process($this->_request->getPost('Redaxscript\Admin\View\ArticleForm'));
	}

	/**
	 * process the extra
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processExtra() : string
	{
		$extraController = new Admin\Controller\Extra($this->_registry, $this->_request, $this->_language, $this->_config);
		return $extraController->process($this->_request->getPost('Redaxscript\Admin\View\ExtraForm'));
	}

	/**
	 * process the comment
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processComment() : string
	{
		$commentController = new Admin\Controller\Comment($this->_registry, $this->_request, $this->_language, $this->_config);
		return $commentController->process($this->_request->getPost('Redaxscript\Admin\View\CommentForm'));
	}

	/**
	 * process the user
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processUser() : string
	{
		$userController = new Admin\Controller\User($this->_registry, $this->_request, $this->_language, $this->_config);
		return $userController->process($this->_request->getPost('Redaxscript\Admin\View\UserForm'));
	}

	/**
	 * process the group
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processGroup() : string
	{
		$groupController = new Admin\Controller\Group($this->_registry, $this->_request, $this->_language, $this->_config);
		return $groupController->process($this->_request->getPost('Redaxscript\Admin\View\GroupForm'));
	}

	/**
	 * process the module
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processModule() : string
	{
		$moduleController = new Admin\Controller\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		return $moduleController->process($this->_request->getPost('Redaxscript\Admin\View\ModuleForm'));
	}

	/**
	 * process the setting
	 *
	 * @since 4.00
	 *
	 * @return string
	 */

	protected function _processSetting() : string
	{
		$settingController = new Admin\Controller\Setting($this->_registry, $this->_request, $this->_language, $this->_config);
		return $settingController->process($this->_request->getPost('Redaxscript\Admin\View\SettingForm'));
	}

	/**
	 * process the common
	 *
	 * @since 4.00
	 *
	 * @return string|null
	 */

	protected function _processCommon() : ?string
	{
		$adminParameter = $this->getAdmin();
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
			$commonController = new Admin\Controller\Common($this->_registry, $this->_request, $this->_language, $this->_config);
			return $commonController->process($adminParameter);
		}
		return null;
	}

	/**
	 * render the view
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	protected function _renderView() : ?string
	{
		$tableParameter = $this->getTable();

		/* handle table */

		if ($tableParameter === 'categories')
		{
			$categoryTable = new Admin\View\CategoryTable($this->_registry, $this->_language);
			return $categoryTable->render();
		}
		if ($tableParameter === 'articles')
		{
			$articleTable = new Admin\View\ArticleTable($this->_registry, $this->_language);
			return $articleTable->render();
		}
		if ($tableParameter === 'extras')
		{
			$extraTable = new Admin\View\ExtraTable($this->_registry, $this->_language);
			return $extraTable->render();
		}
		if ($tableParameter === 'comments')
		{
			$commentTable = new Admin\View\CommentTable($this->_registry, $this->_language);
			return $commentTable->render();
		}
		if ($tableParameter === 'users')
		{
			$userTable = new Admin\View\UserTable($this->_registry, $this->_language);
			return $userTable->render();
		}
		if ($tableParameter === 'groups')
		{
			$groupTable = new Admin\View\GroupTable($this->_registry, $this->_language);
			return $groupTable->render();
		}
		if ($tableParameter === 'modules')
		{
			$moduleTable = new Admin\View\ModuleTable($this->_registry, $this->_language);
			return $moduleTable->render();
		}
		return null;
	}

	/**
	 * render the new
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	protected function _renderNew() : ?string
	{
		$tableParameter = $this->getTable();

		/* handle table */

		if ($tableParameter === 'categories')
		{
			$categoryForm = new Admin\View\CategoryForm($this->_registry, $this->_language);
			return $categoryForm->render();
		}
		if ($tableParameter === 'articles')
		{
			$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);
			return $articleForm->render();
		}
		if ($tableParameter === 'extras')
		{
			$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);
			return $extraForm->render();
		}
		if ($tableParameter === 'comments')
		{
			$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);
			return $commentForm->render();
		}
		if ($tableParameter === 'users')
		{
			$userForm = new Admin\View\UserForm($this->_registry, $this->_language);
			return $userForm->render();
		}
		if ($tableParameter === 'groups')
		{
			$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);
			return $groupForm->render();
		}
		return null;
	}

	/**
	 * render the edit
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	protected function _renderEdit() : ?string
	{
		$tableParameter = $this->getTable();
		$idParameter = $this->getId();

		/* handle table */

		if ($tableParameter === 'categories' && $idParameter)
		{
			$categoryForm = new Admin\View\CategoryForm($this->_registry, $this->_language);
			return $categoryForm->render($idParameter);
		}
		if ($tableParameter === 'articles' && $idParameter)
		{
			$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);
			return $articleForm->render($idParameter);
		}
		if ($tableParameter === 'extras' && $idParameter)
		{
			$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);
			return $extraForm->render($idParameter);
		}
		if ($tableParameter === 'comments' && $idParameter)
		{
			$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);
			return $commentForm->render($idParameter);
		}
		if ($tableParameter === 'users' && $idParameter)
		{
			$userForm = new Admin\View\UserForm($this->_registry, $this->_language);
			return $userForm->render($idParameter);
		}
		if ($tableParameter === 'groups' && $idParameter)
		{
			$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);
			return $groupForm->render($idParameter);
		}
		if ($tableParameter === 'modules' && $idParameter)
		{
			$moduleForm = new Admin\View\ModuleForm($this->_registry, $this->_language);
			return $moduleForm->render($idParameter);
		}
		if ($tableParameter === 'settings')
		{
			$settingForm = new Admin\View\SettingForm($this->_registry, $this->_language);
			return $settingForm->render();
		}
		return null;
	}

	/**
	 * messenger factory
	 *
	 * @since 4.0.0
	 *
	 * @return Admin\Messenger
	 */

	protected function _messengerFactory() : Admin\Messenger
	{
		return new Admin\Messenger($this->_registry);
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
		$messenger = $this->_messengerFactory();
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
		$messenger = $this->_messengerFactory();
		return $messenger
			->setRoute($this->_language->get('back'), 'admin')
			->error($this->_language->get('access_no'), $this->_language->get('error_occurred'));
	}
}
