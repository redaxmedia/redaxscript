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

	public function autorun() : void
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

	protected function _setContent() : void
	{
		$aliasValidator = new Validator\Alias();
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$lastParameter = $this->_registry->get('lastParameter');
		$lastSubParameter = $this->_registry->get('lastSubParameter');
		$isRoot = !$lastParameter && !$lastSubParameter;
		$isAdmin = $firstParameter === 'admin' && !$secondParameter;

		/* set by the root */

		if ($isRoot || $isAdmin)
		{
			$this->_setTableByRoot();
			$this->_setIdByRoot();
		}

		/* else set by the parameter */

		else if ($aliasValidator->validate($firstParameter))
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

	protected function _setTableByRoot() : void
	{
		$settingModel = new Model\Setting();
		$homepageId = $settingModel->get('homepage');
		$table = $homepageId > 0 ? 'articles' : 'categories';

		/* set the registry */

		$this->_registry->set('firstTable', $table);
		$this->_registry->set('lastTable', $table);
	}

	/**
	 * set the table by parameter
	 *
	 * @since 3.1.0
	 */

	protected function _setTableByParameter() : void
	{
		$contentModel = new Model\Content();
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$thirdParameter = $this->_registry->get('thirdParameter');

		/* set the registry */

		if ($thirdParameter)
		{
			$this->_registry->set('thirdTable', $contentModel->getTableByAlias($thirdParameter));
			if ($this->_registry->get('thirdTable'))
			{
				$this->_registry->set('lastTable', $this->_registry->get('thirdTable'));
				$this->_registry->set('secondTable', $contentModel->getTableByAlias($secondParameter));
				if ($this->_registry->get('secondTable'))
				{
					$this->_registry->set('firstTable', $contentModel->getTableByAlias($firstParameter));
				}
			}
		}
		else if ($secondParameter)
		{
			$this->_registry->set('secondTable', $contentModel->getTableByAlias($secondParameter));
			if ($this->_registry->get('secondTable'))
			{
				$this->_registry->set('lastTable', $this->_registry->get('secondTable'));
				$this->_registry->set('firstTable', $contentModel->getTableByAlias($firstParameter));
			}
		}
		else if ($firstParameter)
		{
			$this->_registry->set('firstTable', $contentModel->getTableByAlias($firstParameter));
			if ($this->_registry->get('firstTable'))
			{
				$this->_registry->set('lastTable', $this->_registry->get('firstTable'));
			}
		}
	}

	/**
	 * set the id by root
	 *
	 * @since 3.3.0
	 */

	protected function _setIdByRoot() : void
	{
		$settingModel = new Model\Setting();
		$homepageId = $settingModel->get('homepage');
		$order = $settingModel->get('order');
		$lastTable = $this->_registry->get('lastTable');
		$content = Db::forTablePrefix($lastTable);

		/* set by homepage */

		if ($homepageId > 0)
		{
			$this->_registry->set('articleId', $homepageId);
			$this->_registry->set('lastId', $homepageId);
		}

		/* set by order */

		else if ($order === 'asc')
		{
			$this->_setId($lastTable,
			[
				'rank' => $content->min('rank')
			]);
		}
		else if ($order === 'desc')
		{
			$this->_setId($lastTable,
			[
				'rank' => $content->max('rank')
			]);
		}
	}

	/**
	 * set the id by parameter
	 *
	 * @since 3.1.0
	 */

	protected function _setIdByParameter() : void
	{
		$categoryModel = new Model\Category();
		$firstTable = $this->_registry->get('firstTable');
		$secondTable = $this->_registry->get('secondTable');
		$thirdTable = $this->_registry->get('thirdTable');
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$thirdParameter = $this->_registry->get('thirdParameter');

		/* set by third table */

		if ($thirdTable === 'articles')
		{
			$this->_setId('articles',
			[
				'alias' => $thirdParameter,
				'category' => $categoryModel->query()->where('alias', $secondParameter)->findOne()->id
			]);
		}

		/* set by second table */

		else if ($secondTable === 'categories')
		{
			$this->_setId('categories',
			[
				'alias' => $secondParameter,
				'parent' => $categoryModel->query()->where('alias', $firstParameter)->findOne()->id
			]);
		}
		else if ($secondTable === 'articles')
		{
			$this->_setId('articles',
			[
				'alias' => $secondParameter,
				'category' => $categoryModel->query()->where('alias', $firstParameter)->findOne()->id
			]);
		}

		/* set by first table */

		else if ($firstTable === 'categories')
		{
			$this->_setId('categories',
			[
				'alias' => $firstParameter
			], 'parent');
		}
		else if ($firstTable === 'articles')
		{
			$this->_setId('articles',
			[
				'alias' => $firstParameter
			], 'category');
		}
	}

	/**
	 * set the id
	 *
	 * @since 3.1.0
	 *
	 * @param string $table
	 * @param array $whereArray
	 * @param string $whereNull
	 */

	protected function _setId(string $table = null, array $whereArray = [], string $whereNull = null) : void
	{
		$categoryModel = new Model\Category();
		$articleModel = new Model\Article();

		/* set the registry */

		if ($table === 'categories')
		{
			$category = $categoryModel->query()->where($whereArray)->where('status', 1);
			if ($whereNull)
			{
				$category->whereNull($whereNull);
			}
			$category = $category->findOne();
			$this->_registry->set('categoryId', $category->id);
			$this->_registry->set('lastId', $category->id);
		}
		if ($table === 'articles')
		{
			$article = $articleModel->query()->where($whereArray)->where('status', 1);
			if ($whereNull)
			{
				$article->whereNull($whereNull);
			}
			$article = $article->findOne();
			$this->_registry->set('articleId', $article->id);
			$this->_registry->set('lastId', $article->id);
		}
	}
}
