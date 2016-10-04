<?php
namespace Redaxscript\Modules\ShareThis;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * integrate social networks
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class ShareThis extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Share this',
		'alias' => 'ShareThis',
		'author' => 'Redaxmedia',
		'description' => 'Integrate social networks',
		'version' => '3.0.0'
	];

	/**
	 * contentFragmentEnd
	 *
	 * @since 3.0.0
	 */

	public static function contentFragmentEnd()
	{
		if (Registry::get('lastTable') === 'articles')
		{
			$url = Registry::get('root') . Registry::get('parameterRoute') . Registry::get('fullRoute');
			return self::render($url);
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
			->appendFile('modules/ShareThis/assets/styles/share_this.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('modules/ShareThis/assets/scripts/init.js')
			->appendFile('modules/ShareThis/assets/scripts/share_this.js');
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

	public static function render($url = null)
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
				'class' => self::$_configArray['className']['list']
			]);

			/* process network */

			foreach (self::$_configArray['network'] as $key => $value)
			{
				$output .= '<li>';
				$output .= $linkElement->attr(
				[
					'class' => self::$_configArray['className']['link'] . ' ' . $value['className'],
					'data-height' => $value['height'],
					'data-type' => $key,
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
