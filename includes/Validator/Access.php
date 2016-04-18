<?php
namespace Redaxscript\Validator;

/**
 * children class to validate access again group
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Access implements ValidatorInterface
{
	/**
	 * validate the access
	 *
	 * @since 2.2.0
	 *
	 * @param string $access content related access restriction
	 * @param string $groups groups the user is a member of
	 *
	 * @return boolean
	 */

	public function validate($access = null, $groups = null)
	{
		$output = ValidatorInterface::FAILED;
		$accessArray = array_filter(explode(',', $access));
		$groupsArray = array_filter(explode(',', $groups));

		/* validate access */

		if (!$access || in_array(1, $groupsArray) || array_intersect($accessArray, $groupsArray))
		{
			$output = ValidatorInterface::PASSED;
		}
		return $output;
	}
}
