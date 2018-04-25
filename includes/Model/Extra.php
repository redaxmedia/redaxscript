<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * parent class to provide the extra model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Extra
{
	/**
	 * get the extra result by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 *
	 * @return object
	 */

	public function getResultByLanguage(string $language = null)
	{
		return Db::forTablePrefix('extras')
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
	}

	/**
	 * get the extra result by alias and language
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias alias of the extra
	 * @param string $language
	 *
	 * @return object
	 */

	public function getResultByAliasAndLanguage(string $extraAlias = null, string $language = null)
	{
		return Db::forTablePrefix('extras')
			->where('alias', $extraAlias)
			->whereLanguageIs($language)
			->findMany();
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
		return Db::forTablePrefix('extras')->findMany();
	}

	/**
	 * publish each extra by date
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 *
	 * @return int
	 */

	public function publishByDate(string $date = null) : int
	{
		return Db::forTablePrefix('extras')
			->where('status', 2)
			->whereLt('date', $date)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}
}
