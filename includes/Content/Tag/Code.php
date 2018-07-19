<?php
namespace Redaxscript\Content\Tag;

use Redaxscript\Html;

/**
 * children class to parse content for code tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

class Code extends TagAbstract
{
	/**
	 * options of the code tag
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'code' => 'rs-js-code rs-code-default'
		],
		'search' =>
		[
			'<rs-code>',
			'</rs-code>'
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

		/* html element */

		$preElement = new Html\Element();
		$preElement->init('pre',
		[
			'class' => $this->_optionArray['className']['code']
		]);

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			if ($key % 2)
			{
				$partArray[$key] = $preElement->copy()->html(htmlspecialchars($value, null, null, false));
			}
		}
		$output = implode($partArray);
		return $output;
	}
}