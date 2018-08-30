<?php
namespace Redaxscript\Model;

/**
 * abstract class to create a model class
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

abstract class ContentAbstract extends ModelAbstract
{
	/**
	 * get all by order
	 *
	 * @since 4.0.0
	 *
	 * @param string $column name of the column
	 *
	 * @return object
	 */

	public function getAllByOrder(string $column = null)
	{
		return $this->query()->orderGlobal($column)->findMany();
	}

	/**
	 * get the content by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 *
	 * @return object
	 */

	public function getByLanguage(string $language = null)
	{
		return $this
			->query()
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
	}

	/**
	 * get the content by id and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $id
	 * @param string $language
	 *
	 * @return object
	 */

	public function getByIdAndLanguage(int $id = null, string $language = null)
	{
		return $this
			->query()
			->whereIdIs($id)
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
	}

	/**
	 * publish by date
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 *
	 * @return int
	 */

	public function publishByDate(string $date = null) : int
	{
		return $this
			->query()
			->whereLt('date', $date)
			->where('status', 2)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}
}
