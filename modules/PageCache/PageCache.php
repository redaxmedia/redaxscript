<?php
namespace Redaxscript\Modules\PageCache;

use Redaxscript\Filesystem;
use Redaxscript\Module;
use function chmod;
use function is_dir;
use function mkdir;
use function preg_replace;

/**
 * high performance caching for pages
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class PageCache extends Module\Metadata
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
		'description' => 'High performance caching for pages',
		'version' => '4.2.0'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'directory' =>
		[
			'scripts' => 'cache/scripts',
			'styles' => 'cache/styles',
			'pages' => 'cache/pages'
		],
		'extension' => 'phtml',
		'lifetime' => 300,
		'tokenPlaceholder' => '%TOKEN%'
	];

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		if (!mkdir($pagesDirectory = $this->_optionArray['directory']['pages']) && !is_dir($pagesDirectory))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $this->_optionArray['directory']['pages'] . $this->_language->get('point'));
		}
		else if (!chmod($this->_optionArray['directory']['pages'], 0777))
		{
			$this->setNotification('error', $this->_language->get('directory_permission_grant') . $this->_language->get('colon') . ' ' . $this->_optionArray['directory']['pages'] . $this->_language->get('point'));
		}
		return $this->getNotificationArray();
	}

	/**
	 * templateReplace
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	public function templateReplace() : ?string
	{
		$fileSystem = new Filesystem\Filesystem();
		$stylesFileSystem = $fileSystem->copy()->init($this->_optionArray['directory']['styles']);
		$scriptsFileSystem = $fileSystem->copy()->init($this->_optionArray['directory']['scripts']);

		/* prevent as needed */

		if ($stylesFileSystem->countIterator() === 0 || $scriptsFileSystem->countIterator() === 0 || $this->_request->get('post') || $this->_registry->get('noPageCache'))
		{
			return null;
		}

		/* cache as needed */

		$cacheFilesystem = new Filesystem\Cache();
		$cacheFilesystem->init($this->_optionArray['directory']['pages'], $this->_optionArray['extension']);
		$bundle = $this->_registry->get('root') . $this->_registry->get('fullRoute') . '/' . $this->_registry->get('template') . '/' . $this->_registry->get('language');
		$token = $this->_registry->get('token');

		/* load from cache */

		if ($cacheFilesystem->validate($bundle, $this->_optionArray['lifetime']))
		{
			$raw = $cacheFilesystem->retrieve($bundle);
			$content = preg_replace('/' . $this->_optionArray['tokenPlaceholder'] . '/', $token, $raw);
			return $content;
		}

		/* store to cache */

		$rawFilesystem = new Filesystem\File();
		$rawFilesystem->init('templates' . DIRECTORY_SEPARATOR . $this->_registry->get('template'));
		$raw = $rawFilesystem->renderFile('index.phtml');
		$content = preg_replace('/' . $token . '/', $this->_optionArray['tokenPlaceholder'], $raw);
		$cacheFilesystem->store($bundle, $content);
		return $raw;
	}
}
