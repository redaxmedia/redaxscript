<?php
namespace Redaxscript\Modules\LiveReload;

use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Reader;

/**
 * live reload for developers
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class LiveReload extends Module\Metadata
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Live Reload',
		'alias' => 'LiveReload',
		'author' => 'Redaxmedia',
		'description' => 'Live reload for developers',
		'version' => '4.1.1',
		'access' => '[1]'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'url' => 'http://localhost:7000/livereload.js'
	];

	/**
	 * renderStart
	 *
	 * @since 3.3.0
	 */

	public function renderStart() : void
	{
		$this->_registry->set('noAssetCache', true);
		$this->_registry->set('noPageCache', true);
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile($this->_optionArray['url']);
	}

	/**
	 * adminNotification
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		$reader = new Reader();
		$reader->init();
		$content = $reader->load($this->_optionArray['url']);

		/* handle notification */

		if ($content)
		{
			$this->setNotification('success', $this->_language->get('_live_reload')['server_online'] . $this->_language->get('point'));
		}
		else
		{
			$this->setNotification('error', $this->_language->get('_live_reload')['server_offline'] . $this->_language->get('colon') . ' ' . $this->_optionArray['url']);
		}
		return $this->getNotificationArray();
	}
}
