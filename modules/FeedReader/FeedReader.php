<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Language;
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

class FeedReader extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Feed reader',
		'alias' => 'FeedReader',
		'author' => 'Redaxmedia',
		'description' => 'Read external RSS and Atom feeds',
		'version' => '3.0.0'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function adminPanelNotification()
	{
		return self::getNotification();
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/FeedReader/assets/styles/feed_reader.css');
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

	public static function render($url = null, $optionArray = [])
	{
		$counter = 0;
		$output = null;

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h3',
		[
			'class' => self::$_configArray['className']['title']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'target' => '_blank'
		]);
		$boxElement = new Html\Element();
		$boxElement->init('div',
		[
			'class' => self::$_configArray['className']['box']
		]);

		/* load result */

		$reader = new Reader();
		$result = $reader->loadXML($url)->getObject();
		$result = $result->entry ? $result->entry : $result->channel->item;

		/* process result */

		if ($result)
		{
			foreach ($result as $value)
			{
				if ($counter++ < $optionArray['limit'])
				{
					$linkElement
							->attr('href', $value->link->attributes()->href ? $value->link->attributes()->href : $value->link)
							->text($value->title);

					/* collect output */

					$output .= $titleElement->html($linkElement) . $boxElement->text($value->summary ? $value->summary : $value->description);
				}
			}
		}

		/* else handle notification */

		else
		{
			self::setNotification('error', Language::get('url_incorrect') . Language::get('point'));
		}
		return $output;
	}
}
