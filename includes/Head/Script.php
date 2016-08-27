<?php
namespace Redaxscript\Head;

/**
 * children class to create the script tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

class Script extends HeadAbstract
{
	/**
	 * render the script
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		// render the script tag from collection array using html\element
		// empty the collection using clean() after render was triggered

		// HeadAbstract is a Singleton because we have to pass a global collection from the Core to the Modules and back
		// Idea for testing
		// $scriptOne = new Head\Script();
		// $scriptOne->append('src', 'test1');
		// $scriptTwo = new Head\Script();
		// $scriptOne->append('src', 'test2');

		// output of $scriptTwo->render() should be <script src="test1"><script><script src="test2"><script>
	}
}