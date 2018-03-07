<?php
namespace Redaxscript\Modules\Debugger;

use Redaxscript\Db;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * debugger
 *
 * @since 3.3.0
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
		'version' => '3.3.2'
	];

	/**
	 * init
	 *
	 * @since 3.3.0
	 */

	public function init()
	{
		Db::configure('logging', true);
	}

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/Debugger/dist/styles/debugger.min.css');
	}

	/**
	 * renderEnd
	 *
	 * @since 3.3.0
	 */

	public function renderEnd()
	{
		$debuggerArray = $this->_getArray();

		/* html elements */

		$sectionElement = new Html\Element();
		$sectionElement->init('section',
		[
			'class' => 'rs-section-debugger'
		]);
		$boxElement = new Html\Element();
		$boxElement->init('div',
		[
			'class' => 'rs-box-debugger'
		]);
		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-title-debugger'
		]);
		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => 'rs-list-debugger'
		]);
		$itemElement = new Html\Element();
		$itemElement->init('li');

		/* process debugger */

		foreach ($debuggerArray as $debuggerKey => $subArray)
		{
			if ($subArray)
			{
				$boxElement->clear();
				$listElement->clear();

				/* process sub */

				foreach ($subArray as $subKey => $subValue)
				{
					$text = is_string($subKey) ? $subKey . $this->_language->get('colon') . ' ' . $subValue : $subValue;
					$listElement->append(
						$itemElement->text($text)
					);
				}
				$title = $debuggerKey . $this->_language->get('colon') . ' ' . count($subArray);
				$sectionElement->append(
					$boxElement->append(
						$titleElement->text($title) . $listElement
					)
				);
			}
		}
		echo $sectionElement;
	}

	/**
	 * getArray
	 *
	 * @since 3.3.0
	 */

	public function _getArray()
	{
		return
		[
			'database' => Db::getQueryLog(),
			'registry' => $this->_registry->get(),
			'server' => $this->_request->getServer(),
			'get' => $this->_request->getQuery(),
			'post' => $this->_request->getPost(),
			'files' => $this->_request->getFiles(),
			'session' => $this->_request->getSession(),
			'cookie' => $this->_request->getCookie()
		];
	}
}
