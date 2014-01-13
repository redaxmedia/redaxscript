<?php

/**
 * Migrate
 *
 * Provides wrapper functions for new objects which are called as functions
 * by template files.
 *
 * @since 2.1.0
 *
 * @category Migrate
 * @package Redaxscript
 * @author Gary Aylward
 */

/**
 * breadcrumb
 *
 * Wrapper function for Breadcrumb class for backwards compatibility
 *
 * @since 2.1.0
 *
 * @category Migrate
 * @package Redaxscript
 * @author Gary Aylward
 */

function breadcrumb()
{
	$constants = Redaxscript_Constants::getInstance();
	$breadcrumb = new Redaxscript_Breadcrumb($constants);
	echo $breadcrumb->displayBreadcrumb();
}

/**
 * migrate_constants
 *
 * Function to migrate defined constants to an array to inject into the new
 * constants class
 *
 * @since 2.1.0
 *
 * @category Migrate
 * @package Redaxscript
 * @author Gary Aylward
 *
 * @return array
 */

function migrate_constants()
{
	/* get all constants into an array */
	$constants = get_defined_constants(false);

	foreach ($constants as $key => $value)
	{
		/* split constant name into words */
		$key_words = explode('_', $key);
		$count = 0;

		if (is_array($key_words))
		{
			foreach ($key_words as &$key_word)
			{
				if ($count === 0)
				{
					/* convert first word to lower case */
					$key_word = strtolower($key_word);
					$count++;
				}
				else
				{
					/* convert subsequent words to lower case with first letter upper case */
					$key_word = ucfirst(strtolower($key_word));
				}
			}
			$new_key = implode('', $key_words);
		}
		else
		{
			/* only one word so make it lower case */
			$new_key = strtolower($key_words);
		}

		/* change key name */
		$constants[$new_key] = $constants[$key];
		unset($constants[$key]);
	}
	return $constants;
}

?>