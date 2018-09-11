<?php
namespace Redaxscript\Modules\HtmlValidator;

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

class HtmlValidator extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'HTML Validator',
		'alias' => 'HtmlValidator',
		'author' => 'Redaxmedia',
		'description' => 'HTML validator for developers',
		'version' => '4.0.0',
		'access' => '1'
	];

	/**
	 * adminNotification
	 *
	 * @since 3.0.1
	 *
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		if ($this->_registry->get('firstParameter') !== 'admin')
		{
			/* load result */

			$url = $this->_configArray['apiUrl'] . '/?doc=' . $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
			$reader = new Reader();
			$result = $reader->loadJSON($url . '&out=json')->getArray();

			/* process result */

			if ($result['messages'])
			{
				foreach ($result['messages'] as $value)
				{
					if ($value['type'] === 'error')
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
