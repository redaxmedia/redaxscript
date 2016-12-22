<?php
namespace Redaxscript\Parser;

use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * abstract class to create a parser class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

abstract class ParserAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * array of tag namespaces
	 *
	 * @var array
	 */

	protected $_namespaceArray =
	[
		'Redaxscript\Parser\Tag\Blockcode',
		'Redaxscript\Parser\Tag\Language',
		'Redaxscript\Parser\Tag\Module',
		'Redaxscript\Parser\Tag\Readmore',
		'Redaxscript\Parser\Tag\Registry',
		'Redaxscript\Parser\Tag\Template'
	];

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}
}