<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin group model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Group extends BaseModel\Group
{
	/**
	 * create the group by array
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this->query()
			->create()
			->set($createArray)
			->save();
	}

	/**
	 * update the group by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $groupId identifier of the group
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $groupId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($groupId)
			->findOne()
			->set($updateArray)
			->save();
	}

	/**
	 * enable the group by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $groupId identifier of the group
	 *
	 * @return bool
	 */

	public function enableById(int $groupId = null) : bool
	{
		return $this->query()
			->whereIdIs($groupId)
			->findOne()
			->set('status', 1)
			->save();
	}

	/**
	 * disable the group by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $groupId identifier of the group
	 *
	 * @return bool
	 */

	public function disableById(int $groupId = null) : bool
	{
		return $this->query()
			->whereIdIs($groupId)
			->findOne()
			->set('status', 0)
			->save();
	}

	/**
	 * delete the group by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $groupId identifier of the group
	 *
	 * @return bool
	 */

	public function deleteById(int $groupId = null) : bool
	{
		return $this->query()->whereIdIs($groupId)->deleteMany();
	}
}
