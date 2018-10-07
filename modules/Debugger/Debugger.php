<?php
namespace Redaxscript\Modules\Debugger;

use Redaxscript\Db;
use Redaxscript\Head;
use Redaxscript\Module;

/**
 * debugger
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Debugger extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Debugger',
		'alias' => 'Debugger',
		'author' => 'Redaxmedia',
		'description' => 'Debugger',
		'version' => '4.0.0',
		'access' => '[1]'
	];

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $moduleArray custom module setup
	 */

	public function init(array $moduleArray = [])
	{
		parent::init($moduleArray);
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('modules/Debugger/assets/scripts/init.js');

	}

	/**
	 * renderEnd
	 *
	 * @since 4.0.0
	 */

	public function renderEnd()
	{
		$debuggerArray = $this->_getArray();
		$inline = file_get_contents('modules/Debugger/assets/scripts/debugger.js');

		/* script */

		$script = Head\Script::getInstance();
		echo $script
			->init()
			->transportVar('rs.modules.Debugger.data', $debuggerArray)
			->appendInline($inline);
	}

	/**
	 * getArray
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function _getArray() : array
	{
		return array_filter(
		[
			'database' => $this->_flattenArray(Db::getQueryLog()),
			'registry' => $this->_flattenArray($this->_registry->get()),
			'server' => $this->_flattenArray($this->_request->getServer()),
			'get' => $this->_flattenArray($this->_request->getQuery()),
			'post' => $this->_flattenArray($this->_request->getPost()),
			'files' => $this->_flattenArray($this->_request->getFiles()),
			'session' => $this->_flattenArray($this->_request->getSession()),
			'cookie' => $this->_flattenArray($this->_request->getCookie()),
		]);
	}

	/**
	 * flattenArray
	 *
	 * @since 4.0.0
	 *
	 * @param array $dirtyArray
	 *
	 * @return array
	 */

	public function _flattenArray(array $dirtyArray = []) : array
	{
		$flatArray = [];

		/* process dirty */

		foreach ($dirtyArray as $key => $value)
		{
			$flatArray[$key] = is_array($value) ? json_encode($value) : $value;
		}
		return $flatArray;
	}
}
