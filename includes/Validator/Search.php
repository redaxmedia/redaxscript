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
	 * @since 4.0.0
	 *
	 * @param string $search search term
	 *
	 * @return bool
	 */

	public function validate(string $search = null) : bool
	{
		$length = strlen($search);
		return $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
	}
}