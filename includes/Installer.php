<?php
namespace Redaxscript;

/**
 * parent class to install the database
 *
 * @since 2.4.0
 *
 * @category Installer
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Installer
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param Config $config instance of the config class
	 */

	public function init(Config $config)
	{
		$this->_config = $config;
	}

	/**
	 * create mysql
	 *
	 * @since 2.4.0
	 */

	public function createMysql()
	{
		$this->_process('create', 'mysql');
	}

	/**
	 * drop mysql
	 *
	 * @since 2.4.0
	 */

	public function dropMysql()
	{
		$this->_process('drop', 'mysql');
	}

	/**
	 * process sql
	 *
	 * @since 2.4.0
	 *
	 * @param string $action action to process
	 * @param string $type type of the database
	 */

	public function _process($action = null, $type = 'mysql')
	{
		$sqlDirectory = new Directory();
		$sqlDirectory->init('database/' . $type . '/' . $action);
		$sqlArray = $sqlDirectory->getArray();

		/* process sql files */

		foreach ($sqlArray as $file)
		{
			$sqlContents = file_get_contents('database/' . $type . '/' . $action . '/' . $file);
			if ($sqlContents)
			{
				if ($this->_config->get('prefix'))
				{
					$sqlContents = $this->_prefix($sqlContents, $action, $type);
				}
				Db::rawExecute($sqlContents);
			}
		}
	}

	/**
	 * prefix tables
	 *
	 * @since 2.4.0
	 *
	 * @param string $contents contents of the sql
	 * @param string $action action to process
	 * @param string $type type of the database
	 *
	 * @return string
	 */

	protected function _prefix($contents = null, $action = null, $type = 'mysql')
	{
		$output = $contents;

		/* mysql */

		if ($type === 'mysql')
		{
			if ($action === 'create')
			{
				$search = 'CREATE TABLE IF NOT EXISTS ';
			}
			if ($action === 'drop')
			{
				$search = 'DROP TABLE ';
			}
			$replace = $search . $this->_config->get('prefix');
			$output = str_replace($search, $replace, $output);
		}
		return $output;
	}
}
