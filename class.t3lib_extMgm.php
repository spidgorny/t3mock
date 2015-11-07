<?php

class t3lib_extMgm {

	static function extPath($ext) {
		if ($ext == 'sl_timetracking') {
			return '';
		}
		if ($ext == 'sl_countusers') {
			return 'sl_countusers/';
		}
		return '../';
	}

	static function siteRelPath($ext) {
		return '';
	}

}
