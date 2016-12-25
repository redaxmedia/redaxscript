<?php
namespace Redaxscript\Modules\PageCache;

use Redaxscript\Cache;
use Redaxscript\Template;

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
		'name' => 'Page cache',
		'alias' => 'PageCache',
		'author' => 'Redaxmedia',
		'description' => 'Simple page cache',
		'version' => '3.0.0'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function adminPanelNotification()
	{
		return $this->getNotification();
	}

	/**
	 * renderTemplate
	 *
	 * @since 3.0.0
	 *
	 * @return mixed
	 */

	public function renderTemplate()
	{
		/* handle notification */

		if (!is_dir($this->_configArray['directory']) && !mkdir($this->_configArray['directory']))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $this->_configArray['directory'] . $this->_language->get('point'));
		}

		/* prevent as needed */

		if ($this->_request->getPost() || $this->_registry->get('noCache'))
		{
			return false;
		}

		/* cache as needed */

		$cache = new Cache();
		$cache->init($this->_configArray['directory'], $this->_configArray['extension']);
		$bundle = $this->_registry->get('fullRoute') . '/' . $this->_registry->get('template') . '/' . $this->_registry->get('language');

		/* load from cache */

		if ($cache->validate($bundle, $this->_configArray['lifetime']))
		{
			$raw = $cache->retrieve($bundle);
			$content = preg_replace('/' . $this->_configArray['tokenPlaceholder'] . '/', $this->_registry->get('token'), $this->_uncompress($raw));
			return
			[
				'header' => function_exists('gzdeflate') ? 'content-encoding: deflate' : null,
				'content' => $this->_compress($content)
			];
		}

		/* else store to cache */

		else
		{
			$raw = Template\Tag::partial('templates/' . $this->_registry->get('template') . '/index.phtml');
			$content = preg_replace('/' . $this->_registry->get('token') . '/', $this->_configArray['tokenPlaceholder'], $raw);
			$cache->store($bundle, $this->_compress($content));
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
