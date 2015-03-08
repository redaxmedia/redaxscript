<?php
namespace Redaxscript\Validator;

/**
 * children class to validate access again group
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Access implements Validator
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
		$output = Validator::FAILED;
		$accessArray = array_filter(explode(',', $access));
		$groupsArray = array_filter(explode(',', $groups));

		/* validate access again group */

		if ($access == 0 || in_array(1, $groupsArray) || array_intersect($accessArray, $groupsArray))
		{
			$output = Validator::PASSED;
		}
		return $output;
	}
}
