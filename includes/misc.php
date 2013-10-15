<?php

/**
 * shortcut
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Misc
 * @author Henry Ruhs
 *
 * @param string $name
 * @return string
 */

function b($name = '')
{
	/* script */

	$b['script_tags'] = 'alert, code, expression, java, script, perl, print, xss';
	$b['script_handlers'] = 'fscommand, onabort, onactivate, onafterprint, onafterupdate, onbeforeactivate, onbeforecopy, onbeforecut, onbeforedeactivate, onbeforeeditfocus, onbeforepaste, onbeforeprint, onbeforeunload, onbeforeupdate, onblur, onbounce, oncellchange, onchange, onclick, oncontextmenu, oncontrolselect, oncopy, oncut, ondataavailable, ondatasetchanged, ondatasetcomplete, ondblclick, ondeactivate, ondrag, ondragend, ondragenter, ondragleave, ondragover, ondragstart, ondrop, onerror, onerrorupdate, onfilterchange, onfinish, onfocus, onfocusin, onfocusout, onhelp, onkeydown, onkeypress, onkeyup, onlayoutcomplete, onload, onlosecapture, onmousedown, onmouseenter, onmouseleave, onmousemove, onmouseout, onmouseover, onmouseup, onmousewheel, onmove, onmoveend, onmovestart, onpaste, onpropertychange, onreadystatechange, onreset, onresize, onresizeend, onresizestart, onrowenter, onrowexit, onrowsdelete, onrowsinserted, onscroll, onselect, onselectionchange, onselectstart, onstartonstop, onsubmit, onunload';
	$b['script_characters'] = chr(0x2a) . ', ' . chr(0x2b) . ', ' . chr(0x2d) . chr(0x2d) . ', ' . chr(0x23) . ', ' . chr(0x25) . ', ' . chr(0x26) . ', ' . chr(0x27) . ', ' . chr(0x28) . chr(0x29) . ', ' . chr(0x3b) . ', ' . chr(0x5c) . chr(0x5c) . ', ' . chr(0x7b) . ', ' . chr(0x7d);

	/* html */

	$b['html_tags'] = 'applet, base, basefont, bgsound, body, embed, font, form, frame, frameset, function, head, html, iframe, ilayer, img, input, layer, link, meta, object, table, title, xml';
	$b['html_attributes'] = 'background, codebase, dynsrc, href, lowsrc, name, rel, src, type, url';

	/* default */

	$b['default_alias'] = 'admin, loader, login, logout, password_reset, scripts, styles, registration, reminder';
	$b['default_post'] = 'comment, login, password_reset, registration, reminder, search';

	/* agent */

	$b['agent_browsers'] = 'chrome, firefox, konqueror, msie, netscape, opera, safari';
	$b['agent_engines'] = 'gecko, khtml, presto, trident, webkit';
	$b['agent_desktops'] = 'bsd, linux, macintosh, solaris, windows';
	$b['agent_mobiles'] = 'mobile, android, blackberry, ipod, iphone, palm';
	$b['agent_tablets'] = 'tablet, android, ipad, kindle, xoom';

	/* constant */

	$b['constant_public'] = 'TOKEN, LOGGED_IN, FIRST_PARAMETER, FIRST_SUB_PARAMETER, SECOND_PARAMETER, SECOND_SUB_PARAMETER, THIRD_PARAMETER, THIRD_SUB_PARAMETER, ADMIN_PARAMETER, TABLE_PARAMETER, ID_PARAMETER, ALIAS_PARAMETER, LAST_PARAMETER, LAST_SUB_PARAMETER, FIRST_TABLE, SECOND_TABLE, THIRD_TABLE, LAST_TABLE, FULL_ROUTE, FULL_TOP_ROUTE, REWRITE_ROUTE, LANGUAGE_ROUTE, TEMPLATE_ROUTE, REFRESH_ROUTE, MY_IP, MY_BROWSER, MY_BROWSER_VERSION, MY_ENGINE, MY_DESKTOP, MY_MOBILE, MY_TABLET';
	$output = $b[$name];
	return $output;
}
?>
