<?php

class tslib_pibase {

	var $prefixId;

	var $extKey;

	var $piVars = array();

	/**
	 * @var tslib_cObj
	 */
	var $cObj;

	var $conf = array();

	var $LL = array();

	function __construct() {
		$this->cObj = new tslib_cObj();
	}

	function pi_setPiVarDefaults() {
		$this->piVars = ifsetor($_REQUEST[$this->prefixId]);
	}

	function pi_loadLL() {
		$filename = 'pi1/locallang.xml';
//		d($filename);
//		$filename = realpath($filename);
//		d($filename);
		$default = 'default';
		$content = t3lib_div::readLLfile($filename, $default);
		$this->LL = $content[$default];
		foreach ($this->LL as &$assoc) {
			$assoc = $assoc[0]['target'];
		}
	}

	function pi_wrapInBaseClass($content) {
		return '<div class="'.$this->extKey.'">'.$content.'</div>';
	}

	function pi_getLL($key, $default = NULL) {
		return ifsetor($this->LL[$key], $default ?: $key);
	}

	function pi_getPageLink($pageID, $target = '', array $params = array()) {
		$pi1 = tx_sltimetracking_pi1::$instance;
		return $pi1->getLink($pageID, $params);
	}

	function pi_linkToPage($text, $pageID, $target = NULL, array $params = array()) {
		$link = $this->pi_getPageLink($pageID, $target, $params);
		$content = '<a href="'.$link.'">'.$text.'</a>';
		return $content;
	}

}
