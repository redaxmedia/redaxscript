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
	 * placeholder for the prefix
	 *
	 * @var string
	 */

	protected $_prefixPlaceholder = '/* {configPrefix} */';

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
	 * create table
	 *
	 * @since 2.4.0
	 */

	public function create()
	{
		$this->_execute('create', $this->_config->get('type'));
	}

	/**
	 * insert into table
	 *
	 * @since 2.4.0
	 */

	public function insert()
	{
		$this->_execute('insert', $this->_config->get('type'));
	}

	/**
	 * update table
	 *
	 * @since 2.4.0
	 */

	public function update()
	{
		$this->_execute('update', $this->_config->get('type'));
	}

	/**
	 * delete from table
	 *
	 * @since 2.4.0
	 */

	public function delete()
	{
		$this->_execute('delete', $this->_config->get('type'));
	}

	/**
	 * drop table
	 *
	 * @since 2.4.0
	 */

	public function drop()
	{
		$this->_execute('drop', $this->_config->get('type'));
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
					$query = str_replace($this->_prefixPlaceholder, $this->_config->get('prefix'), $query);
				}
				Db::rawExecute($query);
			}
		}
	}
}
