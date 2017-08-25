<?php
namespace Redaxscript\Template\Helper;

/**
 * helper class to provide a direction helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Direction extends HelperAbstract
{
	/**
	 * default direction
	 *
	 * @var string
	 */

	protected $_direction = 'ltr';

	/**
	 * array of the directions
	 *
	 * @var array
	 */

	protected $_directionArray =
	[
		'rtl' =>
		[
			'ar',
			'fa',
			'he'
		]
	];

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$language = $this->_registry->get('language');

		/* process direction */

		foreach ($this->_directionArray as $direction => $valueArray)
		{
			if (in_array($language, $valueArray))
			{
				return $direction;
			}
		}
		return $this->_direction;
	}
}
