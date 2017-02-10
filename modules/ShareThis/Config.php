<?php
namespace Redaxscript\Modules\ShareThis;

use Redaxscript\Module;

/**
 * children class to store module config
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
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'className' =>
		[
			'link' => 'rs-link-share-this',
			'list' => 'rs-js-share-this rs-list-share-this rs-fn-clearfix'
		],
		'network' =>
		[
			'facebook' =>
			[
				'type' => 'facebook',
				'url' => 'https://facebook.com/sharer.php?u=',
				'className' => 'rs-link-facebook'
			],
			'googleplus' =>
			[
				'type' => 'googleplusone',
				'url' => 'https://plusone.google.com/_/+1/confirm?url=',
				'className' => 'rs-link-googleplus',
				'height' => 700
			],
			'twitter' =>
			[
				'type' => 'twitter',
				'url' => 'https://twitter.com/share?url=',
				'className' => 'rs-link-twitter'
			],
			'pinterest' =>
			[
				'type' => 'pinterest',
				'url' => 'https://pinterest.com/pin/create/button/?url=',
				'className' => 'rs-link-pinterest',
				'width' => 800
			],
			'linkedin' =>
			[
				'type' => 'linkedin',
				'url' => 'https://linkedin.com/shareArticle?url=',
				'className' => 'rs-link-linkedin'
			],
			'stumbleupon' =>
			[
				'type' => 'stumbleupon',
				'url' => 'https://stumbleupon.com/submit?url=',
				'className' => 'rs-link-stumbleupon',
				'height' => 600
			]
		]
	];
}
