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
	 * @return object|null
	 */

	public function getById(int $id = null) : ?object
	{
		return $this->query()->whereIdIs($id)->findOne() ? : null;
	}

	/**
	 * get all
	 *
	 * @since 4.0.0
	 *
	 * @return object|null
	 */

	public function getAll() : ?object
	{
		return $this->query()->findMany() ? : null;
	}

	/**
	 * query the table
	 *
	 * @since 4.0.0
	 *
	 * @return Db
	 */

	public function query() : Db
	{
		return Db::forTablePrefix($this->_table);
	}

	/**
	 * clear cache for the table
	 *
	 * @since 4.0.0
	 */

	public function clearCache()
	{
		Db::clearCache($this->_table);
	}
}
