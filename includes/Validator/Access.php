<?php
namespace Redaxscript\Validator;
use Redaxscript_Validator_Interface;

/**
 * children class to validate access
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Access implements Redaxscript_Validator_Interface
{
	/**
	 * validate the access
	 *
	 * @since 2.2.0
	 *
	 * @param string $access content related access restriction
	 * @param string $groups groups the user is a member of
	 *
	 * @return integer
	 */

	public function validate($access = null, $groups = null)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
		$accessArray = explode(',', $access);
		$groupsArray = explode(',', $groups);

		/* validate data */

		if ($access == 0 || in_array(1, $groupsArray) || array_intersect($accessArray, $groupsArray))
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}
		return $output;
	}
}
