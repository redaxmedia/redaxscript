<?php
namespace Redaxscript\Modules\LiveReload;

use Redaxscript\Head;
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

class LiveReload extends Config
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
		'version' => '3.3.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.3.0
	 */

	public function renderStart()
	{
		$this->_registry->set('noCache', true);
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile($this->_configArray['url']);
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.3.0
	 *
	 * @return array|bool
	 */

	public function adminPanelNotification()
	{
		$reader = new Reader();
		$content = $reader->load($this->_configArray['url']);

		/* handle notification */

		if ($content)
		{
			$this->setNotification('success', $this->_language->get('server_online', '_live_reload') . $this->_language->get('point'));
		}
		else
		{
			$this->setNotification('error', $this->_language->get('server_offline', '_live_reload') . $this->_language->get('colon') . ' ' . $this->_configArray['url']);
		}
		return $this->getNotification();
	}
}
