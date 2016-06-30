<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Html;
use Redaxscript\Reader;

/**
 * read external rss and atom feeds
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */
/* TODO: add admin notification */
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
		'version' => '3.0.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.3.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/FeedReader/assets/styles/feed_reader.css';
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public static function render($url = null, $optionArray = array())
	{
		$counter = 0;
		$output = null;

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h3', array(
			'class' => self::$_configArray['className']['title']
		));
		$linkElement = new Html\Element();
		$linkElement->init('a', array(
			'target' => '_blank'
		));
		$boxElement = new Html\Element();
		$boxElement->init('div', array(
			'class' => self::$_configArray['className']['box']
		));

		/* load result */

		$reader = new Reader();
		$result = $reader->loadXML($url)->getXML();
		$result = $result->entry ? $result->entry : $result->channel->item;

		/* process result */

		foreach ($result as $value)
		{
			if ($counter++ < $optionArray['limit'])
			{
				$linkElement
						->attr('href', $value->link['href'] ? $value->link['href'] : $value->link)
						->text($value->title);

				/* collect output */

				$output .= $titleElement->html($linkElement) . $boxElement->text($value->summary ? $value->summary : $value->description);
			}
		}
		return $output;
	}
}
