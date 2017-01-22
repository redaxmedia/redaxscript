<?php
namespace Redaxscript\Template\Helper;

use Redaxscript\Db;

/**
 * helper class to provide a description helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Description extends HelperAbstract
{
	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');
		$useDescription = $this->_registry->get('useDescription');
		$settingDescription = Db::getSetting('description');

		/* find description */

		if ($useDescription)
		{
			$description = $useDescription;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$description = $content->description;

			/* handle parent */

			if (!$description)
			{
				$parentId = $content->category ? $content->category : $content->parent;
				$parent = Db::forTablePrefix('categories')->whereIdIs($parentId)->whereNull('access')->findOne();
				$description = $parent->description;
			}
		}

		/* handle description */

		if ($description)
		{
			return $description;
		}
		return $settingDescription;
	}
}
