<?php
namespace Redaxscript;

use DateTime;
use DateTimeZone;
use function date_default_timezone_get;

/**
 * parent class to handle the date time
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Date
 * @author Henry Ruhs
 */

class Dater
{
	/**
	 * time zone
	 *
	 * @var DateTimeZone
	 */

	protected $_timeZone;

	/**
	 * date time
	 *
	 * @var DateTime
	 */

	protected $_dateTime;

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param int $date timestamp of the date
	 */

	public function init(int $date = null) : void
	{
		$zone = null;
		if (Db::getStatus() === 2)
		{
			$settingModel = new Model\Setting();
			$zone = $settingModel->get('zone');
		}
		$this->_timeZone = new DateTimeZone($zone ? : date_default_timezone_get());
		$this->_dateTime = new DateTime();
		$this->_dateTime->setTimezone($this->_timeZone);
		if ($date)
		{
			$this->_dateTime->setTimestamp($date);
		}
	}

	/**
	 * get the time zone
	 *
	 * @since 4.0.0
	 *
	 * @return DateTimeZone
	 */

	public function getTimeZone() : DateTimeZone
	{
		return $this->_timeZone;
	}

	/**
	 * get the date time
	 *
	 * @since 4.0.0
	 *
	 * @return DateTime
	 */

	public function getDateTime() : DateTime
	{
		return $this->_dateTime;
	}

	/**
	 * format to time
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function formatTime() : string
	{
		$settingModel = new Model\Setting();
		return $this->getDateTime()->format($settingModel->get('time'));
	}

	/**
	 * format to date
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function formatDate() : string
	{
		$settingModel = new Model\Setting();
		return $this->getDateTime()->format($settingModel->get('date'));
	}

	/**
	 * format to field
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function formatField() : string
	{
		return $this->getDateTime()->format('Y-m-d\TH:i');
	}
}
