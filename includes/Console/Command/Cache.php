<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Filesystem;

/**
 * children class to execute the cache command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Cache extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'cache' =>
		[
			'description' => 'Cache command',
			'argumentArray' =>
			[
				'clear' =>
				[
					'description' => 'Clear the cache',
					'optionArray' =>
					[
						'directory' =>
						[
							'description' => 'Required directory of the cache'
						],
						'extension' =>
						[
							'description' => 'Required extension of the cache files'
						],
						'bundle' =>
						[
							'description' => 'Optional key or collection of the bundle'
						]
					]
				],
				'clear-invalid' =>
				[
					'description' => 'Clear the invalid cache',
					'optionArray' =>
					[
						'directory' =>
						[
							'description' => 'Required directory of the cache'
						],
						'extension' =>
						[
							'description' => 'Required extension of the cache files'
						],
						'lifetime' =>
						[
							'description' => 'Optional lifetime of the bundle'
						]
					]
				]
			]
		]
	];

	/**
	 * run the command
	 *
	 * @param string $mode name of the mode
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function run($mode = null)
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$argumentKey = $parser->getArgument(1);
		if ($argumentKey === 'clear')
		{
			return $this->_clear($parser->getOption());
		}
		if ($argumentKey === 'clear-invalid')
		{
			return $this->_clearInvalid($parser->getOption());
		}
		return $this->getHelp();
	}

	/**
	 * clear the cache
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _clear(array $optionArray = [])
	{
		$directory = $this->prompt('directory', $optionArray);
		$extension = $this->prompt('extension', $optionArray);
		if (is_dir($directory))
		{
			$cacheFilesystem = new Filesystem\Cache();
			return is_object($cacheFilesystem->init($directory, $extension)->clear($optionArray['bundle']));
		}
		return false;
	}

	/**
	 * clear the invalid cache
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _clearInvalid(array $optionArray = [])
	{
		$directory = $this->prompt('directory', $optionArray);
		$extension = $this->prompt('extension', $optionArray);
		$lifetime = is_numeric($optionArray['lifetime']) ? $optionArray['lifetime'] : 3600;
		if (is_dir($directory))
		{
			$cacheFilesystem = new Filesystem\Cache();
			return is_object($cacheFilesystem->init($directory, $extension)->clearInvalid($lifetime));
		}
		return false;
	}
}
