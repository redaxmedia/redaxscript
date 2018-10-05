<?php
namespace Redaxscript;

$teaser = Db::forTablePrefix('extras')
	->where('alias', 'teaser')
	->findOne()
	->text;