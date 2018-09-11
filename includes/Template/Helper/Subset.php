<?php
namespace Redaxscript\Template\Helper;

/**
 * helper class to provide a subset helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Subset extends HelperAbstract
{
	/**
	 * default subset
	 *
	 * @var string
	 */

	protected $_subset = 'latin';

	/**
	 * array of the subsets
	 *
	 * @var array
	 */

	protected $_subsetArray =
	[
		'cyrillic' =>
		[
			'bg',
			'ru'
		],
		'vietnamese' =>
		[
			'vi'
		]
	];

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function process() : ?string
	{
		$language = $this->_registry->get('language');

		/* process subset */

		foreach ($this->_subsetArray as $subset => $valueArray)
		{
			if (in_array($language, $valueArray))
			{
				return $subset;
			}
		}
		return $this->_subset;
	}
}
