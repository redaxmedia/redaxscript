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
				'title' => $createArray['title'],
				'alias' => $createArray['alias'],
				'author' => $createArray['author'],
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'sibling' => $createArray['sibling'],
				'category' => $createArray['category'],
				'article' => $createArray['article'],
				'headline' => $createArray['headline'],
				'status' => $createArray['status'],
				'rank' => $createArray['rank'],
				'access' => $createArray['access'],
				'date' => $createArray['date']
			])
			->save();
	}

	/**
	 * update the extra by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $extraId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($extraId)
			->findOne()
			->set(
			[
				'title' => $updateArray['title'],
				'alias' => $updateArray['alias'],
				'author' => $updateArray['author'],
				'text' => $updateArray['text'],
				'language' => $updateArray['language'],
				'sibling' => $updateArray['sibling'],
				'category' => $updateArray['category'],
				'article' => $updateArray['article'],
				'headline' => $updateArray['headline'],
				'status' => $updateArray['status'],
				'rank' => $updateArray['rank'],
				'access' => $updateArray['access'],
				'date' => $updateArray['date']
			])
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
		return $this->query()
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
		return $this->query()
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
