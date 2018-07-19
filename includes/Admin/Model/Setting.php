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
		$settingArray =
		[
			'language' => $updateArray['language'],
			'template' => $updateArray['template'],
			'title' => $updateArray['title'],
			'author' => $updateArray['author'],
			'copyright' => $updateArray['copyright'],
			'description' => $updateArray['description'],
			'keywords' => $updateArray['keywords'],
			'robots' => $updateArray['robots'],
			'email' => $updateArray['email'],
			'subject' => $updateArray['subject'],
			'notification' => $updateArray['notification'],
			'charset' => $updateArray['charset'],
			'divider' => $updateArray['divider'],
			'time' => $updateArray['time'],
			'date' => $updateArray['date'],
			'homepage' => $updateArray['homepage'],
			'limit' => $updateArray['limit'],
			'order' => $updateArray['order'],
			'pagination' => $updateArray['pagination'],
			'moderation' => $updateArray['moderation'],
			'registration' => $updateArray['registration'],
			'verification' => $updateArray['verification'],
			'recovery' => $updateArray['recovery'],
			'captcha' => $updateArray['captcha']
		];

		/* process setting */

		foreach ($settingArray as $key => $value)
		{
			return $this->query()
				->where('name', $key)
				->findOne()
				->set('value', $value)
				->save();
		}
	}
}