<?php
namespace Redaxscript\Modules\ShareThis;

use Redaxscript\Module;

/**
 * parent class to store module config
 *
 * @since 2.2.0
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
			'list' => 'list_share_this clearfix',
			'link' => 'js_link_share_this link_share_this'
		),
		'network' => array(
			'facebook' => array(
				'url' => 'http://facebook.com/sharer.php?u=',
				'className' => ' link_facebook',
				'attribute' => ' data-type="facebook"'
			),
			'googleplusone' => array(
				'url' => 'http://plusone.google.com/_/+1/confirm?url=',
				'className' => ' link_googleplusone',
				'attribute' => ' data-type="googleplusone"'
			),
			'twitter' => array(
				'url' => 'http://twitter.com/share?url=',
				'className' => ' link_twitter',
				'attribute' => ' data-type="twitter" data-height="340"'
			),
			'pinterest' => array(
				'url' => 'http://pinterest.com/pin/create/button/?url=',
				'className' => ' link_pinterest',
				'attribute' => ' data-type="pinterest"'
			),
			'linkedin' => array(
				'url' => 'http://linkedin.com/shareArticle?url=',
				'className' => ' link_linkedin',
				'attribute' => ' data-type="linkedin" data-height="490" data-width="850"'
			),
			'stumbleupon' => array(
				'url' => 'http://stumbleupon.com/submit?url=',
				'className' => ' link_stumbleupon',
				'attribute' => ' data-type="stumbleupon"'
			),
			'delicious' => array(
				'url' => 'http://del.icio.us/post?url=',
				'className' => ' link_delicious',
				'attribute' => ' data-type="delicious" data-height="580"'
			)
		)
	);
}
