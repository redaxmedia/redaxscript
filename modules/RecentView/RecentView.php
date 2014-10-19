<?php
namespace Redaxscript\Modules\RecentView;

use Redaxscript\Module;
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

class RecentView extends Module
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
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param $limit limit for the list
	 *
	 * @return string
	 */

	public static function render($limit = 10)
	{
		$counter = 0;
		$log = self::_log();
		if ($log)
		{
			$output = '<ul class="list_recent_view">';
			foreach ($log as $value)
			{
				/* break if limit reached */

				if (++$counter > $limit && $limit)
				{
					break;
				}
				$output .= '<li><a href="' . Registry::get('fullRoute') . $value . '" title="' . $value . '">' . $value . '</a></li>';
			}
			$output .= '</ul>';
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

		/* handle recent view */

		if (end(Request::getSession($root . '/recent_view')) !== $fullRoute && $fullRoute)
		{
			$_SESSION[$root . '/recent_view'][] = $fullRoute;
		}
		$output = array_reverse(Request::getSession($root . '/recent_view'));
		return $output;
	}
}
