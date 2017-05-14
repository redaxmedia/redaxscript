<?php
namespace Redaxscript\Router;

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
			'up',
			'down',
			'publish',
			'unpublish',
			'enable',
			'disable',
			'install',
			'uninstall',
			'delete',
			'process'
		],
		'edit' =>
		[
			'update'
		]
	];

	/**
	 * get the lite route
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getLite()
	{
		return $this->_getRoute('lite');
	}

	/**
	 * get the full route
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getFull()
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
	 * @return string
	 */

	protected function _getRoute($type = 'lite')
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
		else if (in_array($adminParameter, $this->_resolverArray['edit']))
		{
			$output = 'admin/edit/' . $tableParameter;
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