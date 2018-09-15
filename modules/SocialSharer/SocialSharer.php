<?php
namespace Redaxscript\Modules\SocialSharer;

use Redaxscript\Head;
use Redaxscript\Html;

/**
 * integrate social sharer
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class SocialSharer extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Social Sharer',
		'alias' => 'SocialSharer',
		'author' => 'Redaxmedia',
		'description' => 'Integrate social sharer',
		'version' => '4.0.0'
	];

	/**
	 * articleFragmentEnd
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function articleFragmentEnd() : ?string
	{
		if ($this->_registry->get('lastTable') === 'articles')
		{
			$url = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
			return $this->render($url);
		}
		return null;
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		/* link */

		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/SocialSharer/dist/styles/social-sharer.min.css');
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 *
	 * @return string|null
	 */

	public function render(string $url = null) : ?string
	{
		$output = null;
		$outputItem = null;

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_configArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_configArray['className']['link'],
				'target' => '_blank'
			]);

		/* process network */

		foreach ($this->_configArray['network'] as $key => $value)
		{
			$outputItem .= $itemElement
				->html(
					$linkElement
						->copy()
						->addClass($value['className'])
						->attr('href', $value['url'] . $url)
						->text($key)
				);
		}

		/* collect output */

		if ($outputItem)
		{
			$output = $listElement->html($outputItem);
		}
		return $output;
	}
}
