<?php
namespace Redaxscript\Modules\Validator;

use Redaxscript\Html;
use Redaxscript\Reader;
use Redaxscript\Registry;

/**
 * html validator for developers
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Validator extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Validator',
		'alias' => 'Validator',
		'author' => 'Redaxmedia',
		'description' => 'HTML validator for developers',
		'version' => '3.0.0',
		'access' => '1'
	);

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 */

	public static function adminPanelNotification()
	{
		/* load result */

		$url = self::$_configArray['url'] . Registry::get('root') . '/' . Registry::get('parameterRoute') . Registry::get('fullRoute') . '&parser=' . self::$_configArray['parser'] . '&out=xml';
		$reader = new Reader();
		$result = $reader->loadXML($url)->getObject();

		/* process result */

		foreach ($result as $value)
		{
			$type = $value->attributes()->type ? (string)$value->attributes()->type : $value->getName();
			if (in_array($type, self::$_configArray['typeArray']))
			{
				self::setNotification($type, $value->message);
			}
		}
		return self::getNotification();
	}
}
