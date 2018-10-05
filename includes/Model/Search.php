<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * parent class to provide the search model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Search
{
	/**
	 * get the search by table
	 *
	 * @since 3.3.0
	 *
	 * @param string $table name of the table
	 * @param string $search value of the search
	 * @param string $language value of the language
	 *
	 * @return object|null
	 */

	public function getByTable(string $table = null, string $search = null, string $language = null) : ?object
	{
		return Db::forTablePrefix($table)
			->whereLikeMany($this->_createColumnArray($table), $this->_createLikeArray($table, $search))
			->whereLanguageIs($language)
			->where('status', 1)
			->orderByDesc('date')
			->findMany() ? : null;
	}

	/**
	 * create the column array
	 *
	 * @since 3.3.0
	 *
	 * @param string $table name of the table
	 *
	 * @return array
	 */

	protected function _createColumnArray(string $table = null) : array
	{
		return array_filter(
		[
			$table === 'categories' || $table === 'articles' ? 'title' : null,
			$table === 'categories' || $table === 'articles' ? 'description' : null,
			$table === 'categories' || $table === 'articles' ? 'keywords' : null,
			$table === 'articles' || $table === 'comments' ? 'text' : null
		]);
	}

	/**
	 * create the like array
	 *
	 * @since 3.3.0
	 *
	 * @param string $table name of the table
	 * @param string $search value of the search
	 *
	 * @return array
	 */

	protected function _createLikeArray(string $table = null, string $search = null) : array
	{
		return array_filter(
		[
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'articles' || $table === 'comments' ? '%' . $search . '%' : null
		]);
	}
}
