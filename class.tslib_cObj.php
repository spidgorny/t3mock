<?php

class tslib_cObj {

	var $data = array(
		'colPos' => 0,
		'select_key' => '',
	);

	function enableFields($table, $yesOrNo = true) {
		if ($yesOrNo) {
			if ($table == 'fe_users') {
				return " AND NOT $table.disable AND NOT $table.deleted";
			} else {
				return " AND NOT $table.hidden AND NOT $table.deleted";
			}
		} else {
			return '';
		}
	}

	function getSubpart($template, $part) {
//		debug($template, $part);
		$parts = trimExplode($part, $template);
		$templatePart = $parts[1];
		return $templatePart;
	}

	function substituteSubpart($template, $part, $replacement) {
		$subPart = $this->getSubpart($template, $part);
		$replaced = str_replace($subPart, $replacement, $template);
		return $replaced;
	}

}
