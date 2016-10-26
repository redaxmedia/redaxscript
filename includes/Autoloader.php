<?php
namespace Redaxscript;

/**
 * parent class to load required class files
 *
 * @since 3.0.0
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
	 * file suffix
	 *
	 * @var string
	 */

	protected $_fileSuffix = '.php';

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $autoload key or collection of the autoload
	 */

	public function init($autoload = null)
	{
		/* handle autoload */

		if (is_string($autoload))
		{
			$autoload =
			[
				$autoload
			];
		}
		if (is_array($autoload))
		{
			$this->_autoloadArray = array_merge($this->_autoloadArray, $autoload);
		}

		/* register autoload */

		spl_autoload_register(
		[
			__CLASS__,
			'_load'
		]);
	}

	/**
	 * load the class file
	 *
	 * @since 3.0.0
	 *
	 * @param string $className name of the class
	 */

	protected function _load($className = null)
	{
		foreach ($this->_autoloadArray as $namespace => $directory)
		{
			$file = str_replace($namespace, null, $className);
			$file = str_replace('\\', '/', $file);
			$file .= $this->_fileSuffix;

			/* include as needed */

			if (file_exists($directory . '/' . $file))
			{
				include_once($directory . '/' . $file);
			}
		}
	}
}
