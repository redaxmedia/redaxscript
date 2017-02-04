<?php
namespace Redaxscript;

use DOMDocument;

/**
 * parent class to purify html against xss
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 */

class Purifier
{
	/**
	 * array of allowed tags
	 *
	 * @var array
	 */

	protected $_allowedTags =
	[
		'#text',
		'br',
		'caption',
		'div',
		'dd',
		'dl',
		'dt',
		'em',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'li',
		'p',
		'pre',
		'ol',
		'span',
		'strike',
		'strong',
		'sub',
		'sup',
		'table',
		'tbody',
		'tfoot',
		'td',
		'th',
		'tr',
		'strong',
		'u',
		'ul',
		'wbr'
	];

	/**
	 * purify the html
	 *
	 * @since 3.0.0
	 *
	 * @param string $html target html
	 * @param boolean $filter optional filter nodes
	 *
	 * @return string
	 */

	public function purify($html = null, $filter = true)
	{
		$charset = Db::getSetting('charset');
		$html = mb_convert_encoding($html, 'html-entities', $charset);

		/* filter html */

		if ($filter === true)
		{
			$html = $this->_process($html);
		}
		return $html;
	}

	/**
	 * process the html
	 *
	 * @since 3.0.0
	 *
	 * @param object $html target node
	 *
	 * @return object
	 */

	protected function _process($html = null)
	{
		if (is_object($html))
		{
			/* process children */

			if ($html->hasChildNodes())
			{
				$range = range($html->childNodes->length - 1, 0);
				foreach ($range as $i)
				{
					$this->_process($html->childNodes->item($i));
				}
			}

			/* strip tags and attributes */

			$this->_stripTags($html);
			$this->_stripAttributes($html);
		}
		else
		{
			$dom = $this->_createDocument($html);
			$this->_process($dom->documentElement);
			return trim($dom->saveHTML());
		}
	}

	/**
	 * strip the tags
	 *
	 * @since 3.0.0
	 *
	 * @param object $node target node
	 *
	 * @return object
	 */

	protected function _stripTags($node = null)
	{
		if (!in_array($node->nodeName, $this->_allowedTags))
		{
			$fragment = $this->_createFragment($node);
			return $node->parentNode->replaceChild($fragment, $node);
		}
	}

	/**
	 * strip the attributes
	 *
	 * @since 3.0.0
	 *
	 * @param object $node target node
	 */

	protected function _stripAttributes($node = null)
	{
		while ($node->hasAttributes())
		{
			$node->removeAttributeNode($node->attributes->item(0));
		}
	}

	/**
	 * create the document
	 *
	 * @since 2.4.0
	 *
	 * @param string $html target html
	 *
	 * @return object
	 */

	protected function _createDocument($html = null)
	{
		$doc = new DOMDocument();
		$doc->loadHTML($html, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
		return $doc;
	}

	/**
	 * create the fragment
	 *
	 * @since 3.0.0
	 *
	 * @param string $node target node
	 *
	 * @return object
	 */

	protected function _createFragment($node = null)
	{
		$fragment = $node->ownerDocument->createDocumentFragment();

		/* process nodes */

		while ($node->childNodes->length)
		{
			$fragment->appendChild($node->firstChild);
		}
		return $fragment;
	}
}
