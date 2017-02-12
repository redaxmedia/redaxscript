<?php
namespace Redaxscript\Modules\CallHome;

use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Reader;

/**
 * provide version and news updates
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CallHome extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Call home',
		'alias' => 'CallHome',
		'author' => 'Redaxmedia',
		'description' => 'Provide version and news updates',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token') && $this->_registry->get('firstParameter') === 'admin')
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('//google-analytics.com/analytics.js')
				->appendFile('modules/CallHome/assets/scripts/init.js')
				->appendFile('modules/CallHome/dist/scripts/call-home.min.js');
		}
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function adminPanelNotification()
	{
		$outputArray = [];
		$reader = new Reader();
		$aliasFilter = new Filter\Alias();
		$version = $aliasFilter->sanitize($this->_language->get('version', '_package'));

		/* load result */

		$urlVersion = 'https://service.redaxscript.com/version/' . $version;
		$urlNews = 'https://service.redaxscript.com/news/' . $version;
		$resultVersion = $reader->loadJSON($urlVersion)->getArray();
		$resultNews = $reader->loadJSON($urlNews)->getArray();

		/* merge as needed */

		if (is_array($resultVersion))
		{
			$outputArray = array_merge_recursive($outputArray, $resultVersion);
		}
		if (is_array($resultNews))
		{
			$outputArray = array_merge_recursive($outputArray, $resultNews);
		}
		return $outputArray;
	}
}
