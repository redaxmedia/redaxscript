<?php
namespace Redaxscript\Filter;

use DOMDocument;

/**
 * children class to filter html
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

class Html implements Filter
{
	/**
	 * array of tags
	 *
	 * @var array
	 */

	protected $_htmlTags = array(
		'applet',
		'audio',
		'base',
		'bgsound',
		'embed',
		'frame',
		'frameset',
		'function',
		'form',
		'isindex',
		'iframe',
		'img',
		'link',
		'meta',
		'object',
		'script',
		'video'
	);

	/**
	 * array of attributes
	 *
	 * @var array
	 */

	protected $_htmlAttributes = array(
		'archive',
		'background',
		'cite',
		'classid',
		'codebase',
		'dynsrc',
		'fscommand',
		'formaction',
		'icon',
		'href',
		'longdesc',
		'lowsrc',
		'manifest',
		'name',
		'onabort',
		'onactivate',
		'onafterprint',
		'onafterupdate',
		'onbeforeactivate',
		'onbeforecopy',
		'onbeforecut',
		'onbeforedeactivate',
		'onbeforeeditfocus',
		'onbeforepaste',
		'onbeforeprint',
		'onbeforeunload',
		'onbeforeupdate',
		'onblur',
		'onbounce',
		'oncellchange',
		'onchange',
		'onclick',
		'oncontextmenu',
		'oncontrolselect',
		'oncopy',
		'oncut',
		'ondataavailable',
		'ondatasetchanged',
		'ondatasetcomplete',
		'ondblclick',
		'ondeactivate',
		'ondrag',
		'ondragend',
		'ondragenter',
		'ondragleave',
		'ondragover',
		'ondragstart',
		'ondrop',
		'onerror',
		'onerrorupdate',
		'onfilterchange',
		'onfinish',
		'onfocus',
		'onfocusin',
		'onfocusout',
		'onhelp',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onlayoutcomplete',
		'onload',
		'onlosecapture',
		'onmousedown',
		'onmouseenter',
		'onmouseleave',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
		'onmousewheel',
		'onmove',
		'onmoveend',
		'onmovestart',
		'onpaste',
		'onpropertychange',
		'onreadystatechange',
		'onreset',
		'onresize',
		'onresizeend',
		'onresizestart',
		'onrowenter',
		'onrowexit',
		'onrowsdelete',
		'onrowsinserted',
		'onscroll',
		'onselect',
		'onselectionchange',
		'onselectstart',
		'onstartonstop',
		'onsubmit',
		'onunload',
		'poster',
		'profile',
		'rel',
		'src',
		'style',
		'type',
		'usemap'
	);

	/**
	 * sanitize the html
	 *
	 * @since 2.2.0
	 *
	 * @param string $html target with html
	 * @param boolean $filter optional filter nodes
	 *
	 * @return string
	 */

	public function sanitize($html = null, $filter = true)
	{
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$body = $doc->getElementsByTagName('body');

		/* filter nodes */

		if ($filter === true)
		{
			/* disable errors */

			libxml_use_internal_errors(true);

			/* process tags */

			foreach ($this->_htmlTags as $tag)
			{
				$node = $doc->getElementsByTagName($tag);
				foreach ($node as $childNode)
				{
					$childNode->parentNode->removeChild($childNode);
				}
			}

			/* process attributes */

			foreach ($body as $node)
			{
				foreach ($node->childNodes as $childNode)
				{
					if ($childNode->nodeType !== XML_TEXT_NODE)
					{
						foreach ($this->_htmlAttributes as $attribute)
						{
							if ($childNode->hasAttribute($attribute))
							{
								$childNode->removeAttribute($attribute);
							}
						}
					}
				}
			}

			/* clear errors */

			libxml_clear_errors();
		}

		/* clean document */

		$output = $this->_clean($doc);
		return $output;
	}

	/**
	 * clean the document
	 *
	 * @since 2.2.0
	 *
	 * @param DOMDocument $doc target with document
	 *
	 * @return string
	 */

	protected function _clean(DOMDocument $doc)
	{
		$output = '';

		/* cleanup document */

		if (isset($doc->firstChild) && $doc->firstChild->nodeType === XML_DOCUMENT_TYPE_NODE)
		{
			/* remove doctype */

			$doc->removeChild($doc->firstChild);

			/* remove html wrapper */

			if (isset($doc->firstChild->firstChild->firstChild) && $doc->firstChild->firstChild->tagName === 'body')
			{
				$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild);
				$output = trim($doc->saveHTML());
			}
		}
		return $output;
	}
}
