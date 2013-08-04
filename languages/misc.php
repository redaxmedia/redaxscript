<?php

/* languages */

$l['ar'] = 'العربية';
$l['bg'] = 'Български';
$l['bs'] = 'Bosanski';
$l['ca'] = 'Català';
$l['cs'] = 'Čeština';
$l['de'] = 'Deutsch';
$l['en'] = 'English';
$l['es'] = 'Español';
$l['et'] = 'Eesti';
$l['fa'] = 'فارسی';
$l['fr'] = 'Français';
$l['he'] = 'עברית';
$l['hu'] = 'Magyar';
$l['id'] = 'Bahasa Indonesia';
$l['it'] = 'Italiano';
$l['lv'] = 'Latviešu';
$l['ms'] = 'Bahasa Melayu';
$l['nl'] = 'Nederlands';
$l['no'] = 'Norsk';
$l['pl'] = 'Polski';
$l['pt'] = 'Português';
$l['ro'] = 'Română';
$l['ru'] = 'Русский';
$l['sk'] = 'Slovenčina';
$l['sv'] = 'Svenska';
$l['tr'] = 'Türkçe';
$l['vi'] = 'Tiếng Việt';
$l['zh'] = '中文';

/* redaxscript */

$l['redaxscript'] = 'Redaxscript';
$l['redaxscript_version'] = 'Nightly build';
$l['redaxscript_website'] = 'redaxscript.com';

/**
 * shortcut
 * 
 * @param string $name
 * @return string
 */

function l($name = '')
{
	global $l;
	$output = entity($l[$name]);
	return $output;
}
?>