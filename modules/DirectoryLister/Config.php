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
			'link' => 'link_directory_lister',
			'textSize' => 'text_directory_lister text_size',
			'textDate' => 'text_directory_lister text_date',
			'types' => array(
				'directoryClosed' => 'directory_closed',
				'directoryOpen' => 'directory_open',
				'fileBlank' => 'file_blank',
				'fileText' => 'file_text',
				'fileImage' => 'file_image',
				'fileMusic' => 'file_music',
				'fileVideo' => 'file_video'
			)
		),
		'size' => array(
			'unit' => 'kB',
			'divider' => 1024
		),
		'extention' => array(
			'doc' => 'fileText',
			'txt' => 'fileText',
			'gif' => 'fileImage',
			'jpg' => 'fileImage',
			'pdf' => 'fileImage',
			'png' => 'fileImage',
			'mp3' => 'fileMusic',
			'wav' => 'fileMusic',
			'avi' => 'fileVideo',
			'mp4' => 'fileVideo'
		)
	);
}
