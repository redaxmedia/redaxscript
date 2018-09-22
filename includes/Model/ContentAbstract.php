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
	 * @param string $order name of the column to order
	 *
	 * @return object|null
	 */

	public function getAllByOrder(string $order = null) : ?object
	{
		$items = $this->query()->orderGlobal($order)->findMany();
		return $items ? $items : null;
	}

	/**
	 * get the content by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 * @param string $order name of the column to order
	 *
	 * @return object|null
	 */

	public function getByLanguageAndOrder(string $language = null, string $order = null) : ?object
	{
		$items = $this
			->query()
			->whereLanguageIs($language)
			->where('status', 1)
			->orderGlobal($order)
			->findMany();
		return $items ? $items : null;
	}

	/**
	 * get the content by id and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $id
	 * @param string $language
	 *
	 * @return object|null
	 */

	public function getByIdAndLanguage(int $id = null, string $language = null) : ?object
	{
		$items = $this
			->query()
			->whereIdIs($id)
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
		return $items ? $items : null;
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
