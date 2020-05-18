<?php
namespace Redaxscript\Modules\ImageUpload;

use Redaxscript\Dater;
use Redaxscript\Header;
use Redaxscript\Module;
use function chmod;
use function current;
use function in_array;
use function is_dir;
use function is_uploaded_file;
use function json_encode;
use function mkdir;
use function move_uploaded_file;
use function pathinfo;

/**
 * shared module to upload images
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class ImageUpload extends Module\Metadata
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Image Upload',
		'alias' => 'ImageUpload',
		'author' => 'Redaxmedia',
		'description' => 'Shared module to upload images',
		'version' => '4.3.0',
		'access' => '[1]'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'uploadDirectory' => 'upload',
		'extension' =>
		[
			'gif',
			'jpg',
			'png',
			'svg'
		]
	];

	/**
	 * renderStart
	 *
	 * @since 4.3.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'image-upload' && $this->_registry->get('tokenParameter'))
		{
			$this->_registry->set('renderBreak', true);
			echo $this->_upload();
		}
	}

	/**
	 * adminNotification
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		if (!mkdir($uploadDirectory = $this->_optionArray['uploadDirectory']) && !is_dir($uploadDirectory))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $this->_optionArray['uploadDirectory'] . $this->_language->get('point'));
		}
		else if (!chmod($this->_optionArray['uploadDirectory'], 0777))
		{
			$this->setNotification('error', $this->_language->get('directory_permission_grant') . $this->_language->get('colon') . ' ' . $this->_optionArray['uploadDirectory'] . $this->_language->get('point'));
		}
		return $this->getNotificationArray();
	}

	/**
	 * upload
	 *
	 * @since 4.3.0
	 *
	 * @return string|null
	 */

	protected function _upload() : ?string
	{
		$dater = new Dater();
		$dater->init();
		$filesArray = current($this->_request->getFiles());
		$fileExtension = pathinfo($filesArray['name'], PATHINFO_EXTENSION);
		$path = $this->_optionArray['uploadDirectory'] . DIRECTORY_SEPARATOR . $dater->getDateTime()->getTimestamp() . '.' . $fileExtension;

		/* handle upload */

		if (in_array($fileExtension, $this->_optionArray['extension']) && is_uploaded_file($filesArray['tmp_name']) && move_uploaded_file($filesArray['tmp_name'], $path))
		{
			Header::contentType('application/json');
			return json_encode(
			[
				'location' => $path
			]);
		}
		Header::responseCode(404);
		exit;
	}
}
