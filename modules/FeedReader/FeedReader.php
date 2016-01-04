<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Html;
use SimpleXMLElement;

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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Feed reader',
		'alias' => 'FeedReader',
		'author' => 'Redaxmedia',
		'description' => 'Read external RSS and Atom feeds',
		'version' => '2.6.2'
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

	public static function render($url = null, $options = array())
	{
		$output = null;
		$counter = 0;

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h3', array(
			'class' => self::$_config['className']['title']
		));
		$linkElement = new Html\Element();
		$linkElement->init('a', array(
			'target' => '_blank'
		));
		$boxElement = new Html\Element();
		$boxElement->init('div', array(
			'class' => self::$_config['className']['box']
		));

		/* get contents */

		$contents = file_get_contents($url);
		if ($contents)
		{
			$feed = new SimpleXMLElement($contents);
			$result = $feed->entry ? $feed->entry : $feed->channel->item;

			/* process result */

			foreach ($result as $value)
			{
				/* break if limit reached */

				if (++$counter > $options['limit'])
				{
					break;
				}

				/* handle feed type */

				$url = $value->link['href'] ? (string)$value->link['href'] : (string)$value->link;
				$text = $value->summary ? $value->summary : $value->description;

				/* url */

				if ($url)
				{
					$linkElement->attr('href', $url)->text($value->title);
				}
				else
				{
					$linkElement = $value->title;
				}

				/* collect output */

				$output .= $titleElement->html($linkElement) . $boxElement->text($text);
			}
		}
		return $output;
	}
}
