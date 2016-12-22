<?php
namespace Redaxscript\Parser\Tag;

/**
 * helper class to parse content for readmore tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Readmore implements TagInterface
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
			'readmore' => 'rs-link-readmore'
		],
		'position' => null,
		'search' =>
		[
			'<readmore>'
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