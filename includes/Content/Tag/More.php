<?php
namespace Redaxscript\Content\Tag;

use Redaxscript\Html;

/**
 * children class to parse content for more tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

class More extends TagAbstract
{
	/**
	 * options of the more tag
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'more' => 'rs-link-more'
		],
		'search' =>
		[
			'<rs-more>'
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

		/* html element */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => $this->_optionArray['className']['more']
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
					->text($this->_language->get('read_more'));
			}
		}
		return $output;
	}
}