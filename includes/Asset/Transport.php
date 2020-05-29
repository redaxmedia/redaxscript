<?php
namespace Redaxscript\Asset;

use Redaxscript\Language;
use Redaxscript\Registry;
use function is_array;
use function json_encode;

/**
 * class to transport javascript variables
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Asset
 * @author Henry Ruhs
 */

class Transport
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
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

	public function getArray() : array
	{
		$transportArray =
		[
			'baseUrl' => $this->_registry->get('root') . '/',
			'generator' => $this->_language->get('_package')['name'] . ' ' . $this->_language->get('_package')['version'],
			'language' => $this->_language->getArray(),
			'version' => $this->_language->get('_package')['version']
		];

		/* process registry */

		foreach ($this->_registryArray as $value)
		{
			$transportArray['registry'][$value] = $this->_registry->get($value);
		}
		return $transportArray;
	}

	/**
	 * render the javascript variables
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string|array $value
	 *
	 * @return string|null
	 */

	public function render(string $key = null, $value = null) : ?string
	{
		$output = null;
		if (is_array($value))
		{
			foreach ($value as $keyChildren => $valueChildren)
			{
				$output .= 'window.' . $key . '.' . $keyChildren . ' = ' . json_encode($valueChildren) . ';';
			}
		}
		else if ($value)
		{
			$output = 'window.' . $key . ' = ' . json_encode($value) . ';';
		}
		return $output;
	}
}
