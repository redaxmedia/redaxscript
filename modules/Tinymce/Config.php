<?php
namespace Redaxscript\Modules\Tinymce;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module\Notification
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'uploadDirectory' => 'upload'
	];
}
