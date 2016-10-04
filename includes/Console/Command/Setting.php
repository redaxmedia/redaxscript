<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Db;

/**
 * children class to execute the setting command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Setting extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'setting' =>
		[
			'description' => 'Setting command',
			'argumentArray' =>
			[
				'list' =>
				[
					'description' => 'List the settings'
				],
				'set' =>
				[
					'description' => 'Set the setting',
					'optionArray' =>
					[
						'key' =>
						[
							'description' => 'Required setting key'
						],
						'value' =>
						[
							'description' => 'Required setting value'
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
		if ($argumentKey === 'list')
		{
			return $this->_list();
		}
		if ($argumentKey === 'set')
		{
			return $this->_set($parser->getOption());
		}
		return $this->getHelp();
	}

	/**
	 * list the settings
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _list()
	{
		$output = null;
		$settings = Db::getSetting();

		/* process settings */

		foreach ($settings as $setting)
		{
			$output .= str_pad($setting->name, 30) . $setting->value . PHP_EOL;
		}
		return $output;
	}

	/**
	 * set the setting
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return boolean
	 */

	protected function _set($optionArray = [])
	{
		$key = $this->prompt('key', $optionArray);
		$value = $this->prompt('value', $optionArray);
		if ($key && $value)
		{
			return Db::setSetting($key, $value);
		}
		return false;
	}
}
