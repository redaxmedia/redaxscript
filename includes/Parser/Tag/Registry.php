<?php
namespace Redaxscript\Parser\Tag;

/**
 * helper class to parse content for registry tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Registry implements TagInterface
{
	/**
	 * array of the pseudo tag
	 *
	 * @var array
	 */

	protected $_tagArray =
	[
		'position' => null,
		'search' =>
		[
			'<registry>',
			'</registry>'
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