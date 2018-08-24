<?php
namespace Redaxscript\Modules\CssValidator;

use Redaxscript\Reader;

/**
 * css validator for developers
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CssValidator extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'CSS Validator',
		'alias' => 'CssValidator',
		'author' => 'Redaxmedia',
		'description' => 'CSS validator for developers',
		'version' => '4.0.0',
		'access' => '1'
	];

	/**
	 * adminNotification
	 *
	 * @since 4.0.0
	 *
	 * @return array|bool
	 */

	public function adminNotification()
	{
		if ($this->_registry->get('firstParameter') !== 'admin')
		{
			/* load result */

			$url = $this->_configArray['apiUrl'] . '/?uri=' . $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute') . '&profile=' . $this->_configArray['profile'];
			$reader = new Reader();
			$result = $reader->loadJSON($url . '&output=json')->getArray();

			/* process result */

			if ($result['cssvalidation']['errors'])
			{
				foreach ($result['cssvalidation']['errors'] as $value)
				{
					$message =
					[
						'text' => $value['message'],
						'attr' =>
						[
							'href' => $url,
							'target' => '_blank'
						]
					];
					$this->setNotification('error', $message);
				}
			}
			if (!$result)
			{
				$this->setNotification('warning', $this->_language->get('service_no', '_validator') . $this->_language->get('point'));
			}
			else if (!$this->getNotification('error'))
			{
				$this->setNotification('success', $this->_language->get('document_validate', '_validator') . $this->_language->get('point'));
			}
		}
		return $this->getNotification();
	}
}
