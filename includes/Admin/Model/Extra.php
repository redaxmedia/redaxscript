<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin extra model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Extra extends BaseModel\Extra
{
	/**
	 * create the extra by array
	 *
	 * @since 4.0.0
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
	 * update the extra by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $extraId = null, array $updateArray = []) : bool
	{
		return $this
			->query()
			->whereIdIs($extraId)
			->findOne()
			->set($updateArray)
			->save();
	}

	/**
	 * publish the extra by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 *
	 * @return bool
	 */

	public function publishById(int $extraId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($extraId)
			->findOne()
			->set('status', 1)
			->save();
	}

	/**
	 * unpublish the extra by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 *
	 * @return bool
	 */

	public function unpublishById(int $extraId = null) : bool
	{
		return $this
			->query()
			->whereIdIs($extraId)
			->findOne()
			->set('status', 0)
			->save();
	}

	/**
	 * delete the extra by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 *
	 * @return bool
	 */

	public function deleteById(int $extraId = null) : bool
	{
		return $this->query()->whereIdIs($extraId)->deleteMany();
	}
}
