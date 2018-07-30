<?php
namespace Redaxscript\Admin\Model;

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
	/**
	 * update the settings by array
	 *
	 * @since 4.0.0
	 *
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByArray(array $updateArray = []) : bool
	{
		foreach ($updateArray as $key => $value)
		{
			if (!$this->set($key, $value))
			{
				return false;
			}
		}
		return true;
	}
}
