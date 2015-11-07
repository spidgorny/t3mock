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

}
