<?php
namespace Redaxscript\Validator;

use function preg_match;
use function strlen;

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
	 * pattern for search
	 *
	 * @var string
	 */

	protected $_pattern = '[a-zA-Z0-9-]';

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
	 * get the form pattern
	 *
	 * @since 4.1.0
	 *
	 * @return string
	 */

	public function getFormPattern() : string
	{
		return $this->_pattern . '.{' . $this->_rangeArray['min'] . ',' . $this->_rangeArray['max']  . '}';
	}

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
		return preg_match('/' . $this->_pattern . '/i', $search) && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
	}
}
