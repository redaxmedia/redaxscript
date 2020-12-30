<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the module model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Module extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'modules';

	/**
	 * get the module by alias
	 *
	 * @since 4.5.0
	 *
	 * @param string $moduleAlias alias of the module
	 *
	 * @return object|null
	 */

	public function getByAlias(string $moduleAlias = null) : ?object
	{
		return $this->query()->where('alias', $moduleAlias)->findOne() ? : null;
	}

	/**
	 * create the module by array
	 *
	 * @since 3.3.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this
			->query()
			->create()
			->set($createArray)
			->save();
	}

	/**
	 * delete the module by alias
	 *
	 * @since 3.3.0
	 *
	 * @param string $moduleAlias alias of the module
	 *
	 * @return bool
	 */

	public function deleteByAlias(string $moduleAlias = null) : bool
	{
		return $this->query()->where('alias', $moduleAlias)->deleteMany();
	}
}
