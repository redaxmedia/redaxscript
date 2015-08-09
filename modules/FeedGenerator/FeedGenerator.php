<?php
namespace Redaxscript\Modules\FeedGenerator;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * generate atom feeds from content
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class FeedGenerator extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Feed generator',
		'alias' => 'FeedGenerator',
		'author' => 'Redaxmedia',
		'description' => 'Generate Atom feeds from content',
		'version' => '2.6.0'
	);

	/**
	 * renderStart
	 *
	 * @since 2.3.0
	 */

	public static function renderStart()
	{
		$firstParamter = Registry::get('firstParameter');
		$secondParameter = Registry::get('secondParameter');
		if ($firstParamter === 'feed' && ($secondParameter === 'articles' || $secondParameter === 'comments'))
		{
			header('content-type: application/atom+xml');
			echo self::render(SECOND_PARAMETER);
			Registry::set('renderBreak', true);
		}
	}

	/**
	 * render
	 *
	 * @since 2.3.0
	 *
	 * @param string $table
	 *
	 * @return string
	 */

	public static function render($table = 'articles')
	{
		$output = '';

		/* fetch result */

		$result = Db::forTablePrefix($table)
			->where('status', 1)
			->where('access', 0)
			->where('language', Request::getQuery('l') ? Registry::get('language') : '')
			->orderGlobal('rank')
			->limitGlobal()
			->findArray();

		/* process result */

		if ($result)
		{
			$route = Registry::get('root') . '/' . Registry::get('rewriteRoute') . Registry::get('fullRoute');
			if (Request::getQuery('l'))
			{
				$route .= Registry::get('languageRoute') . Registry::get('language');
			}
			$title = Db::getSettings('title');
			$description = Db::getSettings('description');
			$author = Db::getSettings('author');
			$copyright = Db::getSettings('copyright');

			/* collect output */

			$output = '<?xml version="1.0" encoding="' . Db::getSettings('charset') . '"?>';
			$output .= '<feed xmlns="http://www.w3.org/2005/Atom">';
			$output .= '<id>' . $route . '</id>';
			$output .= '<link type="application/atom+xml" href="' . $route . '" rel="self" />';
			$output .= '<updated>' . date('c', strtotime(Registry::get('now'))) . '</updated>';

			/* title */

			if ($title)
			{
				$output .= '<title>' . $title . '</title>';
			}

			/* description */

			if ($description)
			{
				$output .= '<subtitle>' . $description . '</subtitle>';
			}

			/* author */

			if ($author)
			{
				$output .= '<author><name>' . $author . '</name></author>';
			}

			/* copyright */

			if ($copyright)
			{
				$output .= '<rights>' . $copyright . '</rights>';
			}

			/* generator */

			$output .= '<generator>' . Language::get('name', '_package') . ' ' . Language::get('version', '_package') . '</generator>';

			/* collect body output */

			foreach ($result as $value)
			{
				$route = Registry::get('root') . '/' . Registry::get('rewriteRoute');
				$route .= $value['category'] < 1 ? $value['alias'] : build_route($table, $value['id']);

				/* collect entry output */

				$output .= '<entry>';
				$output .= '<id>' . $route . '</id>';
				$output .= '<link href="' . $route . '" />';
				$output .= '<updated>' . date('c', strtotime($value['date'])) . '</updated>';

				/* title */

				$output .= '<title>' . ($table === 'comments' ? $value['author'] : $value['title']) . '</title>';

				/* description */

				if ($value['description'])
				{
					$output .= '<summary>' . $value['description'] . '</summary>';
				}

				/* text */

				$output .= '<content>' . strip_tags($value['text']) . '</content>';

				/* author */

				if ($value['author'])
				{
					$output .= '<author><name>' . $value['author'] . '</name></author>';
				}
				$output .= '</entry>';
			}
			$output .= '</feed>';
		}
		return $output;
	}
}
