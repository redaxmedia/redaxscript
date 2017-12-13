<?php
namespace Redaxscript\Template\Helper;

use Redaxscript\Db;
use Redaxscript\Model;

/**
 * helper class to provide a robots helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Robots extends HelperAbstract
{
	/**
	 * array of the robots
	 *
	 * @var array
	 */

	protected static $_robotArray =
	[
		'none',
		'all',
		'index',
		'follow',
		'index_no',
		'follow_no'
	];

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$settingModel = new Model\Setting();
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');
		$contentError = $this->_registry->get('contentError');
		$useRobots = $this->_registry->get('useRobots');
		$settingRobots = $settingModel->get('robots');

		/* find robots */

		if ($useRobots)
		{
			$robots = $useRobots;
		}
		else if ($contentError)
		{
			$robots = 0;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->whereNull('access')->findOne();
			$robots = $content->robots;

			/* handle parent */

			if (!$robots)
			{
				$parentId = $content->category ? $content->category : $content->parent;
				$parent = Db::forTablePrefix('categories')->whereIdIs($parentId)->whereNull('access')->findOne();
				$robots = $parent->robots;
			}
		}

		/* handle robots */

		if (is_array(self::$_robotArray) && array_key_exists($robots, self::$_robotArray))
		{
			return self::$_robotArray[$robots];
		}
		return self::$_robotArray[$settingRobots];
	}
}
