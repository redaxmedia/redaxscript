<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the group model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Group extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'groups';

	/**
	 * get the group by alias
	 *
	 * @since 4.0.0
	 *
	 * @param string $groupAlias alias of the extra
	 *
	 * @return object|null
	 */

	public function getByAlias(string $groupAlias = null) : ?object
	{
		$group = $this->query()->where('alias', $groupAlias)->findOne();
		return $group ? $group : null;
	}
}
