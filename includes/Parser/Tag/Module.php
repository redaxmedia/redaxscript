<?php
namespace Redaxscript\Parser\Tag;

/**
 * helper class to parse content for module tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Module implements TagInterface
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
			'<module>',
			'</module>'
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