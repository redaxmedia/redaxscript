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

	protected $_commandArray = array(
		'setting' => array(
			'description' => 'Setting command',
			'argumentArray' => array(
				'list' => array(
					'description' => 'List the settings'
				),
				'set' => array(
					'description' => 'Set the setting',
					'optionArray' => array(
						'<name>' => array(
							'description' => 'Required setting <name>'
						)
					)
				)
			)
		)
	);
	
	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		$parser = new Parser($this->_request);
		$parser->init();

		/* run command */

		$argumentKey = $parser->getArgument(2);
		if ($argumentKey === 'list')
		{
			return $this->_list();
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

	public function _list()
	{
		$output = null;
		$settings = DB::getSetting();

		/* process settings */

		foreach ($settings as $setting)
		{
			if ($setting->value)
			{
				$output .= str_pad($setting->name, 30) . $setting->value . PHP_EOL;
			}
		}
		return $output;
	}
}
