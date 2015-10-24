<?php
namespace Redaxscript;

/**
 * parent class to build a router
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class Router extends Parameter
{
	/**
	 * array of the router
	 *
	 * @var array
	 */

	protected $_routerArray = array(
		'view' => array(
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
		),
		'edit' => array(
			'update'
		)
	);

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
		$subArray = array(
			$this->getSub()
		);

		/* admin route */

		if (in_array($adminParameter, $this->_routerArray['view']))
		{
			$output = 'admin/view/' . $tableParameter;
		}
		else if (in_array($adminParameter, $this->_routerArray['edit']))
		{
			$output = 'admin/edit/' . $tableParameter;
		}

		/* else general route */

		else
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