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
	//@todo: 1. this class gets a raw collection from the head bundle and extracts all src and href values
	//@todo: 2. after that it is using the Cache via $cache->validate($bundle) to see if there is a valid cached (not outdated) file present in the cache folder
	//@todo: 3. if there is a valid cached file, the loader returns the path via $cache->getPath($bundle) back to the Head bundle and the Head class generates a single tag from it
	//@todo: 4. otherwise it tries to generate a cached file by using $cache->store($bundle, $content) and returns that one via $cache->getPath($bundle) afterwards
	//@todo: 5. if everything files (cache could not be created) the loader returns false to the head - so the head bundle is going to generate multiple tags like before

	//@todo: there is also an option to return the content instead of the path by using $cache->retrieve($bundle) - maybe for inline code mode!?
	//@todo: how to handle extra attributes like defer=true if we output a single file from the cache? will it be ignored or not added to the bundle!?
	//@todo: after thinking about "separation of concern" principle -> it is not job of the cache class to collect the $content from each file, the loader has todo that using file_get_contents()
	//@todo: $bundle is btw. a string or array that can consists of a set of paths - this will be imploded and hashed inside the cache class to generate a unique filename
}