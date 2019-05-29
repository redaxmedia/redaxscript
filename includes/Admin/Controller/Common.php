<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use function method_exists;

/**
 * children class to handle common
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Common extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @param string $action action to process
	 *
	 * @return string
	 */

	public function process(string $action = null) : string
	{
		$table = $this->_registry->get('tableParameter');
		$id = $this->_registry->get('idParameter');
		$alias = $this->_registry->get('aliasParameter');

		/* handle publish */

		if ($action === 'publish')
		{
			if ($this->_publish($table, $id))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table, $id),
					'timeout' => 0
				]);
			}
		}

		/* handle unpublish */

		if ($action === 'unpublish')
		{
			if ($this->_unpublish($table, $id))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table, $id),
					'timeout' => 0
				]);
			}
		}

		/* handle enable */

		if ($action === 'enable')
		{
			if ($this->_enable($table, $id))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table, $id),
					'timeout' => 0
				]);
			}
		}

		/* handle disable */

		if ($action === 'disable')
		{
			if ($this->_disable($table, $id))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table, $id),
					'timeout' => 0
				]);
			}
		}

		/* handle install */

		if ($action === 'install')
		{
			if ($this->_install($table, $alias))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table),
					'timeout' => 0
				]);
			}
		}

		/* handle uninstall */

		if ($action === 'uninstall')
		{
			if ($this->_uninstall($table, $alias))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table),
					'timeout' => 0
				]);
			}
		}

		/* handle delete */

		if ($action === 'delete')
		{
			if ($this->_delete($table, $id))
			{
				return $this->_success(
				[
					'route' => $this->_getRoute($table, $id),
					'timeout' => 0
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $this->_getRoute($table, $id)
		]);
	}

	/**
	 * publish the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 *
	 * @return bool
	 */

	protected function _publish(string $table = null, int $id = null) : bool
	{
		if ($table === 'categories')
		{
			$categoryModel = new Admin\Model\Category();
			$articleModel = new Admin\Model\Article();
			$commentModel = new Admin\Model\Comment();
			return $categoryModel->publishById($id) && $articleModel->publishByCategory($id) && $commentModel->publishByCategory($id);
		}
		if ($table === 'articles')
		{
			$articleModel = new Admin\Model\Article();
			$commentModel = new Admin\Model\Comment();
			return $articleModel->publishById($id) && $commentModel->publishByArticle($id);
		}
		if ($table === 'extras')
		{
			$extraModel = new Admin\Model\Extra();
			return $extraModel->publishById($id);
		}
		if ($table === 'comments')
		{
			$commentModel = new Admin\Model\Comment();
			return $commentModel->publishById($id);
		}
		return false;
	}

	/**
	 * unpublish the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 *
	 * @return bool
	 */

	protected function _unpublish(string $table = null, int $id = null) : bool
	{
		if ($table === 'categories')
		{
			$categoryModel = new Admin\Model\Category();
			$articleModel = new Admin\Model\Article();
			$commentModel = new Admin\Model\Comment();
			return $categoryModel->unpublishById($id) && $articleModel->unpublishByCategory($id) && $commentModel->unpublishByCategory($id);
		}
		if ($table === 'articles')
		{
			$articleModel = new Admin\Model\Article();
			$commentModel = new Admin\Model\Comment();
			return $articleModel->unpublishById($id) && $commentModel->unpublishByArticle($id);
		}
		if ($table === 'extras')
		{
			$extraModel = new Admin\Model\Extra();
			return $extraModel->unpublishById($id);
		}
		if ($table === 'comments')
		{
			$commentModel = new Admin\Model\Comment();
			return $commentModel->unpublishById($id);
		}
		return false;
	}

	/**
	 * enable the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 *
	 * @return bool
	 */

	protected function _enable(string $table = null, int $id = null) : bool
	{
		if ($table === 'groups')
		{
			$groupModel = new Admin\Model\Group();
			return $groupModel->enableById($id);
		}
		if ($table === 'users')
		{
			$userModel = new Admin\Model\User();
			return $userModel->enableById($id);
		}
		if ($table === 'modules')
		{
			$moduleModel = new Admin\Model\Module();
			return $moduleModel->enableById($id);
		}
		return false;
	}

	/**
	 * disable the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 *
	 * @return bool
	 */

	protected function _disable(string $table = null, int $id = null) : bool
	{
		if ($table === 'groups')
		{
			$groupModel = new Admin\Model\Group();
			return $groupModel->disableById($id);
		}
		if ($table === 'users')
		{
			$userModel = new Admin\Model\User();
			return $userModel->disableById($id);
		}
		if ($table === 'modules')
		{
			$moduleModel = new Admin\Model\Module();
			return $moduleModel->disableById($id);
		}
		return false;
	}

	/**
	 * install the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param string $alias alias of the item
	 *
	 * @return bool
	 */

	protected function _install(string $table = null, string $alias = null) : bool
	{
		if ($table === 'modules')
		{
			$moduleClass = 'Redaxscript\Modules\\' . $alias . '\\' . $alias;
			if (method_exists($moduleClass, 'install'))
			{
				$module = new $moduleClass($this->_registry, $this->_request, $this->_language, $this->_config);
				return $module->install();
			}
		}
		return false;
	}

	/**
	 * uninstall the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param string $alias alias of the item
	 *
	 * @return bool
	 */

	protected function _uninstall(string $table = null, string $alias = null) : bool
	{
		if ($table === 'modules')
		{
			$moduleClass = 'Redaxscript\Modules\\' . $alias . '\\' . $alias;
			if (method_exists($moduleClass, 'uninstall'))
			{
				$module = new $moduleClass($this->_registry, $this->_request, $this->_language, $this->_config);
				return $module->uninstall();
			}
		}
		return false;
	}

	/**
	 * delete the item
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 *
	 * @return bool
	 */

	protected function _delete(string $table = null, int $id = null) : bool
	{
		if ($table === 'categories')
		{
			$categoryModel = new Admin\Model\Category();
			$articleModel = new Admin\Model\Article();
			$commentModel = new Admin\Model\Comment();
			return $commentModel->deleteByCategory($id) && $articleModel->deleteByCategory($id) && $categoryModel->deleteById($id);
		}
		if ($table === 'articles')
		{
			$articleModel = new Admin\Model\Article();
			$commentModel = new Admin\Model\Comment();
			return $commentModel->deleteByArticle($id) && $articleModel->deleteById($id);
		}
		if ($table === 'extras')
		{
			$extraModel = new Admin\Model\Extra();
			return $extraModel->deleteById($id);
		}
		if ($table === 'comments')
		{
			$commentModel = new Admin\Model\Comment();
			return $commentModel->deleteById($id);
		}
		if ($table === 'groups')
		{
			$groupModel = new Admin\Model\Group();
			return $groupModel->deleteById($id);
		}
		if ($table === 'users')
		{
			$userModel = new Admin\Model\User();
			return $userModel->deleteById($id);
		}
		return false;
	}

	/**
	 * get the route
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 *
	 * @return string
	 */

	protected function _getRoute(string $table = null, int $id = null) : string
	{
		if ($this->_registry->get($table . 'Edit'))
		{
			if ($id)
			{
				return 'admin/view/' . $table . '#row-' . $id;
			}
			if ($table)
			{
				return 'admin/view/' . $table;
			}
		}
		return 'admin';
	}
}
