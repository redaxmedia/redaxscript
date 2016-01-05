<?php
namespace Redaxscript\Modules\RecentView;

use Redaxscript\Html;
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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Recent view',
		'alias' => 'RecentView',
		'author' => 'Redaxmedia',
		'description' => 'Generate a recent view list',
		'version' => '3.0.0'
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
		$output = null;
		$counter = 0;
		$log = self::_log();

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => self::$_config['className']['list']
		));

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
			$output = $listElement->html($output);
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
