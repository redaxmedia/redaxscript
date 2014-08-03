<?php

/**
 * clean
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @param integer $mode
 * @return string
 */

function clean($input = '', $mode = '')
{
	$output = $input;

	/* if untrusted user */

	if (FILTER == 1)
	{
		if ($mode == 0)
		{
			$output = clean_special($output);
		}
		if ($mode == 1)
		{
			$output = clean_script($output);
			$output = clean_html($output);
		}
	}

	/* type related clean */

	if ($mode == 2)
	{
		$output = clean_alias($output);
	}
	if ($mode == 3)
	{
		$output = clean_email($output);
	}
	if ($mode == 4)
	{
		$output = clean_url($output);
	}

	/* mysql clean */

	$output = clean_mysql($output);
	return $output;
}

/**
 * clean special
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_special($input = '')
{
	$output = preg_replace('/[^a-z0-9]/i', '', $input);
	return $output;
}

/**
 * clean script
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_script($input = '')
{
	$script_characters = array(
		chr(0x2a),
		chr(0x2b),
		chr(0x2d) . chr(0x2d),
		chr(0x23),
		chr(0x25),
		chr(0x26),
		chr(0x27),
		chr(0x28) . chr(0x29),
		chr(0x3b),
		chr(0x5c) . chr(0x5c),
		chr(0x7b),
		chr(0x7d)
	);
	$script_tags = array(
		'alert',
		'code',
		'expression',
		'java',
		'script',
		'perl',
		'print',
		'xss'
	);
	$script_handlers = array(
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
	$search = $script_tags . $script_handlers;
	foreach ($search as $key => $value)
	{
		$replace[$key] = '[' . $value . ']';
		$search[$key] = '/' . $value . '/i';
	}
	$output = str_replace($script_characters, '', $input);
	$output = preg_replace($search, $replace, $output);
	return $output;
}

/**
 * clean html
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_html($input = '')
{
	$html_tags = array(
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
	$html_attributes = array(
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
	$search =$html_tags . $html_attributes;
	foreach ($search as $key => $value)
	{
		$replace[$key] = '[' . $value . ']';
		$search[$key] = '/' . $value . '/i';
	}
	$output = preg_replace($search, $replace, $input);
	return $output;
}

/**
 * clean alias
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_alias($input = '')
{
	$output = trim(strtolower($input));
	$output = preg_replace('/[^a-z0-9_]/i', ' ', $output);
	$output = preg_replace('/\s+/i', '-', $output);
	return $output;
}

/**
 * clean email
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_email($input = '')
{
	$output = trim(strtolower($input));
	$output = preg_replace('/[^@a-z0-9._-]/i', '', $input);
	return $output;
}

/**
 * clean url
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_url($input = '')
{
	$output = trim($input);
	$output = preg_replace('/www.(.*?)/i', '', $output);
	return $output;
}

/**
 * clean mysql
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_mysql($input = '')
{
	if (get_magic_quotes_gpc())
	{
		$input = stripslashes($input);
	}

	/* mysql real escape */

	if (DB_CONNECTED == 1 && function_exists('mysql_real_escape_string'))
	{
		$output = mysql_real_escape_string($input);
	}

	/* mysql escape fallback */

	else if (function_exists('mysql_escape_string'))
	{
		$output = mysql_escape_string($input);
	}
	return $output;
}