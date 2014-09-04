<?php
namespace Redaxscript\Filter;

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
	 * array of tags that will be filtered
	 *
	 * @var array
	 */

	protected $_htmlTags = array(
		'applet',
		'applet',
		'base',
		'basefont',
		'bgsound',
		'body',
		'embed',
		'font',
		'form',
		'frame',
		'frameset',
		'function',
		'head',
		'html',
		'isindex',
		'iframe',
		'ilayer',
		'img',
		'input',
		'layer',
		'link',
		'meta',
		'object',
		'table',
		'title',
		'xml'
	);

	/**
	 * array of attributes that will be filtered
	 *
	 * @var array
	 */

	protected $_htmlAttributes = array(
		'background',
		'codebase',
		'dynsrc',
		'href',
		'lowsrc',
		'name',
		'rel',
		'src',
		'type',
		'url'
	);

	/**
	 * sanitize the html
	 *
	 * @since 2.2.0
	 *
	 * @param string $html target with html
	 *
	 * @return string
	 */

	public function sanitize($html = null)
	{
		$output = str_replace($this->_htmlTags, '', $html);
		$output = str_replace($this->_htmlAttributes, '', $output);
		return $output;
	}
}