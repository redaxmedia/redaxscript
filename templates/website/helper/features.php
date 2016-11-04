<?php namespace Redaxscript; ?>

<?php $features = Db::forTablePrefix('articles')->where('alias', 'features')->findOne(); ?>