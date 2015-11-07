<?php

class t3lib_befunc {

	static $tsConfig = array(
			'plugin.' => array(
					'tx_sltimetracking_pi1.' => array(
							'mainFunction' => 'mainOverview',
					),
			),
	);

	static function setMainFunction($function) {
		self::$tsConfig['plugin.']['tx_sltimetracking_pi1.']['mainFunction']
		= $function;
	}

	static function getPagesTSconfig($pageID) {
		return self::$tsConfig;
	}

	static function calcAge($seconds, $labels = 'min|hrs|days|yrs') {
		$labelArr = explode('|', $labels);
		$prefix = '';
		if ($seconds < 0) {
			$prefix = '-';
			$seconds = abs($seconds);
		}
		if ($seconds < 3600) {
			$seconds = round($seconds / 60) . ' ' . trim($labelArr[0]);
		} elseif ($seconds < 24 * 3600) {
			$seconds = round($seconds / 3600) . ' ' . trim($labelArr[1]);
		} elseif ($seconds < 365 * 24 * 3600) {
			$seconds = round($seconds / (24 * 3600)) . ' ' . trim($labelArr[2]);
		} else {
			$seconds = round($seconds / (365 * 24 * 3600)) . ' ' . trim($labelArr[3]);
		}
		return $prefix . $seconds;
	}

}
