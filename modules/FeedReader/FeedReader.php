<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Head;
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

class FeedReader extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Feed Reader',
		'alias' => 'FeedReader',
		'author' => 'Redaxmedia',
		'description' => 'Read external RSS and Atom feeds',
		'version' => '4.0.0'
	];

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array|null
	 */

	public function adminNotification() : ?array
	{
		return $this->getNotification();
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
			->appendFile('modules/FeedReader/dist/styles/feed-reader.min.css');
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

	public function render(string $url = null, array $optionArray = []) : string
	{
		$output = null;

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_configArray['className']['title']
			]);
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_configArray['className']['box']
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'target' => '_blank'
			]);

		/* load result */

		$reader = new Reader();
		$result = $reader->loadXML($url)->getObject();
		$result = $result->entry ? $result->entry : $result->channel->item;

		/* process result */

		if ($result)
		{
			$counter = 0;
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
			$this->setNotification('error', $this->_language->get('url_incorrect') . $this->_language->get('point'));
		}
		return $output;
	}
}
