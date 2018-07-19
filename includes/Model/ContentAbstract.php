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
		return $this->query()
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
		return $this->query()
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
		return $this->query()
			->where('status', 2)
			->whereLt('date', $date)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}
}