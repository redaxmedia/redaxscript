<?php
namespace Redaxscript\Filter;

use DOMDocument;
use Redaxscript\Db;

/**
 * children class to filter html
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Html implements FilterInterface
{
	/**
	 * array of allowed tags
	 *
	 * @var array
	 */

	protected $_allowedTags =
	[
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
	 * array of allowed attributes
	 *
	 * @var array
	 */

	protected $_allowedAttributes =
	[
		'class',
		'colspan',
		'id',
		'rowspan',
		'title'
	];

	/**
	 * array of forbidden values
	 *
	 * @var array
	 */

	protected $_forbiddenValues =
	[
		'onabort',
		'onafterprint',
		'onautocomplete',
		'onautocompleteerror',
		'onbeforeprint',
		'onbeforeunload',
		'onblur',
		'oncancel',
		'oncanplay',
		'oncanplaythrough',
		'onchange',
		'onclick',
		'onclose',
		'oncontextmenu',
		'oncuechange',
		'ondblclick',
		'ondevicelight',
		'ondevicemotion',
		'ondeviceorientation',
		'ondeviceproximity',
		'ondrag',
		'ondragend',
		'ondragenter',
		'ondragleave',
		'ondragover',
		'ondragstart',
		'ondrop',
		'ondurationchange',
		'onemptied',
		'onended',
		'onerror',
		'onfocus',
		'onhashchange',
		'oninput',
		'oninvalid',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onlanguagechange',
		'onload',
		'onloadeddata',
		'onloadedmetadata',
		'onloadstart',
		'onmessage',
		'onmousedown',
		'onmouseenter',
		'onmouseleave',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
		'onmousewheel',
		'onoffline',
		'ononline',
		'onpagehide',
		'onpageshow',
		'onpause',
		'onplay',
		'onplaying',
		'onpopstate',
		'onprogress',
		'onratechange',
		'onreset',
		'onresize',
		'onscroll',
		'onsearch',
		'onseeked',
		'onseeking',
		'onselect',
		'onshow',
		'onstalled',
		'onstorage',
		'onsubmit',
		'onsuspend',
		'ontimeupdate',
		'ontoggle',
		'ontransitionend',
		'onunload',
		'onuserproximity',
		'onvolumechange',
		'onwaiting',
		'onwheel',
		'style'
	];

	/**
	 * sanitize the html
	 *
	 * @since 2.4.0
	 *
	 * @param string $html target html
	 * @param boolean $filter optional filter nodes
	 *
	 * @return string
	 */

	public function sanitize($html = null, $filter = true)
	{
		$charset = Db::getSetting('charset');
		$html = mb_convert_encoding($html, 'html-entities', $charset);
		$doc = $this->_createDocument($html);
		$doc = $this->_cleanDocument($doc);

		/* filter nodes */

		if ($filter === true)
		{
			$doc = $this->_stripTags($doc);
			$doc = $this->_stripAttributes($doc);
			$doc = $this->_stripValues($doc);
		}

		/* collect output */

		$output = trim($doc->saveHTML());
		return $output;
	}

	/**
	 * create the document
	 *
	 * @since 2.4.0
	 *
	 * @param string $html target html
	 *
	 * @return DOMDocument
	 */

	protected function _createDocument($html = null)
	{
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		return $doc;
	}

	/**
	 * clean the document
	 *
	 * @since 2.6.0
	 *
	 * @param DOMDocument $doc target document
	 *
	 * @return DOMDocument
	 */

	protected function _cleanDocument(DOMDocument $doc)
	{
		/* clean document */

		if ($doc->firstChild->nodeType === XML_DOCUMENT_TYPE_NODE)
		{
			/* remove doctype */

			$doc->removeChild($doc->firstChild);

			/* remove head and body */

			if ($doc->firstChild->firstChild->nodeName === 'head' || $doc->firstChild->firstChild->nodeName === 'body')
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
	 * @param object $node target node
	 *
	 * @return object
	 */

	protected function _stripTags($node = null)
	{
		foreach ($node->childNodes as $childNode)
		{
			if ($childNode->nodeType === XML_ELEMENT_NODE)
			{
				if (!in_array($childNode->tagName, $this->_allowedTags))
				{
					$childNode->parentNode->removeChild($childNode);
				}

				/* strip children tags */

				if ($childNode->hasChildNodes())
				{
					$this->_stripTags($childNode);
				}
			}
		}
		return $node;
	}

	/**
	 * strip the attributes
	 *
	 * @since 2.4.0
	 *
	 * @param object $node target node
	 *
	 * @return object
	 */

	protected function _stripAttributes($node = null)
	{
		foreach ($node->childNodes as $childNode)
		{
			if ($childNode->nodeType === XML_ELEMENT_NODE)
			{
				foreach ($childNode->attributes as $attributeName => $attributeNode)
				{
					if (!in_array($attributeName, $this->_allowedAttributes))
					{
						$childNode->removeAttribute($attributeName);
					}
				}

				/* strip children attributes */

				if ($childNode->hasChildNodes())
				{
					$this->_stripAttributes($childNode);
				}
			}
		}
		return $node;
	}

	/**
	 * strip the values
	 *
	 * @since 2.6.0
	 *
	 * @param object $node target node
	 *
	 * @return object
	 */

	protected function _stripValues($node = null)
	{
		foreach ($node->childNodes as $childNode)
		{
			if ($childNode->nodeType === XML_ELEMENT_NODE)
			{
				/* strip children values */

				if ($childNode->hasChildNodes())
				{
					$this->_stripValues($childNode);
				}
			}
			else
			{
				$childNode->nodeValue = str_ireplace($this->_forbiddenValues, '', $childNode->nodeValue);
			}
		}
		return $node;
	}
}
