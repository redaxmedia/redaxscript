<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * parent class to provide the group model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Group
{
	/**
	 * get the group id by alias
	 *
	 * @since 3.3.0
	 *
	 * @param string $groupAlias
	 *
	 * @return int
	 */

	public function getIdByAlias(string $groupAlias = null) : int
	{
		return Db::forTablePrefix('groups')->select('id')->where('alias', $groupAlias)->findOne()->id | 0;
	}
}
