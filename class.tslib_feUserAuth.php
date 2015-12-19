<?php

class tslib_feUserAuth {

	/**
	 * @var FEUser
	 */
	var $feUser;

	var $user = array();

	/**
	 * @var array
	 */
	var $uc;

	function __construct() {
		$session = new FESession();
		$this->feUser = $session->getUserBySession();
		if ($this->feUser) {
			$this->user = $this->feUser->data;
			$this->uc = unserialize($this->user['uc']);
			//debug($this->uc);
		}
	}

	function setKey($type, $key, $val) {
		if ($type == 'user') {
			$this->uc[$key] = $val;
		} else {
			if (session_status() != PHP_SESSION_ACTIVE) {
				session_start();
			}
			$_SESSION[__CLASS__][$key] = $val;
			//d($_SESSION);
		}
	}

	function getKey($type, $key) {
		if ($type == 'user') {
			return ifsetor($this->uc[$key]);
		} else {
			if (session_status() != PHP_SESSION_ACTIVE) {
				session_start();
			}
			return ifsetor($_SESSION[__CLASS__][$key]);
		}
	}

	function storeSessionData() {
		$this->feUser->update([
			'uc' => serialize($this->uc),
		]);
	}

}
