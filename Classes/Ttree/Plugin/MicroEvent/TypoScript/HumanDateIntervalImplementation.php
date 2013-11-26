<?php
namespace Ttree\Plugin\MicroEvent\TypoScript;

/*                                                                          *
 * This script belongs to the TYPO3 Flow package "Ttree.Plugin.MicroEvent". *
 *                                                                          *
 * It is free software; you can redistribute it and/or modify it under      *
 * the terms of the GNU General Public License, either version 3 of the     *
 * License, or (at your option) any later version.                          *
 *                                                                          *
 * The TYPO3 project - inspiring people to share!                           *
 *                                                                          */

use TYPO3\Eel\Helper\StringHelper;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n\Formatter\DatetimeFormatter;
use TYPO3\Flow\I18n\Locale;
use TYPO3\Flow\Utility\Unicode\Functions;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;

/**
 * Show a Human Date Interval, with support for date interval in a single month
 *
 * @Flow\Scope("prototype")
 */
class HumanDateIntervalImplementation extends AbstractTypoScriptObject {

	const SINGLE_MONTH_DATE_INTERVAL_PATTERN = '@startDay - @endDay @startMonth @endYear';
	const MULTIPLE_MONTH_DATE_INTERVAL_PATTERN = '@startDay @startMonth - @endDay @endMonth @endYear';

	/**
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * @var \DateTime
	 */
	protected $endDate;

	/**
	 * @Flow\Inject
	 * @var DatetimeFormatter
	 */
	protected $datetimeFormatter;

	/**
	 * @var Locale
	 */
	protected $locale;

	public function __construct(\TYPO3\TypoScript\Core\Runtime $tsRuntime, $path, $typoScriptObjectName) {
		parent::__construct($tsRuntime, $path, $typoScriptObjectName);
		$this->locale = new Locale('fr_FR');
	}


	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}



	/**
	 * Just return the processed value
	 *
	 * @return mixed
	 */
	public function evaluate() {
		$output = NULL;
		/** @var \DateTime $startDate */
		$startDate = $this->tsValue('startDate');
		if ($startDate instanceof \DateTime) {
			return $output;
		}
		/** @var \DateTime $endDate */
		$endDate = $this->tsValue('endDate');
		if ($startDate instanceof \DateTime && !$endDate instanceof \DateTime) {
			$output = $this->formatSingleDate($startDate);
		} elseif ($endDate instanceof \DateTime && $startDate->diff($endDate)->m === 0) {
			$output = $this->formatSingleMonthDateInterval($startDate, $endDate);
		} elseif ($startDate instanceof \DateTime && $endDate instanceof \DateTime) {
			$output = $this->formatMultipleMonthDateInterval($startDate, $endDate);
		}
		return $output;
	}

	/**
	 * @param \DateTime $startDate
	 * @return string
	 */
	protected function formatSingleDate(\DateTime $startDate) {
		return $this->datetimeFormatter->formatDateTimeWithCustomPattern($startDate, 'd MMMM y', $this->locale);
	}

	/**
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @return string
	 */
	protected function formatSingleMonthDateInterval(\DateTime $startDate, \DateTime $endDate) {
		return str_replace(
			array(
				'@startDay',
				'@endDay',
				'@startMonth',
				'@endYear'
			),
			array(
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($startDate, 'd', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($endDate, 'd', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($startDate, 'MMMM', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($endDate, 'y', $this->locale)
			),
			self::SINGLE_MONTH_DATE_INTERVAL_PATTERN
		);
	}

	/**
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @return string
	 */
	protected function formatMultipleMonthDateInterval(\DateTime $startDate, \DateTime $endDate) {
		return str_replace(
			array(
				'@startDay',
				'@endDay',
				'@startMonth',
				'@endMonth',
				'@startYear',
				'@endYear'
			),
			array(
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($startDate, 'd', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($endDate, 'd', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($startDate, 'MMMM', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($endDate, 'MMMM', $this->locale),
				$this->datetimeFormatter->formatDateTimeWithCustomPattern($endDate, 'y', $this->locale)
			),
			self::MULTIPLE_MONTH_DATE_INTERVAL_PATTERN
		);
	}

}
