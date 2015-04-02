<?php
namespace Redaxscript;

/**
 * parent class to install the database
 *
 * @since 2.4.0
 *
 * @category Installer
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Installer
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * placeholder for the prefix
	 *
	 * @var string
	 */

	protected $_prefixPlaceholder = '/* {configPrefix} */';

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param Config $config instance of the config class
	 */

	public function init(Config $config)
	{
		$this->_config = $config;
	}

	/**
	 * create a table
	 *
	 * @since 2.4.0
	 */

	public function create()
	{
		$this->_execute('create', $this->_config->get('type'));
	}

	/**
	 * drop a table
	 *
	 * @since 2.4.0
	 */

	public function drop()
	{
		$this->_execute('drop', $this->_config->get('type'));
	}

	/**
	 * insert records
	 *
	 * @since 2.4.0
	 *
	 * @param array $options options of the records
	 */

	public function insertRecords($options = null)
	{
		/* articles */

		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'Welcome',
				'alias' => 'welcome',
				'author' => $options['author'],
				'text' => file_get_contents('database/html/articles/welcome.phtml'),
				'category' => 1,
				'rank' => 1
			))->save();

		/* categories */

		Db::forTablePrefix('categories')
			->create()
			->set(array(
				'title' => 'Home',
				'alias' => 'home',
				'author' => $options['author'],
				'rank' => 1
			))->save();

		/* extras */

		$extrasArray = array(
			'categories' => 1,
			'articles' => 1,
			'comments' => 1,
			'languages' => 0,
			'templates' => 0,
			'footer' => 0
		);
		$extrasRank = 1;

		/* process extras array */

		foreach ($extrasArray as $key => $value)
		{
			Db::forTablePrefix('extras')
				->create()
				->set(array(
					'title' => ucfirst($key),
					'alias' => $key,
					'author' => $options['author'],
					'text' => file_get_contents('database/html/extras/' . $key . '.phtml'),
					'status' => $value,
					'rank' => $extrasRank++
				))->save();
		}

		/* groups */

		Db::forTablePrefix('groups')
			->create()
			->set(array(
				'title' => 'Administrators',
				'alias' => 'administrators',
				'description' => 'Unlimited access',
				'categories' => 1,
				'articles' => 1,
				'extras' => 1,
				'comments' => 1,
				'groups' => 1,
				'users' => 1,
				'modules' => 1,
				'settings' => 1,
				'filter' => 0,
			))->save();
		Db::forTablePrefix('groups')
			->create()
			->set(array(
				'title' => 'Members',
				'alias' => 'members',
				'description' => 'Default members group'
			))->save();

		/* settings */

		Db::forTablePrefix('settings')
			->create()
			->set(array(
				'language' => 'detect',
				'template' => 'default',
				'title' => 'Redaxscript',
				'author' => null,
				'copyright' => null,
				'description' => 'Ultra lightweight CMS',
				'keywords' => null,
				'robots' => 'all',
				'email' => $options['email'],
				'subject' => 'Redaxscript',
				'notification' => 0,
				'charset' => 'utf-8',
				'divider' => ' • ',
				'time' => 'H:i',
				'date' => 'd.m.Y',
				'homepage' => '0',
				'limit' => 10,
				'order' => 'asc',
				'pagination' => '1',
				'moderation' => '0',
				'registration' => '1',
				'verification' => '0',
				'reminder' => '1',
				'captcha' => '0',
				'blocker' => '1',
				'version' => Language::get('version', '_package')
			))->save();
	}

	/**
	 * execute sql query
	 *
	 * @since 2.4.0
	 *
	 * @param string $action action to process
	 * @param string $type type of the database
	 */

	public function _execute($action = null, $type = 'mysql')
	{
		$sqlDirectory = new Directory();
		$sqlDirectory->init('database/' . $type . '/' . $action);
		$sqlArray = $sqlDirectory->getArray();

		/* process sql files */

		foreach ($sqlArray as $file)
		{
			$query = file_get_contents('database/' . $type . '/' . $action . '/' . $file);
			if ($query)
			{
				if ($this->_config->get('prefix'))
				{
					$query = str_replace($this->_prefixPlaceholder, $this->_config->get('prefix'), $query);
				}
				Db::rawExecute($query);
			}
		}
	}
}
