<?php
namespace Redaxscript\Filter;

use DOMDocument;
use Redaxscript\Db;

/**
 * children class to filter html
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

class Html implements FilterInterface
{
	/**
	 * array of tags
	 *
	 * @var array
	 */

	protected $_htmlTags = array(
		'div',
        'li',
		'p',
		'span',
        'ul'
	);

	/**
	 * array of attributes
	 *
	 * @var array
	 */

	protected $_htmlAttributes = array(
		'class',
		'id'
	);

	/**
	 * sanitize the html
	 *
	 * @since 2.4.0
	 *
	 * @param string $html target with html
	 * @param boolean $filter optional filter nodes
	 *
	 * @return string
	 */

	public function sanitize($html = null, $filter = true)
	{
		$charset = Db::getSettings('charset');
		$html = mb_convert_encoding($html, 'html-entities', $charset);
        $html = html_entity_decode($html, ENT_QUOTES, $charset);
		$doc = self::_createDocument($html);

		/* filter nodes */

		if ($filter === true)
		{
			/* disable errors */

			libxml_use_internal_errors(true);

			/* clean tags and attributes */

            $doc = self::_stripTags($doc);
            $doc = self::_stripAttributes($doc);

			/* clear errors */

			libxml_clear_errors();
		}

		/* clean document */

		$doc = self::_cleanDocument($doc);
		$output = trim($doc->saveHTML());
		return $output;
	}

	/**
	 * create the document
	 *
	 * @since 2.4.0
	 *
	 * @param string $html target with html
	 *
	 * @return DOMDocument
	 */

	public function _createDocument($html = null)
	{
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		return $doc;
	}

	/**
	 * clean the document
	 *
	 * @since 2.4.0
	 *
	 * @param DOMDocument $doc target with document
	 *
	 * @return DOMDocument
	 */

	protected function _cleanDocument(DOMDocument $doc)
	{
		/* clean document */

		if (isset($doc->firstChild) && $doc->firstChild->nodeType === XML_DOCUMENT_TYPE_NODE)
		{
			/* remove doctype */

			$doc->removeChild($doc->firstChild);

			/* remove html wrapper */

			if (isset($doc->firstChild->firstChild->firstChild) && $doc->firstChild->firstChild->tagName === 'body')
			{
				$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);
			}
		}
		return $doc;
	}

	/**
	 * strip the tags
	 *
	 * @since 2.4.0
	 *
	 * @param DOMDocument $doc target with document
	 *
	 * @return DOMDocument
	 */

	protected function _stripTags(DOMDocument $doc)
	{
		return $doc;
	}

	/**
	 * strip the attributes
	 *
	 * @since 2.4.0
	 *
	 * @param DOMDocument $doc target with document
	 *
	 * @return DOMDocument
	 */

	protected function _stripAttributes(DOMDocument $doc)
	{
		return $doc;
	}
}
