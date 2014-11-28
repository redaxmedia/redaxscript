<?php
namespace Redaxscript;

use ORM;

/**
 * children class to handle the database
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Db
 * @author Henry Ruhs
 *
 * @method _addJoinSource()
 * @method _addOrderBy()
 * @method _setupDb()
 * @method deleteMany()
 * @method deleteOne()
 * @method findMany()
 * @method findOne()
 * @method rawExecute()
 * @method rawQuery()
 * @method orderByAsc()
 * @method orderByDesc()
 * @method selectExpr()
 * @method tableAlias()
 * @method whereIn()
 */

class Db extends ORM
{
	/**
	 * connect to database
	 *
	 * @since 2.2.0
	 *
	 * @param Config $config instance of the config class
	 */

	public static function init(Config $config)
	{
		$type = $config::get('type');
		$file = $config::get('file');
		$host = $config::get('host');
		$name = $config::get('name');
		$user = $config::get('user');
		$password = $config::get('password');

		/* sqlite */

		if ($type === 'sqlite')
		{
			self::configure('sqlite:' . $file);
		}

		/* mysql and pgsql */

		if ($type === 'mysql' || $type === 'pgsql')
		{
			if ($type === 'mysql')
			{
				self::configure('connection_string', 'mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8');
			}
			else
			{
				self::configure('connection_string', 'pgsql:host=' . $host . ';dbname=' . $name . ';options=--client_encoding=utf8');
			}

			/* username and password */

			self::configure(array(
				'username' => $user,
				'password' => $password
			));
		}

		/* general */

		self::configure(array(
			'caching' => true,
			'caching_auto_clear' => true,
			'return_result_sets' => true
		));
	}

	/**
	 * for table with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $connection which connection to use
	 *
	 * @return Db
	 */

	public static function forTablePrefix($table = null, $connection = self::DEFAULT_CONNECTION)
	{
		self::_setupDb($connection);
		return new self(Config::get('prefix') . $table, array(), $connection);
	}

	/**
	 * left join with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $constraint constraint as needed
	 * @param string $tableAlias alias of the table
	 *
	 * @return Db
	 */

	public function leftJoinPrefix($table = null, $constraint = null, $tableAlias = null)
	{
		return $this->_addJoinSource('LEFT', Config::get('prefix') . $table, $constraint, $tableAlias);
	}

	/**
	 * get item from settings
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public static function getSettings($key = null)
	{
		$output = false;
		$settings = self::forTablePrefix('settings')->findMany();

		/* process settings */

		foreach ($settings as $value)
		{
			if ($value->name === $key)
			{
				$output = $value->value;
			}
		}
		return $output;
	}

	/**
	 * order according to settings
	 *
	 * @since 2.2.0
	 *
	 * @param string $column name of the column
	 *
	 * @return Db
	 */

	public function orderGlobal($column = null)
	{
		return $this->_addOrderBy($column, self::getSettings('order'));
	}

	/**
	 * limit according to settings
	 *
	 * @since 2.2.0
	 *
	 * @return Db
	 */

	public function limitGlobal()
	{
		$this->_limit = self::getSettings('limit');
		return $this;
	}
}
