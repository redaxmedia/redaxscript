<?php
namespace Redaxscript\Modules\HtmlValidator;

use Redaxscript\Module;
use Redaxscript\Reader;
use function http_build_query;

/**
 * html validator for developers
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class HtmlValidator extends Module\Notification
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
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		if ($this->_registry->get('firstParameter') !== 'admin')
		{
			/* load result */

			$url = $this->_optionArray['apiUrl'] . '?' . http_build_query(
			[
				'doc' => $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute'),
				'checkerrorpages' => 'yes',
				'out' => 'json'
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
				$this->setNotification('warning', $this->_language->get('service_no', '_html_validator') . $this->_language->get('point'));
			}
			else if (!$this->getNotification('error'))
			{
				$this->setNotification('success', $this->_language->get('document_validate', '_html_validator') . $this->_language->get('point'));
			}
		}
		return $this->getNotification();
	}
}
