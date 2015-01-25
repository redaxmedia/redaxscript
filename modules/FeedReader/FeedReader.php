<?php
namespace Redaxscript\Modules\FeedReader;

/**
 * read external rss and atom feeds
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class FeedReader extends Config
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Feed reader',
		'alias' => 'FeedReader',
		'author' => 'Redaxmedia',
		'description' => 'Read external RSS and Atom feeds',
		'version' => '2.3.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * loaderStart
	 *
	 * @since 2.3.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/FeedReader/styles/feed_reader.css';
	}

	/**
	 * render
	 *
	 * @since 2.3.0
	 *
	 * @param string $url
	 * @param array $options
	 *
	 * @return string
	 */

	public static function render($url = null, $options = null)
	{
		$output = '';

		/* html elements */

		$titleElement = new Element('h3', array(
			'class' => self::$_config['className']['title']
		));
		$boxElement = new Element('ul', array(
				'class' => self::$_config['className']['list'])
		);

		/* get contents */

		$contents = file_get_contents($url);
		if ($contents)
		{
			$feed = new SimpleXMLElement($contents);

			/* handle type */

			if ($feed->entry)
			{
				$type = 'atom';
				$result = $feed->entry;
			}
			else if ($feed->channel)
			{
				$type = 'rss';
				$result = $feed->channel->item;
			}

			/* process result */

			foreach ($result as $value)
			{
				$title = trim($value->title);

				/* atom */

				if ($type == 'atom')
				{
					$route = $value->link['href'];
					$time = date(s('time'), strtotime($value->updated));
					$date = date(s('date'), strtotime($value->updated));
					$text = trim($value->content);
				}

				/* rss */

				else if ($type == 'rss')
				{
					$route = $value->link;
					$time = date(s('time'), strtotime($value->pubDate));
					$date = date(s('date'), strtotime($value->pubDate));
					$text = trim($value->description);
				}

				/* collect output */

				$output .= $titleElement->text($title);
			}
		}
		return $output;
	}
}