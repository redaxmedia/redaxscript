<?php
namespace Redaxscript\Modules\SocialSharer;

use Redaxscript\Head;
use Redaxscript\Html;

/**
 * integrate a social sharer
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
		'name' => 'Social sharer',
		'alias' => 'SocialSharer',
		'author' => 'Redaxmedia',
		'description' => 'Integrate a social sharer',
		'version' => '3.0.0'
	];

	/**
	 * contentFragmentEnd
	 *
	 * @since 3.0.0
	 */

	public function contentFragmentEnd()
	{
		if ($this->_registry->get('lastTable') === 'articles')
		{
			$url = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
			return $this->render($url);
		}
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

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('modules/SocialSharer/assets/scripts/init.js')
			->appendFile('modules/SocialSharer/dist/scripts/social-sharer.min.js');
	}

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 *
	 * @return string
	 */

	public function render($url = null)
	{
		$output = null;
		if ($url)
		{
			/* html elements */

			$linkElement = new Html\Element();
			$linkElement->init('a',
			[
				'target' => '_blank'
			]);
			$listElement = new Html\Element();
			$listElement->init('ul',
			[
				'class' => $this->_configArray['className']['list']
			]);

			/* process network */

			foreach ($this->_configArray['network'] as $key => $value)
			{
				$output .= '<li>';
				$output .= $linkElement->attr(
				[
					'class' => $this->_configArray['className']['link'] . ' ' . $value['className'],
					'data-height' => $value['height'],
					'data-type' => $value['type'],
					'data-width' => $value['width'],
					'href' => $value['url'] . $url,
				])
				->text($key);
				$output .= '</li>';
			}

			/* collect list output */

			if ($output)
			{
				$output = $listElement->html($output);
			}
		}
		return $output;
	}
}
