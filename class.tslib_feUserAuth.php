<?php

class tslib_feUserAuth {

	var $user = array();

	function __construct() {
		$session = new FESession();
		$user = $session->getUserBySession();
		if ($user) {
			$this->user = $user->data;
		}
	}

	function setKey($type, $key, $val) {
		if (session_status() != PHP_SESSION_ACTIVE) {
			session_start();
		}
		$_SESSION[__CLASS__][$key] = $val;
		//d($_SESSION);
	}

	function getKey($type, $key) {
		if (session_status() != PHP_SESSION_ACTIVE) {
			session_start();
		}
		return ifsetor($_SESSION[__CLASS__][$key]);
	}

	function storeSessionData() {

	}

}
