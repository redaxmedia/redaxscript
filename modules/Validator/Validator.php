<?php
namespace Redaxscript\Modules\Validator;

use Redaxscript\Reader;

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
		'version' => '3.2.1',
		'access' => '1'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.1
	 *
	 * @return array
	 */

	public function adminPanelNotification()
	{
		if ($this->_registry->get('firstParameter') !== 'admin')
		{
			/* load result */

			$urlBase = $this->_configArray['apiUrl'] . $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
			$urlJSON = $urlBase . '&out=json';
			$reader = new Reader();
			$result = $reader->loadJSON($urlJSON)->getArray();

			/* process result */

			if ($result['messages'])
			{
				foreach ($result['messages'] as $value)
				{
					if (in_array($value['type'], $this->_configArray['typeArray']))
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
						$this->setNotification($value['type'], $message);
					}
				}
			}
			else
			{
				$this->setNotification('success', $this->_language->get('documentValidate', '_validator') . $this->_language->get('point'));
			}
		}
		return $this->getNotification();
	}
}
