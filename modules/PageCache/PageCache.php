<?php
namespace Redaxscript\Modules\PageCache;

use Redaxscript\Filesystem;

/**
 * simple page cache
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class PageCache extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Page Cache',
		'alias' => 'PageCache',
		'author' => 'Redaxmedia',
		'description' => 'Simple page cache',
		'version' => '4.0.0'
	];

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array|bool
	 */

	public function adminNotification()
	{
		if (!is_dir($this->_configArray['cacheDirectory']) && !mkdir($this->_configArray['cacheDirectory']))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $this->_configArray['cacheDirectory'] . $this->_language->get('point'));
		}
		else if (!chmod($this->_configArray['cacheDirectory'], 0777))
		{
			$this->setNotification('error', $this->_language->get('directory_permission_grant') . $this->_language->get('colon') . ' ' . $this->_configArray['cacheDirectory'] . $this->_language->get('point'));
		}
		return $this->getNotification();
	}

	/**
	 * renderTemplate
	 *
	 * @since 3.0.0
	 *
	 * @return array|bool
	 */

	public function renderTemplate()
	{
		/* prevent as needed */

		if ($this->_request->getPost() || $this->_registry->get('noCache'))
		{
			return false;
		}

		/* cache as needed */

		$cacheFilesystem = new Filesystem\Cache();
		$cacheFilesystem->init($this->_configArray['cacheDirectory'], $this->_configArray['extension']);
		$bundle = $this->_registry->get('root') . $this->_registry->get('fullRoute') . '/' . $this->_registry->get('template') . '/' . $this->_registry->get('language');
		$token = $this->_registry->get('token');

		/* load from cache */

		if ($cacheFilesystem->validate($bundle, $this->_configArray['lifetime']))
		{
			$raw = $this->_uncompress($cacheFilesystem->retrieve($bundle));
			$content = preg_replace('/' . $this->_configArray['tokenPlaceholder'] . '/', $token, $raw);
			return
			[
				'header' => function_exists('gzdeflate') ? 'content-encoding: deflate' : null,
				'content' => $this->_compress($content)
			];
		}

		/* else store to cache */

		else
		{
			$rawFilesystem = new Filesystem\File();
			$rawFilesystem->init('templates/' . $this->_registry->get('template'));
			$raw = $rawFilesystem->renderFile('index.phtml');
			$content = preg_replace('/' . $token . '/', $this->_configArray['tokenPlaceholder'], $raw);
			$cacheFilesystem->store($bundle, $this->_compress($content));
			return
			[
				'content' => $raw
			];
		}
	}

	/**
	 * compress
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 *
	 * @return string
	 */

	public function _compress($content = null)
	{
		return function_exists('gzdeflate') ? gzdeflate($content) : $content;
	}

	/**
	 * uncompress
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 *
	 * @return string
	 */

	public function _uncompress($content = null)
	{
		return function_exists('gzinflate') ? gzinflate($content) : $content;
	}
}
