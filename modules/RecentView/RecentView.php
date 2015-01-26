<?php
namespace Redaxscript\Modules\RecentView;

use Redaxscript\Element;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * generate a recent view list
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class RecentView extends Config
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Recent view',
		'alias' => 'RecentView',
		'author' => 'Redaxmedia',
		'description' => 'Generate a recent view list',
		'version' => '2.3.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param array $options
	 *
	 * @return string
	 */

	public static function render($options = array())
	{
		$output = '';
		$counter = 0;
		$log = self::_log();

		/* html elements */

		$linkElement = new Element('a');
		$listElement = new Element('ul');

		/* process log */

		foreach ($log as $value)
		{
			/* break if limit reached */

			if (++$counter > $options['limit'])
			{
				break;
			}
			$output .= '<li>';
			$output .= $linkElement->attr(array(
				'href' => Registry::get('rewriteRoute') . $value,
				'title' => $value
			))->text($value);
			$output .= '</li>';
		}

		/* collect list output */

		if ($output)
		{
			$output = $listElement->attr('class', self::$_config['className']['list'])->html($output);
		}
		return $output;
	}

	/**
	 * log
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	protected static function _log()
	{
		$root = Registry::get('root');
		$fullRoute = Registry::get('fullRoute');
		$recentView = Request::getSession($root . '/recent_view');

		/* handle recent view */

		if ($fullRoute && current($recentView) !== $fullRoute)
		{
			if (!is_array($recentView))
			{
				$recentView = array();
			}
			array_unshift($recentView, $fullRoute);
			Request::setSession($root . '/recent_view', $recentView);
		}
		$output = $recentView;
		return $output;
	}
}
