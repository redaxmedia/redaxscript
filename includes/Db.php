<?php
namespace Redaxscript;

use ORM;
use PDO;
use PDOException;

/**
 * children class to handle the database
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Db
 * @author Henry Ruhs
 *
 * @method $this _addJoinSource(string $operator, string $table, string|array $constraint, string $tableAlias)
 * @method $this _addOrderBy(string $column, string $value)
 * @method $this _addWhere(string $clause, string|array $value)
 */

class Db extends ORM
{
	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected static $_config;

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Config $config instance of the config class
	 */

	public static function construct(Config $config)
	{
		self::$_config = $config;
	}

	/**
	 * init the class
	 *
	 * @since 3.1.0
	 */

	public static function init()
	{
		$dbType = self::$_config->get('dbType');
		$dbHost = self::$_config->get('dbHost');
		$dbName = self::$_config->get('dbName');
		$dbUser = self::$_config->get('dbUser');
		$dbPassword = self::$_config->get('dbPassword');
		$dbSocket = strstr($dbHost, '.sock');

		/* handle various types */

		if ($dbType === 'mssql' || $dbType === 'mysql' || $dbType === 'pgsql')
		{
			if ($dbType === 'mssql')
			{
				self::configure('connection_string', 'sqlsrv:server=' . $dbHost . ';database=' . $dbName);
			}
			if ($dbType === 'mysql')
			{
				self::configure('connection_string', 'mysql:' . ($dbSocket ? 'unix_socket' : 'host') . '=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8');
			}
			if ($dbType === 'pgsql')
			{
				self::configure('connection_string', 'pgsql:' . ($dbSocket ? 'unix_socket' : 'host') . '=' . $dbHost . ';dbname=' . $dbName . ';options=--client_encoding=utf8');
			}

			/* username and password */

			self::configure('username', $dbUser);
			if ($dbPassword)
			{
				self::configure('password', $dbPassword);
			}
		}

		/* else handle sqlite */

		if ($dbType === 'sqlite')
		{
			self::configure('sqlite:' . $dbHost);
		}

		/* general */

		self::configure(
		[
			'caching' => true,
			'caching_auto_clear' => true,
			'return_result_sets' => true
		]);
	}

	/**
	 * get the database status
	 *
	 * @since 3.1.0
	 *
	 * @return int
	 */

	public static function getStatus() : int
	{
		$output = 0;

		/* has connection */

		try
		{
			$dbType = self::$_config->get('dbType') === 'mssql' ? 'sqlsrv' : self::$_config->get('dbType');
			$dbDriver = self::getDb()->getAttribute(PDO::ATTR_DRIVER_NAME);
			if ($dbType === $dbDriver)
			{
				$output = self::countTablePrefix() > 7 ? 2 : 1;
			}
		}
		catch (PDOException $exception)
		{
			$output = 0;
		}
		return $output;
	}

	/**
	 * count table with prefix
	 *
	 * @since 3.1.0
	 *
	 * @return int
	 */

	public static function countTablePrefix() : int
	{
		$output = 0;
		$dbType = self::$_config->get('dbType');
		$dbName = self::$_config->get('dbName');
		$dbPrefix = self::$_config->get('dbPrefix');

		/* mssql and mysql */

		if ($dbType === 'mssql' || $dbType === 'mysql')
		{
			$output = self::forTable('information_schema.tables')
				->where($dbType === 'mssql' ? 'table_catalog' : 'table_schema', $dbName)
				->whereLike('table_name', $dbPrefix . '%')
				->count();
		}

		/* pgsql */

		if ($dbType === 'pgsql')
		{
			$output = self::forTable('pg_catalog.pg_tables')
				->whereLike('tablename', $dbPrefix . '%')
				->whereNotLike('tablename', 'pg_%')
				->whereNotLike('tablename', 'sql_%')
				->count();
		}

		/* sqlite */

		if ($dbType === 'sqlite')
		{
			$output = self::forTable('sqlite_master')
				->whereLike('tbl_name', $dbPrefix . '%')
				->whereNotLike('tbl_name', 'sql_%')
				->count();
		}
		return $output;
	}

	/**
	 * for table with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $connection which connection to use
	 *
	 * @return self
	 */

	public static function forTablePrefix(string $table = null, string $connection = self::DEFAULT_CONNECTION) : self
	{
		return new self(self::$_config->get('dbPrefix') . $table, [], $connection);
	}

	/**
	 * left join with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string|array $constraint constraint as needed
	 * @param string $tableAlias alias of the table
	 *
	 * @return self
	 */

	public function leftJoinPrefix(string $table = null, string $constraint = null, string $tableAlias = null) : self
	{
		return $this->_addJoinSource('LEFT', self::$_config->get('dbPrefix') . $table, $constraint, $tableAlias);
	}

	/**
	 * where like with many
	 *
	 * @since 3.0.0
	 *
	 * @param array $columnArray array of column names
	 * @param array $likeArray array of the like
	 *
	 * @return self
	 */

	public function whereLikeMany(array $columnArray = [], array $likeArray = []) : self
	{
		return $this->_addWhere('(' . implode($columnArray, ' LIKE ? OR ') . ' LIKE ?)', $likeArray);
	}

	/**
	 * where language is
	 *
	 * @since 3.0.0
	 *
	 * @param string $language value of the language
	 *
	 * @return self
	 */

	public function whereLanguageIs(string $language = null) : self
	{
		return $this->_addWhere('(language = ? OR language IS NULL)', $language);
	}

	/**
	 * order by global setting
	 *
	 * @since 3.3.0
	 *
	 * @param string $column name of the column
	 *
	 * @return self
	 */

	public function orderBySetting(string $column = null) : self
	{
		$order = self::forTablePrefix('settings')->where('name', 'order')->findOne()->value;
		return $this->_addOrderBy($column, $order);
	}

	/**
	 * limit by global setting
	 *
	 * @since 3.3.0
	 *
	 * @param int $step step of the offset
	 *
	 * @return self
	 */

	public function limitBySetting(int $step = null) : self
	{
		$limit = self::forTablePrefix('settings')->where('name', 'limit')->findOne()->value;
		$this->_limit = $step ? $step * $limit . ', ' . $limit : $limit;
		return $this;
	}
}
