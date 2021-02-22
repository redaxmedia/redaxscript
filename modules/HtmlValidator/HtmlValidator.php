<?php
namespace Redaxscript\Modules\HtmlValidator;

use Redaxscript\Module;
use Redaxscript\Reader;
use function array_key_exists;
use function http_build_query;
use function urldecode;

/**
 * realtime w3c validator for your html
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class HtmlValidator extends Module\Metadata
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
		'description' => 'Realtime W3C validator for your HTML',
		'version' => '5.0.0',
		'license' => 'MIT',
		'access' => '[1]'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'apiUrl' => 'https://validator.w3.org/nu/'
	];

	/**
	 * adminNotification
	 *
	 * @since 3.0.1
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		if ($this->_registry->get('firstParameter') !== 'admin')
		{
			/* load result */

			$url = $this->_optionArray['apiUrl'] . '?' . urldecode(http_build_query(
			[
				'doc' => $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute'),
				'checkerrorpages' => 'yes'
			]));
			$urlJSON = $url . '&' . urldecode(http_build_query(
			[
				'out' => 'json'
			]));
			$reader = new Reader();
			$reader->init(
			[
				'curl' =>
				[
					CURLOPT_USERAGENT => $this->_language->get('_package')['name']
				]
			]);
			$result = $reader->loadJSON($urlJSON)->getArray();

			/* process result */

			if ($result['messages'])
			{
				foreach ($result['messages'] as $value)
				{
					if ($value['type'] === 'error' || $value['type'] === 'non-document-error')
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
				$this->setNotification('warning', $this->_language->get('_html_validator')['service_no'] . $this->_language->get('point'));
			}
			else if (!array_key_exists('error', $this->getNotificationArray()))
			{
				$this->setNotification('success', $this->_language->get('_html_validator')['document_validate'] . $this->_language->get('point'));
			}
		}
		return $this->getNotificationArray();
	}
}
