<?php
namespace Redaxscript\Modules\Debugger;

use Redaxscript\Db;
use Redaxscript\Head;
use Redaxscript\Module;
use function array_filter;
use function file_get_contents;
use function is_array;
use function json_encode;

/**
 * debug like a boss
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
		'description' => 'Debug like a boss',
		'version' => '4.4.0',
		'access' => '[1]'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
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

	public function renderEnd() : void
	{
		$debuggerArray = $this->_getArray();
		$inline = file_get_contents('modules/Debugger/assets/scripts/debugger.js');

		/* script */

		$script = Head\Script::getInstance();
		echo $script
			->init()
			->transportVar('rs.modules.Debugger.dataArray', $debuggerArray)
			->appendInline($inline);
	}

	/**
	 * getArray
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	protected function _getArray() : array
	{
		return array_filter(
		[
			'database' => $this->_flattenArray(Db::getQueryLog()),
			'registry' => $this->_flattenArray($this->_registry->getArray()),
			'server' => $this->_flattenArray($this->_request->get('server')),
			'get' => $this->_flattenArray($this->_request->get('get')),
			'post' => $this->_flattenArray($this->_request->get('post')),
			'files' => $this->_flattenArray($this->_request->get('files')),
			'session' => $this->_flattenArray($this->_request->get('session')),
			'cookie' => $this->_flattenArray($this->_request->get('cookie'))
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

	protected function _flattenArray(array $dirtyArray = []) : array
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
