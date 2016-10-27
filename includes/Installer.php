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
	 * name of the directory
	 *
	 * @var string
	 */

	protected $_directory;

	/**
	 * placeholder for the prefix
	 *
	 * @var string
	 */

	protected $_prefixPlaceholder = '/* %PREFIX% */';

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Config $config instance of the config class
	 */

	public function __construct(Config $config)
	{
		$this->_config = $config;
	}

	/**
	 * init the class
	 *
	 * @since 2.6.0
	 *
	 * @param string $directory name of the directory
	 */

	public function init($directory = 'database')
	{
		$this->_directory = $directory;
	}

	/**
	 * create from sql
	 *
	 * @since 2.4.0
	 */

	public function rawCreate()
	{
		$this->_rawExecute('create', $this->_config->get('dbType'));
	}

	/**
	 * drop from sql
	 *
	 * @since 2.4.0
	 */

	public function rawDrop()
	{
		$this->_rawExecute('drop', $this->_config->get('dbType'));
	}

	/**
	 * insert the data
	 *
	 * @since 2.4.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertData($optionArray = null)
	{
		$language = Language::getInstance();
		$language->init();

		/* articles */

		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Welcome',
				'alias' => 'welcome',
				'author' => $optionArray['adminUser'],
				'text' => file_get_contents('database/html/articles/welcome.phtml'),
				'category' => 1,
				'comments' => 1,
				'rank' => 1
			])
			->save();

		/* categories */

		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'title' => 'Home',
				'alias' => 'home',
				'author' => $optionArray['adminUser'],
				'rank' => 1
			])
			->save();

		/* extras */

		$extrasArray =
		[
			'categories' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 1
			],
			'articles' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 1
			],
			'comments' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 1
			],
			'languages' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 0
			],
			'templates' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 0
			],
			'teaser' =>
			[
				'category' => 1,
				'headline' => 0,
				'status' => 0
			]
		];
		$extrasRank = 0;

		/* process extras array */

		foreach ($extrasArray as $key => $value)
		{
			Db::forTablePrefix('extras')
				->create()
				->set(
				[
					'title' => ucfirst($key),
					'alias' => $key,
					'author' => $optionArray['adminUser'],
					'text' => file_get_contents('database/html/extras/' . $key . '.phtml'),
					'category' => $value['category'],
					'headline' => $value['headline'],
					'status' => $value['status'],
					'rank' => ++$extrasRank
				])
				->save();
		}

		/* comments */

		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => $optionArray['adminUser'],
				'email' => $optionArray['adminEmail'],
				'text' => file_get_contents('database/html/comments/hello.phtml'),
				'article' => 1,
				'rank' => 1
			])
			->save();

		/* groups */

		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Administrators',
				'alias' => 'administrators',
				'description' => 'Unlimited access',
				'categories' => '1, 2, 3',
				'articles' => '1, 2, 3',
				'extras' => '1, 2, 3',
				'comments' => '1, 2, 3',
				'groups' => '1, 2, 3',
				'users' => '1, 2, 3',
				'modules' => '1, 2, 3',
				'settings' => 1,
				'filter' => 0
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Members',
				'alias' => 'members',
				'description' => 'Default members group'
			])
			->save();

		/* modules */

		if (is_dir('modules/CallHome'))
		{
			$callHome = new Modules\CallHome\CallHome;
			$callHome->install();
		}
		if (is_dir('modules/Validator'))
		{
			$validator = new Modules\Validator\Validator;
			$validator->install();
		}

		/* settings */

		$settingArray =
		[
			'language' => 'detect',
			'template' => 'default',
			'title' => $language->get('name', '_package'),
			'author' => $optionArray['adminName'],
			'copyright' => null,
			'description' => $language->get('description', '_package'),
			'keywords' => null,
			'robots' => null,
			'email' => $optionArray['adminEmail'],
			'subject' => $language->get('name', '_package'),
			'notification' => 0,
			'charset' => 'utf-8',
			'divider' => ' - ',
			'time' => 'H:i',
			'date' => 'd.m.Y',
			'homepage' => 0,
			'limit' => 10,
			'order' => 'asc',
			'pagination' => 1,
			'registration' => 1,
			'verification' => 0,
			'recovery' => 1,
			'moderation' => 0,
			'captcha' => 0,
			'version' => $language->get('version', '_package')
		];

		/* process settings array */

		foreach ($settingArray as $name => $value)
		{
			Db::forTablePrefix('settings')
				->create()
				->set(
				[
					'name' => $name,
					'value' => $value
				])
				->save();
		}

		/* users */

		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init($optionArray['adminPassword']);
		Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => $optionArray['adminName'],
				'user' => $optionArray['adminUser'],
				'password' => $passwordHash->getHash(),
				'email' => $optionArray['adminEmail'],
				'description' => 'God admin',
				'groups' => '1'
			])
			->save();
	}

	/**
	 * execute from sql
	 *
	 * @since 2.4.0
	 *
	 * @param string $action action to process
	 * @param string $type type of the database
	 */

	protected function _rawExecute($action = null, $type = 'mysql')
	{
		$sqlDirectory = new Directory();
		$sqlDirectory->init($this->_directory . '/' . $type . '/' . $action);
		$sqlArray = $sqlDirectory->getArray();

		/* process sql */

		foreach ($sqlArray as $file)
		{
			$query = file_get_contents($this->_directory . '/' . $type . '/' . $action . '/' . $file);
			if ($query)
			{
				if ($this->_config->get('dbPrefix'))
				{
					$query = str_replace($this->_prefixPlaceholder, $this->_config->get('dbPrefix'), $query);
				}
				Db::rawExecute($query);
			}
		}
	}
}
