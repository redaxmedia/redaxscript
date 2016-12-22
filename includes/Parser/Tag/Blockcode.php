<?php
namespace Redaxscript\Parser\Tag;

/**
 * helper class to parse content for blockcode tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Blockcode implements TagInterface
{
	/**
	 * array of the pseudo tag
	 *
	 * @var array
	 */

	protected $_tagArray =
	[
		'className' =>
		[
			'blockcode' => 'rs-js-code rs-code-default'
		],
		'position' => null,
		'search' =>
		[
			'<blockcode>',
			'</blockcode>'
		],
		'delimiter' => '@@@'
	];

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
	}
}