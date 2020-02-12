<?php
namespace Redaxscript\Modules\SocialSharer;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * integrate social sharer
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class SocialSharer extends Module\Module
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
		'version' => '4.2.0'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'link' => 'rs-link-social-sharer',
			'list' => 'rs-list-social-sharer'
		],
		'network' =>
		[
			'facebook' =>
			[
				'url' => 'https://facebook.com/sharer.php?u=',
				'className' => 'rs-link-facebook'
			],
			'twitter' =>
			[
				'url' => 'https://twitter.com/intent/tweet?url=',
				'className' => 'rs-link-twitter'
			],
			'whatsapp' =>
			[
				'url' => 'https://api.whatsapp.com/send?text=',
				'className' => 'rs-link-whatsapp'
			],
			'telegram' =>
			[
				'url' => 'https://telegram.me/share/url?url=',
				'className' => 'rs-link-telegram'
			]
		]
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

	public function renderStart() : void
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
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_optionArray['className']['link'],
				'target' => '_blank'
			]);

		/* process network */

		foreach ($this->_optionArray['network'] as $key => $value)
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
