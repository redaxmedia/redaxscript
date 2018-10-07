<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Validator;

/**
 * children class to boot the content
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Content extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	protected function _autorun()
	{
		if ($this->_registry->get('dbStatus') === 2)
		{
			$this->_setContent();
		}
	}

	/**
	 * set the content
	 *
	 * @since 3.1.0
	 */

	protected function _setContent()
	{
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$fullRoute = $this->_registry->get('fullRoute');

		/* set by the root */

		if (!$fullRoute || ($firstParameter === 'admin' && !$secondParameter))
		{
			$this->_setTableByRoot();
			$this->_setIdByRoot();
		}

		/* else set by the parameter */

		else
		{
			$this->_setTableByParameter();
			$this->_setIdByParameter();
		}
	}

	/**
	 * set the table by root
	 *
	 * @since 3.1.0
	 */

	protected function _setTableByRoot()
	{
		$settingModel = new Model\Setting();
		$homepage = $settingModel->get('homepage');
		$table = $homepage > 0 ? 'articles' : 'categories';

		/* set the registry */

		$this->_registry->set('firstTable', $table);
		$this->_registry->set('lastTable', $table);
	}

	/**
	 * set the table by parameter
	 *
	 * @since 3.1.0
	 */

	protected function _setTableByParameter()
	{
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$thirdParameter = $this->_registry->get('thirdParameter');
		$lastParameter = $this->_registry->get('lastParameter');
		$contentModel = new Model\Content();

		/* set the registry */

		if ($firstParameter)
		{
			$this->_registry->set('firstTable', $contentModel->getTableByAlias($firstParameter));
			if ($this->_registry->get('firstTable'))
			{
				$this->_registry->set('secondTable', $contentModel->getTableByAlias($secondParameter));
			}
			if ($this->_registry->get('secondTable'))
			{
				$this->_registry->set('thirdTable', $contentModel->getTableByAlias($thirdParameter));
			}
			if ($this->_registry->get('lastParameter'))
			{
				$this->_registry->set('lastTable', $contentModel->getTableByAlias($lastParameter));
			}
		}
	}

	/**
	 * set the id
	 *
	 * @since 3.1.0
	 *
	 * @param array $whereArray
	 */

	protected function _setId(array $whereArray = [])
	{
		$aliasValidator = new Validator\Alias();
		$firstParameter = $this->_registry->get('firstParameter');
		$lastTable = $this->_registry->get('lastTable');

		/* set the registry */

		if ($firstParameter === 'admin' || !$aliasValidator->validate($firstParameter, 'system'))
		{
			if ($lastTable === 'categories')
			{
				$category = Db::forTablePrefix('categories')->where($whereArray)->findOne();
				$this->_registry->set('categoryId', $category->id);
				$this->_registry->set('lastId', $category->id);
			}
			if ($lastTable === 'articles')
			{
				$article = Db::forTablePrefix('articles')->where($whereArray)->findOne();
				$this->_registry->set('articleId', $article->id);
				$this->_registry->set('lastId', $article->id);
			}
		}
	}

	/**
	 * set the id by root
	 *
	 * @since 3.3.0
	 */

	protected function _setIdByRoot()
	{
		$settingModel = new Model\Setting();
		$order = $settingModel->get('order');
		$lastTable = $this->_registry->get('lastTable');
		$content = Db::forTablePrefix($lastTable);

		/* handle order */

		if ($order === 'asc')
		{
			$lastRank = $content->min('rank');
		}
		if ($order === 'desc')
		{
			$lastRank = $content->max('rank');
		}

		/* last rank */

		if ($lastRank)
		{
			$this->_setId(
			[
				'rank' => $lastRank,
				'status' => 1
			]);
		}
	}

	/**
	 * set the id by parameter
	 *
	 * @since 3.1.0
	 */

	protected function _setIdByParameter()
	{
		$lastParameter = $this->_registry->get('lastParameter');
		if ($lastParameter)
		{
			$this->_setId(
			[
				'alias' => $lastParameter,
				'status' => 1
			]);
		}
	}
}
