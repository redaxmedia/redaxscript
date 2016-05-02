<?php
namespace Redaxscript\Modules\FeedReader;

use DOMDocument;
use Redaxscript\Html;
use XMLReader;

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
	 * @param array $options
	 *
	 * @return string
	 */

	public static function render($url = null, $options = array())
	{
		$counter = 0;
		$output = null;

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

		$doc = new DOMDocument();
		$reader = new XMLReader();
		$reader->open($url);

		/* process reader */

		while ($reader->read())
		{
			if ($reader->nodeType === XMLReader::ELEMENT)
			{
				$doc->appendChild($doc->importNode($reader->expand(), true));
			}
		}
		$reader->close();

		/* handle feed type */

		$xml = simplexml_import_dom($doc);
		$feed = $xml->entry ? $xml->entry : $xml->channel->item;

		/* process feed */

		foreach ($feed as $value)
		{
			if ($counter++ < $options['limit'])
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
