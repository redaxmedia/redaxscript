<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * children class to reset password post request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class CommentPost implements ControllerInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param Request $request instance of the registry class
	 */

	public function __construct(Registry $registry, Language $language, Request $request)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_request = $request;
	}

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	public function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$urlValidator = new Validator\Url();

		/* process post */

		$postArray = array(
			'article' => $specialFilter->sanitize($this->_request->getPost('article')),
			'author' => $specialFilter->sanitize($this->_request->getPost('author')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text')),
			'route' => build_route('articles', $this->_request->getPost('article')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* data array */

		$dataArray = array(
			'language' => Db::forTablePrefix('articles')->whereIdIs($postArray['article'])->language,
			'date' => Registry::get('now'),
			'rank' => Db::forTablePrefix('comments')->max('rank') + 1,
			'access' => Db::forTablePrefix('articles')->whereIdIs($postArray['article'])->access
		);

		/* validate post */

		if (!$postArray['author'])
		{
			$errorArray[] = $this->_language->get('author_empty');
		}
		if (!$postArray['email'])
		{
			$errorArray[] = $this->_language->get('email_empty');
		}
		if (!$postArray['text'])
		{
			$errorArray[] = $this->_language->get('comment_empty');
		}
		if ($emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('email_incorrect');
		}
		if ($postArray['url'] && $urlValidator->validate($postArray['url']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('url_incorrect');
		}
		if (Db::getSettings('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return self::error($errorArray);
		}

		if ($this->_registry->get('comments_new') == 0 && Db::getSettings('moderation') == 1)
		{
			$postArray['status'] = 0;
			$successMessage = $this->_language->get('comment_moderation');
		}
		else
		{
			$postArray['status'] = 1;
			$successMessage = $this->_language->get('comment_sent');
		}

		/* send comment notification */

		$createArray = array(
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text'],
			'language' => $dataArray['language'],
			'article' => $postArray['article'],
			'status' => $postArray['status']
		);
		$mailArray = array(
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'route' => $postArray['route'],
			'author' => $postArray['author'],
			'text' => $postArray['text']
		);

		/* send comment and mail */

		if ($this->_create($createArray) && $this->_mail($mailArray))
		{
			if (Db::getSettings('notification') == 1)
			{
				return $this->success(array(
					'route' => $postArray['route'],
					'message' => $successMessage,
					'timeout' => 2
				));
			}
			else
			{
				return $this->success(array(
					'route' => $postArray['route'],
					'message' => $successMessage,
					'timeout' => 0
				));
			}
		}
		else
		{
			return $this->error($this->_language->get('something_wrong'));
		}
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successData
	 *
	 * @return string
	 */

	public function success($successData = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('continue'), $successData['route'])->doRedirect($successData['timeout'])->success($successData['message'], $this->_language->get('operation_completed'));
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), $errorArray['route'])->error($errorArray, $this->_language->get('error_occurred'));
	}

	/**
	 * create comment
	 *
	 * @since 3.0.0
	 *
	 * @param $createArray
	 *
	 * @return boolean
	 */

	protected function _create($createArray = array())
	{
		return Db::forTablePrefix('comments')
			->create()
			->set(array(
				'author' => $createArray['author'],
				'email' => $createArray['email'],
				'url' => $createArray['url'],
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'article' => $createArray['article']
			))
			->save();
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return boolean
	 */

	protected function _mail($mailArray = array())
	{
		/* html elements */

		$linkElement = new Html\Element();
		$linkElement
			->init('a', array(
				'href' => 'mailto:' . $mailArray['email']
			))
			->text($mailArray['email']);

		/* prepare body parts */

		if ($mailArray['url'])
		{
			$urlLink = $linkElement
				->copy()
				->attr('href', $mailArray['url'])
				->text($mailArray['url']);
		}
		$articleRoute = $this->_registry->get('root') . '/' . $this->_registry->get('rewrite_route') . $mailArray['route'];
		$articleLink = $linkElement
			->copy()
			->attr('href', $articleRoute)
			->text($articleRoute);

		/* prepare mail inputs */

		$toArray = array(
			$this->_language->get('author') => Db::getSettings('email')
		);
		$fromArray = array(
			$mailArray['author'] => $mailArray['email']
		);

		$subject = $this->_language->get('comment_new');
		$bodyArray = array(
			'<strong>' . $this->_language->get('author') . $this->_language->get('colon') . '</strong> ' . $mailArray['author'],
			'<br />',
			'<strong>' . $this->_language->get('email') . $this->_language->get('colon') . '</strong> ' . $linkElement,
			'<br />',
			'<strong>' . $this->_language->get('url') . $this->_language->get('colon') . '</strong> ' . $urlLink,
			'<br />',
			'<strong>' . $this->_language->get('article') . $this->_language->get('colon') . '</strong> ' . $articleLink,
			'<br />',
			'<br />',
			'<strong>' . $this->_language->get('comment') . $this->_language->get('colon') . '</strong> ' . $mailArray['text']
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}