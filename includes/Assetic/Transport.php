<?php
namespace Redaxscript\Assetic;

use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * class to transport javascript
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
	 * registry keys to transport
	 *
	 * @since 3.0.0
	 *
	 * @var array
	 */

	private static $public_registry =
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
	 * scripts transport
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function scriptsTransport()
	{
		$language = Language::getInstance();
		$registry = Registry::getInstance();

		/* collect output */

		$script[] = 'if (typeof rs === \'object\')';
		$script[] = '{';

		/* add language */

		$script[] = 'rs.language = ' . json_encode($language->get()) . ';';

		/* add registry */

		$script[] = 'rs.registry = {};';
		foreach (self::$public_registry as $value)
		{
			$script[] = 'rs.registry.' . $value . ' = \'' . $registry->get($value) . '\';';
		}

		/* baseURL fallback */

		$script[] = 'if (rs.baseURL === \'\')';
		$script[] = '{';
		$script[] = 'rs.baseURL = \'' . $registry->get('root') . '\/\';';
		$script[] = '}';

		/* generator and version */

		$script[] = 'rs.generator = \'' . $language->get('name', '_package') . ' ' . $language->get('version', '_package') . '\';';
		$script[] = 'rs.version = \'' . $language->get('version', '_package') . '\';';
		$script[] = '}';

		return $script;
	}
}