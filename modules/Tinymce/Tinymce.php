<?php
namespace Redaxscript\Modules\Tinymce;

use Redaxscript\Dater;
use Redaxscript\Head;
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
 * javascript powered wysiwyg editor
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Tinymce extends Module\Notification
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Tinymce',
		'alias' => 'Tinymce',
		'author' => 'Redaxmedia',
		'description' => 'JavaScript powered WYSIWYG editor',
		'version' => '4.0.0'
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
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.3/tinymce.min.js',
					'modules/Tinymce/assets/scripts/init.js',
					'modules/Tinymce/dist/scripts/tinymce.min.js'
				]);

			/* handle upload */

			if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'tinymce' && $this->_registry->get('thirdParameter') === 'upload' && $this->_registry->get('tokenParameter'))
			{
				$this->_registry->set('renderBreak', true);
				echo $this->_upload();
			}
		}
	}

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		if (!mkdir($uploadDirectory = $this->_optionArray['uploadDirectory']) && !is_dir($uploadDirectory))
		{
			$this->setNotification('error', $this->_language->get('directory_not_found') . $this->_language->get('colon') . ' ' . $this->_optionArray['uploadDirectory'] . $this->_language->get('point'));
		}
		else if (!chmod($this->_optionArray['uploadDirectory'], 0777))
		{
			$this->setNotification('error', $this->_language->get('directory_permission_grant') . $this->_language->get('colon') . ' ' . $this->_optionArray['uploadDirectory'] . $this->_language->get('point'));
		}
		return $this->getNotification();
	}

	/**
	 * upload
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	protected function _upload() : ?string
	{
		$dater = new Dater();
		$dater->init();
		$filesArray = current($this->_request->getFiles());
		$fileExtention = pathinfo($filesArray['name'], PATHINFO_EXTENSION);
		$path = $this->_optionArray['uploadDirectory'] . DIRECTORY_SEPARATOR . $dater->getDateTime()->getTimestamp() . '.' . $fileExtention;

		/* handle upload */

		if (in_array($fileExtention, $this->_optionArray['extension']) && is_uploaded_file($filesArray['tmp_name']) && move_uploaded_file($filesArray['tmp_name'], $path))
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
