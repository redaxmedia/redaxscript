<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Db;
use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin setting model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Setting extends BaseModel\Setting
{
	protected function updateByArray(array $updateArray = []) : bool
	{
		foreach ($updateArray as $key => $value)
		{
			if ($value == 'select')
			{
				$value = null;
			}
			return Db::forTablePrefix('settings')
				->where('name', $key)
				->findOne()
				->set('value', $value)
				->save();
		}
	}
}