<?php

/**
 * parent class to provide language
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Language
 * @author Henry Ruhs
 */

class Redaxscript_Language
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 *
	 * @param Redaxscript_Registry $registry instance of the registry class
	 */

	public function __construct(Redaxscript_Registry $registry)
	{
		$this->_registry = $registry;
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 */

	public function init($name = null)
	{
		/* merge things one time */
		global $l;
		$output = '';
		if ($l === null)
		{
			$l = array();
		}
		$registry = Redaxscript_Registry::instance();
		$language = $registry->get('language');
		$language = 'en';
		$json_default = json_decode(file_get_contents('languages/' . $language . '.json'), true);
		$json_current = array();
		if ($language !== 'en')
		{
			$json_current = json_decode(file_get_contents('languages/' . $language . '.json'), true);;
		}
		if (is_array($json_default))
		{
			$l = array_merge($l, $json_default, $json_current);
		}
		if (array_key_exists($name, $l))
		{
			$output = $l[$name];
			if (function_exists('mb_convert_encoding'))
			{
				$output = mb_convert_encoding($l[$name], s('charset'), 'utf-8, latin1');
			}
		}
		return $output;
	}
}