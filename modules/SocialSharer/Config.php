<?php
namespace Redaxscript\Modules\SocialSharer;

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

class Config extends Module\Module
{
	/**
	 * array of the config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'className' =>
		[
			'link' => 'rs-link-social-sharer',
			'list' => 'rs-list-social-sharer'
		],
		'network' =>
		[
			'facebook' =>
			[
				'url' => 'https://facebook.com/sharer.php?u=',
				'className' => 'rs-link-facebook'
			],
			'google-plus' =>
			[
				'url' => 'https://plus.google.com/share?url=',
				'className' => 'rs-link-google-plus'
			],
			'twitter' =>
			[
				'url' => 'https://twitter.com/intent/tweet?url=',
				'className' => 'rs-link-twitter'
			],
			'whatsapp' =>
			[
				'url' => 'https://api.whatsapp.com/send?text=',
				'className' => 'rs-link-whatsapp'
			],
			'telegram' =>
			[
				'url' => 'https://telegram.me/share/url?url=',
				'className' => 'rs-link-telegram'
			]
		]
	];
}
