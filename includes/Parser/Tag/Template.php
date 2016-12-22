<?php
namespace Redaxscript\Parser\Tag;

/**
 * helper class to parse content for template tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Template implements TagInterface
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
			'<template>',
			'</template>'
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