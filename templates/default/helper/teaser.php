<?php
namespace Redaxscript;

$teaser = Db::forTablePrefix('extras')
	->where(
	[
		'alias' => 'teaser',
		'category' => Template\Helper::getRegistry('categoryId')
	])
	->findOne()
	->text;