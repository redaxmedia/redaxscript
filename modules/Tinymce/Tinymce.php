<?php
namespace Redaxscript\Modules\Tinymce;

use Redaxscript\Head;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * javascript powered wysiwyg editor
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Tinymce extends Config
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
		'version' => '3.0.0',
		'access' => '1'
	];

	/**
	 * scriptEnd
	 *
	 * @since 3.0.0
	 */

	public static function scriptEnd()
	{
		if (Registry::get('loggedIn') === Registry::get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(['src' => '//cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.1/tinymce.min.js'])
				->appendFile(['src' => 'modules/Tinymce/assets/scripts/init.js'])
				->appendFile(['src' => 'modules/Tinymce/assets/scripts/tinymce.js']);
		}
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'tinymce' && Registry::get('secondParameter') === 'upload' && Registry::get('lastParameter') === Registry::get('token'))
		{
			Registry::set('renderBreak', true);
			echo self::_upload();
		}
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function adminPanelNotification()
	{
		if (!is_dir(self::$_configArray['uploadDirectory']))
		{
			self::setNotification('error', Language::get('directory_not_found') . Language::get('colon') . ' ' . self::$_configArray['uploadDirectory'] . Language::get('point'));
		}
		else if (!chmod(self::$_configArray['uploadDirectory'], 0777))
		{
			self::setNotification('error', Language::get('directory_permission_grant') . Language::get('colon') . ' ' . self::$_configArray['uploadDirectory']  . Language::get('point'));
		}
		return self::getNotification();
	}

	/**
	 * upload
	 *
	 * @since 3.0.0
	 */

	protected static function _upload()
	{
		$filesArray = current(Request::getFiles());

		/* upload file */

		if (is_uploaded_file($filesArray['tmp_name']))
		{
			if (move_uploaded_file($filesArray['tmp_name'], self::$_configArray['uploadDirectory'] . '/' . $filesArray['name']))
			{
				return json_encode(
				[
					'location' => self::$_configArray['uploadDirectory'] . '/' . $filesArray['name']
				]);
			}
		}
		header('http/1.0 404 not found');
	}
}
