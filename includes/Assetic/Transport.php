<?php
namespace Redaxscript\Assetic;

use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * class to provide transport data
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
	 * registry to be transported
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
	 * get array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	//this should return an array of DATA and not an array of strings
	public function getArray()
	{
		$language = Language::getInstance();
		$registry = Registry::getInstance();

		/* collect output */

		//rename to transportArray
		$scriptArray[] = 'if (typeof rs === \'object\')';
		$scriptArray[] = '{';
		$scriptArray[] = 'rs.language = ' . json_encode($language->get()) . ';';
		$scriptArray[] = 'rs.registry = {};';
		foreach ($this->_registryArray as $value)
		{
			$scriptArray[] = 'rs.registry.' . $value . ' = \'' . $registry->get($value) . '\';';
		}
		$scriptArray[] = 'if (rs.baseURL === \'\')';
		$scriptArray[] = '{';
		$scriptArray[] = 'rs.baseURL = \'' . $registry->get('root') . '\/\';';
		$scriptArray[] = '}';
		$scriptArray[] = 'rs.generator = \'' . $language->get('name', '_package') . ' ' . $language->get('version', '_package') . '\';';
		$scriptArray[] = 'rs.version = \'' . $language->get('version', '_package') . '\';';
		$scriptArray[] = '}';
		return $scriptArray;
	}
}