<?php
namespace Redaxscript\Assetic;

use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * class to transport javascript variables
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Assetic
 * @author Henry Ruhs
 */

class Transport
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * registry array to be transported
	 *
	 * @since 3.0.0
	 *
	 * @var array
	 */

	protected $_registryArray =
	[
		'token',
		'loggedIn',
		'firstParameter',
		'secondParameter',
		'thirdParameter',
		'adminParameter',
		'tableParameter',
		'idParameter',
		'aliasParameter',
		'lastParameter',
		'firstTable',
		'secondTable',
		'thirdTable',
		'lastTable',
		'fullRoute',
		'fullTopRoute',
		'parameterRoute',
		'languageRoute',
		'templateRoute',
		'refreshRoute',
		'language',
		'template',
		'myBrowser',
		'myBrowserVersion',
		'myEngine',
		'myDesktop',
		'myMobile',
		'myTablet'
	];

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * get the array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getArray()
	{
		//this should return an array of DATA and not an array of strings
		$scriptArray[] = 'if (typeof rs === \'object\')';
		$scriptArray[] = '{';
		$scriptArray[] = 'rs.language = ' . json_encode($this->_language->get()) . ';';
		$scriptArray[] = 'rs.registry = {};';
		foreach ($this->_registryArray as $value)
		{
			$scriptArray[] = 'rs.registry.' . $value . ' = \'' . $this->_registry->get($value) . '\';';
		}
		$scriptArray[] = 'if (rs.baseURL === \'\')';
		$scriptArray[] = '{';
		$scriptArray[] = 'rs.baseURL = \'' . $this->_registry->get('root') . '\/\';';
		$scriptArray[] = '}';
		$scriptArray[] = 'rs.generator = \'' . $this->_language->get('name', '_package') . ' ' . $this->_language->get('version', '_package') . '\';';
		$scriptArray[] = 'rs.version = \'' . $this->_language->get('version', '_package') . '\';';
		$scriptArray[] = '}';
		return $scriptArray;
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return string
	 */

	public function render($key = null, $value = null)
	{
		//i think we need just something like 'window. ' . $key . ' = ' . json_decode($value);
	}
}