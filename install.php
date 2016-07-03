<?php
namespace Redaxscript;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* get instance */

$registry = Registry::getInstance();

/* assets loader */

if ($registry->get('firstParameter') === 'loader' && ($registry->get('secondParameter') === 'styles' || $registry->get('secondParameter') === 'scripts'))
{
	echo loader(Registry::get('secondParameter'), 'outline');
}

/* else render template */

else
{
	include_once('templates/install/install.phtml');
}