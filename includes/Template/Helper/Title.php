<?php
namespace Redaxscript\Template\Helper;

use Redaxscript\Db;

/**
 * helper class to provide a title helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Title extends HelperAbstract
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
		$useTitle = $this->_registry->get('useTitle');
		$settingDivider = Db::getSetting('divider');
		$settingTitle = Db::getSetting('title');

		/* find title */

		if ($useTitle)
		{
			$title = $useTitle;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$title = $content->title;
			if ($title)
			{
				$title .= $settingDivider . $settingTitle;
			}
		}

		/* handle title */

		if ($title)
		{
			return $title;
		}
		return $settingTitle;
	}
}
