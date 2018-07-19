<?php
namespace Redaxscript\Content\Tag;

/**
 * children class to parse content for registry tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

class Registry extends TagAbstract
{
	/**
	 * options of the registry tag
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'search' =>
		[
			'<rs-registry>',
			'</rs-registry>'
		],
		'delimiter' => '@@@'
	];

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	public function process(string $content = null) : string
	{
		$output = str_replace($this->_optionArray['search'], $this->_optionArray['delimiter'], $content);
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			if ($key % 2)
			{
				$partArray[$key] = $this->_registry->get($value);
			}
		}
		$output = implode($partArray);
		return $output;
	}
}