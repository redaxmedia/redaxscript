<?php
namespace Redaxscript\Modules\LiveReload;

use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Reader;

/**
 * live reload
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class LiveReload extends Module\Notification
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
		'description' => 'Launch a local PHP server with live reload',
		'version' => '4.0.0',
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
		$this->_registry->set('noCache', true);
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
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		$reader = new Reader();
		$reader->init();
		$content = $reader->load($this->_optionArray['url']);

		/* handle notification */

		if ($content)
		{
			$this->setNotification('success', $this->_language->get('server_online', '_live_reload') . $this->_language->get('point'));
		}
		else
		{
			$this->setNotification('error', $this->_language->get('server_offline', '_live_reload') . $this->_language->get('colon') . ' ' . $this->_optionArray['url']);
		}
		return $this->getNotification();
	}
}
