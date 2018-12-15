<?php
namespace Redaxscript\Modules\CssValidator;

use Redaxscript\Module;
use Redaxscript\Reader;
use function http_build_query;

/**
 * css validator for developers
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CssValidator extends Module\Notification
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
		'access' => '[1]'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'apiUrl' => 'http://jigsaw.w3.org/css-validator/validator',
		'profile' => 'css3svg'
	];

	/**
	 * adminNotification
	 *
	 * @since 4.0.0
	 *
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		if ($this->_registry->get('firstParameter') !== 'admin')
		{
			/* load result */

			$url = $this->_optionArray['apiUrl'] . '?' . http_build_query(
			[
				'uri' => $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute'),
				'profile' => $this->_optionArray['profile'],
				'output' => 'json'
			]);
			$reader = new Reader();
			$reader->init(
			[
				'curl' =>
				[
					CURLOPT_USERAGENT => $this->_language->get('name', '_package')
				]
			]);
			$result = $reader->loadJSON($url)->getArray();

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
