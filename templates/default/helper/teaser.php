<?php
namespace Redaxscript;

return Db::forTablePrefix('extras')
	->where(
	[
		'alias' => 'teaser',
		'category' => Template\Helper::getRegistry('categoryId')
	])
	->findOne()
	->text;