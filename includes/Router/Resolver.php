<?php
namespace Redaxscript\Router;

use function array_diff;
use function implode;
use function in_array;

/**
 * children class to resolve a route
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class Resolver extends Parameter
{
	/**
	 * array of the resolver
	 *
	 * @var array
	 */

	protected $_resolverArray =
	[
		'view' =>
		[
			'enable',
			'disabled',
			'publish',
			'unpublish',
			'install',
			'uninstall',
			'delete'
		]
	];

	/**
	 * get the lite route
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getLite() : ?string
	{
		return $this->_getRoute('lite');
	}

	/**
	 * get the full route
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getFull() : ?string
	{
		return $this->_getRoute('full');
	}

	/**
	 * get the route
	 *
	 * @since 2.4.0
	 *
	 * @param string $type type of the route
	 *
	 * @return string|null
	 */

	protected function _getRoute(string $type = 'lite') : ?string
	{
		$output = null;
		$adminParameter = $this->getAdmin();
		$tableParameter = $this->getTable();
		$subArray =
		[
			$this->getLastSub()
		];

		/* admin route */

		if (in_array($adminParameter, $this->_resolverArray['view']))
		{
			$output = 'admin/view/' . $tableParameter;
		}

		/* else general route */

		else if ($this->_parameterArray)
		{
			if ($type === 'lite')
			{
				$output = implode('/', array_diff($this->_parameterArray, $subArray));
			}
			else if ($type === 'full')
			{
				$output = implode('/', $this->_parameterArray);
			}
		}
		return $output;
	}
}