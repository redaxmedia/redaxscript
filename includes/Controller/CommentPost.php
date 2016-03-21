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
 * class to handle comment post
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 *
 * @category Controller
 *
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
			'author' => $specialFilter->sanitize($this->_request->getPost('author')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text')),
			'article' => $specialFilter->sanitize($this->_request->getPost('article')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
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
		else if ($emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('email_incorrect');
		}
		if ($postArray['url'] && $urlValidator->validate($postArray['url']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('url_incorrect');
		}
		if (!$postArray['text'])
		{
			$errorArray[] = $this->_language->get('comment_empty');
		}
		if (!$postArray['article'])
		{
			$errorArray[] = $this->_language->get('input_incorrect');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return self::error($errorArray);
		}

		/* handle success */

		$route = build_route('articles', $postArray['article']);
		$createArray = array(
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text'],
			'language' => Db::forTablePrefix('articles')->whereIdIs($postArray['article'])->findOne()->language,
			'article' => $postArray['article'],
			'status' => Db::getSetting('verification') ? 0 : 1
		);
		$mailArray = array(
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'route' => $route,
			'author' => $postArray['author'],
			'text' => $postArray['text'],
			'article' => Db::forTablePrefix('articles')->whereIdIs($postArray['article'])->findOne()->title
		);

		/* create and mail */

		if ($this->_create($createArray) && $this->_mail($mailArray))
		{
			return $this->success(array(
				'route' => $route,
				'timeout' => Db::getSetting('notification') ? 2 : 0
			));
		}
		return $this->error($this->_language->get('something_wrong'));
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successArray array of the success
	 *
	 * @return string
	 */

	public function success($successArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('continue'), $successArray['route'])->doRedirect($successArray['timeout'])->success(Db::getSetting('moderation') ? $this->_language->get('comment_moderation') : $this->_language->get('comment_sent'), $this->_language->get('operation_completed'));
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
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
	 * @param $createArray array of the create
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
	 * @param array $mailArray array of the mail
	 *
	 * @return boolean
	 */

	protected function _mail($mailArray = array())
	{
		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a');
		$linkEmail = $linkElement->copy();
		$linkEmail
			->attr(array(
				'href' => 'mailto:' . $mailArray['email']
			))
			->text($mailArray['email']);
		$linkUrl = $linkElement->copy();
		$linkUrl
			->attr(array(
				'href' => $mailArray['url']
			))
			->text($mailArray['url'] ? $mailArray['url'] : $this->_language->get('none'));
		$urlArticle = $this->_registry->get('root') . '/' . $this->_registry->get('rewrite_route') . $mailArray['route'];
		$linkArticle = $linkElement->copy();
		$linkArticle
			->attr(array(
				'href' => $urlArticle
			))
			->text($urlArticle);

		/* prepare mail */

		$toArray = array(
			$this->_language->get('author') => Db::getSetting('email')
		);
		$fromArray = array(
			$mailArray['author'] => $mailArray['email']
		);
		$subject = $this->_language->get('comment_new');
		$bodyArray = array(
			'<strong>' . $this->_language->get('author') . $this->_language->get('colon') . '</strong> ' . $mailArray['author'],
			'<br />',
			'<strong>' . $this->_language->get('email') . $this->_language->get('colon') . '</strong> ' . $linkEmail,
			'<br />',
			'<strong>' . $this->_language->get('url') . $this->_language->get('colon') . '</strong> ' . $linkUrl . '<br />',
			'<br />',
			'<strong>' . $this->_language->get('article') . $this->_language->get('colon') . '</strong> ' . $linkArticle,
			'<br />',
			'<strong>' . $this->_language->get('comment') . $this->_language->get('colon') . '</strong> ' . $mailArray['text']
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}