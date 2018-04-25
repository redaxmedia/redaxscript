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
