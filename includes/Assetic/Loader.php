<?php
namespace Redaxscript\Assetic;

/**
 * parent class to load and concat assets
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Assetic
 * @author Henry Ruhs
 */

class Loader
{
	//@todo: this class gets a raw collection from the head bundle and extracts all src and href values (???how to handle defer attributes???)
	//@todo: after that it is using the Cache via $cache->validate($sourceArray) to see if there is a valid cache (not outdated) version present
	//@todo: if there is a valid cached version, the loader returns the path via $cache->getPath($sourceArray) to the Head bundle
	//@todo: otherwise it returns either false or the original head collection back

	//@todo: thre is also an option to return the content instead of the path by using $cache->retrieve($sourceArray) - maybe for inline code mode!?
	//@todo: i suggest to pass the $extention like js and css to the Cache class
}