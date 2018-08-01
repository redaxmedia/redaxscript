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
	 * @since 4.0.0
	 *
	 * @param string $access content related access
	 * @param string $groups groups of the user
	 *
	 * @return bool
	 */

	public function validate(string $access = null, string $groups = null) : bool
	{
		$accessArray = (array)json_decode($access);
		$groupArray = (array)json_decode($groups);
		return !$access || in_array(1, $groupArray) || array_intersect($accessArray, $groupArray);
	}
}
