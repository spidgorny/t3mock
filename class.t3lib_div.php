<?php

class t3lib_div {

	static function makeInstance($class, $what_is_it = NULL) {
		return new $class($what_is_it);
	}

	/**
	 * Includes a locallang file and returns the $LOCAL_LANG array found inside.
	 *
	 * @param string $fileRef Input is a file-reference (see \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName). That file is expected to be a 'locallang.php' file containing a $LOCAL_LANG array (will be included!) or a 'locallang.xml' file conataining a valid XML TYPO3 language structure.
	 * @param string $langKey Language key
	 * @param string $charset Character set (option); if not set, determined by the language key
	 * @param integer $errorMode Error mode (when file could not be found): 0 - syslog entry, 1 - do nothing, 2 - throw an exception
	 * @return array Value of $LOCAL_LANG found in the included file. If that array is found it will returned.
	 */
	static public function readLLfile($fileRef, $langKey, $charset = '', $errorMode = 0) {
		/** @var $languageFactory \TYPO3\CMS\Core\Localization\LocalizationFactory */
		$languageFactory = self::makeInstance('TYPO3\\CMS\\Core\\Localization\\LocalizationFactory');
		return $languageFactory->getParsedData($fileRef, $langKey, $charset, $errorMode);
	}

}
