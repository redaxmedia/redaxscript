<?php
namespace Redaxscript\Template\Helper;

use Redaxscript\Model;
use function is_numeric;
use function is_string;

/**
 * helper class to provide a canonical helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Canonical extends HelperAbstract
{
	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function process() : ?string
	{
		$articleModel = new Model\Article();
		$contentModel = new Model\Content();
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$root = $this->_registry->get('root');
		$route = null;

		/* find route */

		if ($lastTable === 'categories')
		{
			$articles = $articleModel->query()->where('category', $lastId);
			$articlesTotal = $articles->count();
			if ($articlesTotal === 1)
			{
				$lastTable = 'articles';
				$lastId = $articles->findOne()->id;
			}
		}
		if (is_string($lastTable) && is_numeric($lastId))
		{
			$route = $contentModel->getRouteByTableAndId($lastTable, $lastId);
		}

		/* handle route */

		if ($route)
		{
			return $root . '/' . $parameterRoute . $route;
		}
		return $root;
	}
}
