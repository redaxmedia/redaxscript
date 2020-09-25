<?php
namespace Redaxscript\Modules\ImageUpload;

use Redaxscript\Dater;
use Redaxscript\Filesystem;
use Redaxscript\Head;
use Redaxscript\Header;
use Redaxscript\Module;
use function chmod;
use function in_array;
use function is_dir;
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
		'version' => '4.5.0',
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
		'uploadDirectory' => 'upload',
		'mimeTypeArray' =>
		[
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/svg+xml'
		]
	];

	/**
	 * renderStart
	 *
	 * @since 4.3.0
	 */

	public function renderStart() : void
	{
		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/ImageUpload/assets/scripts/init.js',
				'modules/ImageUpload/dist/scripts/image-upload.min.js'
			]);

		/* list and upload */

		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'image-upload' && $this->_registry->get('tokenParameter'))
		{
			if ($this->_registry->get('thirdParameter') === 'list')
			{
				$this->_registry->set('renderBreak', true);
				echo $this->_list();
			}
			if ($this->_registry->get('thirdParameter') === 'upload')
			{
				$this->_registry->set('renderBreak', true);
				echo $this->_upload();
			}
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
		if (!mkdir($directory = $this->_optionArray['uploadDirectory']) && !is_dir($directory))
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
	 * list
	 *
	 * @since 4.3.0
	 *
	 * @return string
	 */

	protected function _list() : string
	{
		$uploadFilesystem = new Filesystem\Filesystem();
		$uploadFilesystem->init($this->_optionArray['uploadDirectory']);
		$uploadFilesystemArray = $uploadFilesystem->getSortArray();

		/* handle list */

		Header::contentType('application/json');
		return json_encode($uploadFilesystemArray);
	}

	/**
	 * upload
	 *
	 * @since 4.3.0
	 *
	 * @return string
	 */

	protected function _upload() : ?string
	{
		$dater = new Dater();
		$dater->init();
		$filesArray = $this->_request->getArray()['files'];
		$uploadArray = [];

		/* process files */

		foreach ($filesArray as $key => $file)
		{
			$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
			$fileName = $dater->getDateTime()->getTimestamp() . '.' . $key . '.' . $fileExtension;
			$uploadPath = $this->_optionArray['uploadDirectory'] . DIRECTORY_SEPARATOR . $fileName;
			if (in_array($file['type'], $this->_optionArray['mimeTypeArray']) && move_uploaded_file($file['tmp_name'], $uploadPath))
			{
				$uploadArray[] = $uploadPath;
			}
		}

		/* handle upload */

		Header::contentType('application/json');
		return json_encode($uploadArray);
	}
}
