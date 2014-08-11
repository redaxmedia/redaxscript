<?php
namespace Redaxscript\Validator;
use Redaxscript_Validator_Interface;

/**
 * DNS validator
 *
 * @since 2.2.0
 *
 * @category Validator
 * @package Redaxscript
 * @author Sven Weingartner
 */

class Access implements Redaxscript_Validator_Interface
{


	/**
	 * checks the validator
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 * @author Sven Weingartner
	 *
	 * @param int|string $access
	 * @param string $groups
	 *
	 * @return integer
	 */

	public function validate($access = '', $groups = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		$access_array = explode(', ', $access);
		$groups_array = explode(', ', $groups);

		/* intersect access and groups */
		//TODO: please use type safe === all over the place
		if ($access == 0 || in_array(1, $groups_array) || array_intersect($access_array, $groups_array))
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}
		return $output;
	}
}
