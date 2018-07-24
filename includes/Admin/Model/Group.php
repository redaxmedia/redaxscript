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
			->set(
			[
				'name' => $createArray['name'],
				'alias' => $createArray['alias'],
				'description' => $createArray['description'],
				'categories' => $createArray['categories'],
				'articles' => $createArray['articles'],
				'extras' => $createArray['extras'],
				'comments' => $createArray['comments'],
				'groups' => $createArray['groups'],
				'users' => $createArray['users'],
				'modules' => $createArray['modules'],
				'settings' => $createArray['settings'],
				'filter' => $createArray['filter'],
				'status' => $createArray['status']
			])
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
			->set(
			[
				'name' => $updateArray['name'],
				'description' => $updateArray['description'],
				'categories' => $updateArray['categories'],
				'articles' => $updateArray['articles'],
				'extras' => $updateArray['extras'],
				'comments' => $updateArray['comments'],
				'groups' => $updateArray['groups'],
				'users' => $updateArray['users'],
				'modules' => $updateArray['modules'],
				'settings' => $updateArray['settings'],
				'filter' => $updateArray['filter'],
				'status' => $updateArray['status']
			])
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
