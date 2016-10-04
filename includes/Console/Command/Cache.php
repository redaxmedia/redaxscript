<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Cache as BaseCache;
use Redaxscript\Console\Parser;

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
							'description' => 'Optional directory of the cache'
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
							'description' => 'Optional directory of the cache'
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
	 * @return boolean
	 */

	protected function _clear($optionArray = [])
	{
		$directory = $optionArray['directory'];
		$bundle = explode(',', $optionArray['bundle']);
		$cache = new BaseCache();
		return $cache
			->init($directory)
			->clear($bundle);
	}

	/**
	 * clear the invalid cache
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return boolean
	 */

	protected function _clearInvalid($optionArray = [])
	{
		$directory = $optionArray['directory'];
		$lifetime = is_numeric($optionArray['lifetime']) ? $optionArray['lifetime'] : 3600;
		$cache = new BaseCache();
		return $cache
			->init($directory)
			->clearInvalid($lifetime);
	}
}
