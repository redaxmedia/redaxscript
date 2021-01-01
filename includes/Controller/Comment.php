<?php
namespace Redaxscript\Controller;

use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Model;
use Redaxscript\Validator;

/**
 * children class to process the comment request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Comment extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		$articleModel = new Model\Article();
		$commentModel = new Model\Comment();
		$settingModel = new Model\Setting();
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);

		/* handle validate */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => $this->_getErrorRoute($postArray),
				'message' => $validateArray
			]);
		}

		/* handle create */

		$createArray =
		[
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text'],
			'language' => $articleModel->getById($postArray['article'])->language,
			'article' => $postArray['article'],
			'status' => $settingModel->get('moderation') ? 0 : 1,
			'rank' => $commentModel->query()->max('rank') + 1,
			'date' => $this->_registry->get('now')
		];
		if (!$this->_create($createArray))
		{
			return $this->_error(
			[
				'route' => $this->_getErrorRoute($postArray)
			]);
		}

		/* handle mail */

		$mailArray =
		[
			'author' => $postArray['author'],
			'email' => $postArray['email'],
			'url' => $postArray['url'],
			'text' => $postArray['text'],
			'article' => $articleModel->getById($postArray['article'])->title,
			'route' => $this->_getSuccessRoute($postArray)
		];
		if (!$this->_mail($mailArray))
		{
			return $this->_warning(
			[
				'route' => $this->_getSuccessRoute($postArray),
				'timeout' => 2,
				'message' => $this->_language->get('email_failed')
			]);
		}

		/* handle success */

		return $this->_success(
		[
			'route' => $settingModel->get('moderation') ? $this->_getErrorRoute($postArray) : $this->_getSuccessRoute($postArray),
			'timeout' => 2,
			'message' => $settingModel->get('moderation') ? $this->_language->get('comment_moderation') : $this->_language->get('comment_sent')
		]);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$htmlFilter = new Filter\Html();
		$emailFilter = new Filter\Email();
		$numberFilter = new Filter\Number();
		$textFilter = new Filter\Text();
		$urlFilter = new Filter\Url();

		/* sanitize post */

		return
		[
			'author' => $textFilter->sanitize($this->_request->getPost('author')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text')),
			'article' => $numberFilter->sanitize($this->_request->getPost('article')),
			'task' => $numberFilter->sanitize($this->_request->getPost('task')),
			'solution' => $textFilter->sanitize($this->_request->getPost('solution'))
		];
	}

	/**
	 * validate the post
	 *
	 * @since 3.3.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost(array $postArray = []) : array
	{
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$nameValidator = new Validator\Name();
		$urlValidator = new Validator\Url();
		$settingModel = new Model\Setting();
		$validateArray = [];

		/* validate post */

		if (!$postArray['author'])
		{
			$validateArray[] = $this->_language->get('author_empty');
		}
		else if (!$nameValidator->validate($postArray['author']))
		{
			$validateArray[] = $this->_language->get('author_incorrect');
		}
		if (!$postArray['email'])
		{
			$validateArray[] = $this->_language->get('email_empty');
		}
		else if (!$emailValidator->validate($postArray['email']))
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		if ($postArray['url'] && !$urlValidator->validate($postArray['url']))
		{
			$validateArray[] = $this->_language->get('url_incorrect');
		}
		if (!$postArray['text'])
		{
			$validateArray[] = $this->_language->get('comment_empty');
		}
		if (!$postArray['article'])
		{
			$validateArray[] = $this->_language->get('article_empty');
		}
		if ($settingModel->get('captcha') > 0 && !$captchaValidator->validate($postArray['task'], $postArray['solution']))
		{
			$validateArray[] = $this->_language->get('captcha_incorrect');
		}
		return $validateArray;
	}

	/**
	 * create the comment
	 *
	 * @since 3.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$commentModel = new Model\Comment();
		return $commentModel->createByArray($createArray);
	}

	/**
	 * send the mail
	 *
	 * @since 3.3.0
	 *
	 * @param array $mailArray array of the mail
	 *
	 * @return bool
	 */

	protected function _mail(array $mailArray = []) : bool
	{
		$settingModel = new Model\Setting();
		$urlArticle = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $mailArray['route'];

		/* html element */

		$element = new Html\Element();
		$linkEmail = $element
			->copy()
			->init('a',
			[
				'href' => 'mailto:' . $mailArray['email']
			])
			->text($mailArray['email']);
		$linkUrl = $element
			->copy()
			->init('a',
			[
				'href' => $mailArray['url']
			])
			->text($mailArray['url'] ? : $this->_language->get('none'));
		$linkArticle = $element
			->copy()
			->init('a',
			[
				'href' => $urlArticle
			])
			->text($urlArticle);

		/* prepare mail */

		$toArray =
		[
			$this->_language->get('author') => $settingModel->get('email')
		];
		$fromArray =
		[
			$mailArray['author'] => $mailArray['email']
		];
		$subject = $this->_language->get('comment_new');
		$bodyArray =
		[
			$this->_language->get('author') . $this->_language->get('colon') . ' ' . $mailArray['author'],
			'<br />',
			$this->_language->get('email') . $this->_language->get('colon') . ' ' . $linkEmail,
			'<br />',
			$this->_language->get('url') . $this->_language->get('colon') . ' ' . $linkUrl,
			'<br />',
			$this->_language->get('article') . $this->_language->get('colon') . ' ' . $linkArticle,
			'<br />',
			$this->_language->get('comment') . $this->_language->get('colon') . ' ' . $mailArray['text']
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}

	/**
	 * get success route
	 *
	 * @since 4.5.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return string|null
	 */

	protected function _getSuccessRoute(array $postArray = []) : ?string
	{
		$articleModel = new Model\Article();
		$commentModel = new Model\Comment();
		$commentId = $commentModel->maxIdByArticleAndLanguage($postArray['article'], $articleModel->getById($postArray['article'])->language);
		if ($commentId)
		{
			return $commentModel->getRouteById($commentId);
		}
		return $articleModel->getRouteById($postArray['article']);
	}

	/**
	 * get error route
	 *
	 * @since 4.5.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return string|null
	 */

	protected function _getErrorRoute(array $postArray = []) : ?string
	{
		$articleModel = new Model\Article();
		return $articleModel->getRouteById($postArray['article']);
	}
}
