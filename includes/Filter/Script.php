<?php
namespace Redaxscript\Filter;

/**
 * children class to filter script
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

class Script implements Filter
{
	/**
	 * array of characters that will be filtered
	 *
	 * @var array
	 */

	protected $scriptCharacters = array(
		'*',
		'+',
		'-',
		'#',
		'%',
		'&',
		'\'',
		'()',
		';',
		'\\',
		'{',
		'}'
	);

	/**
	 * array of tags that will be filtered
	 *
	 * @var array
	 */

	protected $scriptTags = array(
		'alert',
		'code',
		'expression',
		'java',
		'script',
		'perl',
		'print',
		'xss'
	);

	/**
	 * array of handlers that will be filtered
	 *
	 * @var array
	 */

	protected $scriptHandlers = array(
		'fscommand',
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
		'onunload'
	);

	/**
	 * sanitize the script
	 *
	 * @since 2.2.0
	 *
	 * @param string $script target with script
	 *
	 * @return string
	 */

	public function sanitize($script = null)
	{
		$output = str_replace($this->scriptCharacters, '', $script);
		$output = str_replace($this->scriptTags, '', $output);
		$output = str_replace($this->scriptHandlers, '', $output);
		return $output;
	}
}