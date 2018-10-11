<?php
namespace Redaxscript;

return Db::forTablePrefix('extras')
	->where('alias', 'teaser')
	->findOne()
	->text;