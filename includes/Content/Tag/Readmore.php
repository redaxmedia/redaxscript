<?php
namespace Redaxscript\Content\Tag;

use Redaxscript\Html;

/**
 * children class to parse content for readmore tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

class Readmore extends TagAbstract
{
	/**
	 * options of the readmore tag
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'readmore' => 'rs-link-readmore'
		],
		'search' =>
		[
			'<readmore>'
		]
	];

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 * @param string $route route of the content
	 *
	 * @return string
	 */

	public function process(string $content = null, string $route = null) : string
	{
		$output = str_replace($this->_optionArray['search'], null, $content);
		$position = strpos($content, $this->_optionArray['search'][0]);

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => $this->_optionArray['className']['readmore']
		]);

		/* collect output */

		if ($position > -1 && $this->_registry->get('lastTable') === 'categories')
		{
			$output = substr($output, 0, $position);
			if ($route)
			{
				$output .= $linkElement
					->copy()
					->attr('href', $this->_registry->get('parameterRoute') . $route)
					->text($this->_language->get('readmore'));
			}
		}
		return $output;
	}
}