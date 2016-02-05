<?php

class t3lib_extMgm {

	static function extPath($ext) {
		if ($ext == 'sl_timetracking') {
			return __DIR__.'/../';
		}
		if ($ext == 'sl_countusers') {
			return __DIR__.'/../sl_countusers/';
		}
		return __DIR__.'/../';
	}

	static function siteRelPath($ext) {
		return '';
	}

}
