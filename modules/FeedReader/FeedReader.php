<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;
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

class FeedReader extends Module\Metadata
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
		'version' => '4.2.0'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'title' => 'rs-title-content rs-title-feed-reader',
			'box' => 'rs-box-content rs-box-feed-reader'
		]
	];

	/**
	 * adminNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		return $this->getNotificationArray();
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
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
				'class' => $this->_optionArray['className']['title']
			]);
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['box']
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'target' => '_blank'
			]);

		/* load result */

		$reader = new Reader();
		$reader->init();
		$result = $reader->loadXML($url)->getObject();
		$result = $result->entry ? : $result->channel->item;

		/* process result */

		if ($result)
		{
			$counter = 0;
			foreach ($result as $value)
			{
				if ($counter++ < $optionArray['limit'])
				{
					$linkElement
							->attr('href', $value->link->attributes()->href ? : $value->link)
							->text($value->title);

					/* collect output */

					$output .= $titleElement->html($linkElement) . $boxElement->text($value->summary ? : $value->description);
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
