<?php
namespace Redaxscript\Validator;

/**
 * children class to validate search
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Search implements ValidatorInterface
{
	/**
	 * allowed range for search
	 *
	 * @var array
	 */

	protected $_rangeArray =
	[
		'min' => 3,
		'max' => 100
	];

	/**
	 * validate the search
	 *
	 * @since 3.0.0
	 *
	 * @param string $search target search term
	 * @param string $placeholder target search placeholder
	 *
	 * @return boolean
	 */

	public function validate($search = null, $placeholder = null)
	{
		$output = ValidatorInterface::FAILED;
		$length = strlen($search);

		/* validate search */

		if ($search !== $placeholder && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'])
		{
			$output = ValidatorInterface::PASSED;
		}
		return $output;
	}
}