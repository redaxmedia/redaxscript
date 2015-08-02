<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Module;

/**
 * parent class to store module config
 *
 * @since 2.5.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module
{
	/**
	 * module config
	 *
	 * @var array
	 */

	protected static $_config = array(
		'className' => array(
			'list' => 'list_directory_lister clearfix',
			'link' => 'link_directory_lister'
		),
		'files' => array(
			'text' => array(
				'className' => 'file_text',
				'extention' => array(
					'doc',
					'txt'
				)
			),
			'image' => array(
				'className' => 'file_image',
				'extention' => array(
					'gif',
					'jpg',
					'pdf',
					'png'
				)
			)
		)
	);
}
