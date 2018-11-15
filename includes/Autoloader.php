<?php
namespace Redaxscript;

use function is_file;
use function spl_autoload_register;
use function str_replace;

/**
 * parent class to load required class files
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Autoloader
 * @author Henry Ruhs
 */

class Autoloader
{
	/**
	 * array of the autoload
	 *
	 * @var array
	 */

	protected $_autoloadArray =
	[
		'Redaxscript' => 'includes',
		'Redaxscript\Modules' => 'modules',
		'libraries'
	];

	/**
	 * file extension
	 *
	 * @var string
	 */

	protected $_fileExtension = '.php';

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $autoload key or collection of the autoload
	 */

	public function init($autoload = null)
	{
		if ($autoload)
		{
			$this->_autoloadArray = (array)$autoload;
		}

		/* register autoload */

		spl_autoload_register(
		[
			self::class,
			'_load'
		]);
	}

	/**
	 * load the class file
	 *
	 * @since 3.1.0
	 *
	 * @param string $className name of the class
	 */

	protected function _load(string $className = null)
	{
		foreach ($this->_autoloadArray as $namespace => $directory)
		{
			$file = $this->_getFile($className, $namespace);
			if (is_file($directory . DIRECTORY_SEPARATOR . $file))
			{
				include_once($directory . DIRECTORY_SEPARATOR . $file);
			}
		}
	}

	/**
	 * get the file
	 *
	 * @since 3.0.0
	 *
	 * @param string $className name of the class
	 * @param string $namespace
	 *
	 * @return string
	 */

	protected function _getFile(string $className = null, string $namespace = null) : string
	{
		$searchArray =
		[
			$namespace,
			'\\'
		];
		$replaceArray =
		[
			null,
			DIRECTORY_SEPARATOR
		];
		return str_replace($searchArray, $replaceArray, $className) . $this->_fileExtension;
	}
}
