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
			'googleplus' =>
			[
				'url' => 'https://plusone.google.com/_/+1/confirm?url=',
				'className' => 'rs-link-googleplus'
			],
			'twitter' =>
			[
				'url' => 'https://twitter.com/share?url=',
				'className' => 'rs-link-twitter'
			],
			'pinterest' =>
			[
				'url' => 'https://pinterest.com/pin/create/button/?url=',
				'className' => 'rs-link-pinterest'
			],
			'linkedin' =>
			[
				'url' => 'https://linkedin.com/shareArticle?url=',
				'className' => 'rs-link-linkedin'
			],
			'stumbleupon' =>
			[
				'url' => 'https://stumbleupon.com/submit?url=',
				'className' => 'rs-link-stumbleupon'
			]
		]
	];
}
