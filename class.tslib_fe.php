<?php

class tslib_fe {

	/**
	 * Frontend page ID
	 * @var int
	 */
	var $id;

	/**
	 * @var tslib_feUserAuth
	 */
	var $fe_user;

	var $additionalHeaderData = array();

	var $csConvObj;

	function __construct() {
		$this->fe_user = new tslib_feUserAuth();
		$this->id = 1;
	}

}
