<?php
namespace Redaxscript\Modules\Validator;

use Redaxscript\Language;
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

	protected static $_moduleArray =
	[
		'name' => 'Validator',
		'alias' => 'Validator',
		'author' => 'Redaxmedia',
		'description' => 'HTML validator for developers',
		'version' => '3.0.0',
		'access' => '1'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function adminPanelNotification()
	{
		if (Registry::get('firstParameter') !== 'admin')
		{
			/* load result */

			$urlBase = self::$_configArray['url'] . Registry::get('root') . '/' . Registry::get('parameterRoute') . Registry::get('fullRoute');
			$urlJSON = $urlBase . '&out=json';
			$reader = new Reader();
			$result = $reader->loadJSON($urlJSON)->getArray();

			/* process result */

			if ($result['messages'])
			{
				foreach ($result['messages'] as $value)
				{
					if (in_array($value['type'], self::$_configArray['typeArray']))
					{
						$message =
						[
							'text' => $value['message'],
							'attr' =>
							[
								'href' => $urlBase,
								'target' => '_blank'
							]
						];
						self::setNotification($value['type'], $message);
					}
				}
			}
			else
			{
				self::setNotification('success', Language::get('documentValidate', '_validator') . Language::get('point'));
			}
			return self::getNotification();
		}
	}
}
