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
		$this->_execute('create');
	}

	/**
	 * insert mysql
	 *
	 * @since 2.4.0
	 */

	public function insertMysql()
	{
		$this->_execute('insert');
	}

	/**
	 * update mysql
	 *
	 * @since 2.4.0
	 */

	public function updateMysql()
	{
		$this->_execute('update');
	}
	
	/**
	 * delete mysql
	 *
	 * @since 2.4.0
	 */

	public function deleteMysql()
	{
		$this->_execute('delete');
	}

	/**
	 * drop mysql
	 *
	 * @since 2.4.0
	 */

	public function dropMysql()
	{
		$this->_execute('drop');
	}

	/**
	 * execute sql query
	 *
	 * @since 2.4.0
	 *
	 * @param string $action action to process
	 * @param string $type type of the database
	 */

	public function _execute($action = null, $type = 'mysql')
	{
		$sqlDirectory = new Directory();
		$sqlDirectory->init('database/' . $type . '/' . $action);
		$sqlArray = $sqlDirectory->getArray();

		/* process sql files */

		foreach ($sqlArray as $file)
		{
			$query = file_get_contents('database/' . $type . '/' . $action . '/' . $file);
			if ($query)
			{
				if ($this->_config->get('prefix'))
				{
					$query = $this->_prefix($query, $action, $type);
				}
				Db::rawExecute($query);
			}
		}
	}

	/**
	 * prefix tables
	 *
	 * @since 2.4.0
	 *
	 * @param string $query raw sql query
	 * @param string $action action to process
	 * @param string $type type of the database
	 *
	 * @return string
	 */

	protected function _prefix($query = null, $action = null, $type = 'mysql')
	{
		$output = $query;

		/* mysql */

		if ($type === 'mysql')
		{
			if ($action === 'create')
			{
				$search = 'CREATE TABLE IF NOT EXISTS ';
			}
			if ($action === 'insert')
			{
				$search = 'INSERT INTO ';
			}
			if ($action === 'update')
			{
				$search = 'UPDATE ';
			}
			if ($action === 'delete')
			{
				$search = 'DELETE FROM ';
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
