<?php
namespace Redaxscript\Template\Helper;

use Redaxscript\Db;

/**
 * helper class to provide a keywords helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Keywords extends HelperAbstract
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
		$useKeywords = $this->_registry->get('useKeywords');
		$settingKeywords = Db::getSetting('keywords');

		/* find keywords */

		if ($useKeywords)
		{
			$keywords = $useKeywords;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$keywords = $content->keywords;

			/* handle parent */

			if (!$keywords)
			{
				$parentId = $content->category ? $content->category : $content->parent;
				$parent = Db::forTablePrefix('categories')->whereIdIs($parentId)->whereNull('access')->findOne();
				$keywords = $parent->keywords;
			}
		}

		/* handle keywords */

		if ($keywords)
		{
			return $keywords;
		}
		return $settingKeywords;
	}
}
