<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * abstract class to create a model class
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

abstract class ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table;

	/**
	 * get the item by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $id
	 *
	 * @return object
	 */

	public function getById(int $id = null)
	{
		return $this->query()->whereIdIs($id)->findOne();
	}

	/**
	 * get all
	 *
	 * @since 4.0.0
	 *
	 * @return object
	 */

	public function getAll()
	{
		return $this->query()->findMany();
	}

	/**
	 * query the table
	 *
	 * @since 4.0.0
	 *
	 * @return object
	 */

	public function query()
	{
		return Db::forTablePrefix($this->_table);
	}

	/**
	 * clear cache for the table
	 *
	 * @since 4.0.0
	 *
	 * @return object
	 */

	public function clearCache()
	{
		return Db::clearCache($this->_table);
	}
}