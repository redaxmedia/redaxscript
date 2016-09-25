<?php
namespace Redaxscript\Assetic;

/**
 * parent class to transport javascript
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Assetic
 * @author Henry Ruhs
 */

class Transport
{
	// @todo this class basically does the job of the deprecated scripts_transport() but without the minify part - delete loader.php asap
	// @todo keep in mind that this class does not return a script tag - just a plain array that is going to be consumed by transportVar() in Head/Script
}