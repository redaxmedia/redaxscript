<?php
namespace Redaxscript\Content\Tag;

use Redaxscript\Html;
use function str_replace;
use function strpos;
use function substr;

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
		'prefix' => 'more-',
		'className' =>
		[
			'link' => 'rs-link-more',
			'break' => 'rs-break-more'
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
		$id = $this->_optionArray['prefix'] . $position;

		/* html element */

		$element = new Html\Element();
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_optionArray['className']['link']
			]);
		$breakElement = $element
			->copy()
			->init('hr',
			[
				'id' => $id,
				'class' => $this->_optionArray['className']['break']
			]);

		/* collect output */

		if ($position > -1 && $this->_registry->get('lastTable') === 'categories')
		{
			$output = substr($output, 0, $position);
			if ($route)
			{
				$output .= $linkElement
					->attr('href', $this->_registry->get('parameterRoute') . $route . '#' . $id)
					->text($this->_language->get('read_more'));
			}
		}
		else if ($position > -1)
		{
			$output = substr($output, 0, $position) . $breakElement . substr($output, $position);
		}
		return $output;
	}
}
