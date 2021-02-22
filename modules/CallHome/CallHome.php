<?php
namespace Redaxscript\Modules\CallHome;

use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Reader;

/**
 * get version updates and latest news
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CallHome extends Module\Metadata
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Call Home',
		'alias' => 'CallHome',
		'author' => 'Redaxmedia',
		'description' => 'Get version updates and latest news',
		'version' => '4.6.0',
		'license' => 'MIT'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token') && $this->_registry->get('firstParameter') === 'admin')
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://google-analytics.com/analytics.js',
					'modules/CallHome/assets/scripts/init.js',
					'modules/CallHome/dist/scripts/call-home.min.js'
				]);
		}
	}

	/**
	 * adminNotification
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		$reader = new Reader();
		$reader->init(
		[
			'curl' =>
			[
				CURLOPT_TIMEOUT_MS => 500
			]
		]);
		$aliasFilter = new Filter\Alias();
		$version = $aliasFilter->sanitize($this->_language->get('_package')['version']);

		/* load result */

		$urlVersion = $this->_language->get('_package')['service'] . '/version/' . $version;
		$urlNews = $this->_language->get('_package')['service'] . '/news/' . $version;
		$versionArray = $reader->loadJSON($urlVersion)->getArray();
		$newsArray = $reader->loadJSON($urlNews)->getArray();

		/* process version */

		foreach ($versionArray as $version)
		{
			foreach ($version as $type => $message)
			{
				$this->setNotification($type, $message);
			}
		}

		/* process news */

		foreach ($newsArray as $news)
		{
			foreach ($news as $type => $message)
			{
				$this->setNotification($type, $message);
			}
		}
		return $this->getNotificationArray();
	}
}
