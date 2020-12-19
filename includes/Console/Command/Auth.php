<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Auth as BaseAuth;
use Redaxscript\Console\Parser;
use Redaxscript\Model;
use Redaxscript\Validator;

/**
 * children class to execute the auth command
 *
 * @since 4.5.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Auth extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'auth' =>
		[
			'description' => 'Auth command',
			'argumentArray' =>
			[
				'login' =>
				[
					'description' => 'Login into the system',
					'optionArray' =>
					[
						'user' =>
						[
							'description' => 'Required user'
						],
						'password' =>
						[
							'description' => 'Required password'
						]
					]
				],
				'logout' =>
				[
					'description' => 'Logout by the system'
				]
			]
		]
	];

	/**
	 * run the command
	 *
	 * @since 4.5.0
	 *
	 * @param string $mode name of the mode
	 *
	 * @return string|null
	 */

	public function run(string $mode = null) : ?string
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$argumentKey = $parser->getArgument(1);
		$haltOnError = (bool)$parser->getOption('halt-on-error');
		if ($argumentKey === 'login')
		{
			return $this->_login($parser->getOptionArray()) ? $this->success() : $this->error($haltOnError);
		}
		if ($argumentKey === 'logout')
		{
			return $this->_logout() ? $this->success() : $this->error($haltOnError);
		}
		return $this->getHelp();
	}

	/**
	 * login into the system
	 *
	 * @since 4.5.0
	 *
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _login(array $optionArray = []) : bool
	{
		$userOption = $this->prompt('user', $optionArray);
		$password = $this->prompt('password', $optionArray);
		$passwordValidator = new Validator\Password();
		$auth = new BaseAuth($this->_request);
		$userModel = new Model\User();
		$user = $userModel->getByUser($userOption);
		if ($passwordValidator->matchHash($password, $user->password))
		{
			return $auth->login($user->id);
		}
		return false;
	}

	/**
	 * logout by the system
	 *
	 * @since 4.5.0
	 *
	 * @return bool
	 */

	protected function _logout() : bool
	{
		$auth = new BaseAuth($this->_request);
		return $auth->logout();
	}
}
