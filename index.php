<?php

/**
 * This is just an example from my site
 * http://beta.rechnung-plus.de/
 * Class Typo3Index
 */
class Typo3Index {

	/**
	 * @var Config
	 */
	var $config;

	/**
	 * @var tx_sltimetracking_pi1
	 */
	var $pi1;

	/**
	 * @var Request
	 */
	var $request;

	function __construct() {
		ini_set('display_errors', true);
		ini_set('log_errors', true);
		error_reporting(E_ALL);
		ini_set('error_prepend_string', '<pre style="
		border: solid 1px silver;
		background: #eeeeee;
		white-space: pre-wrap;
		padding: 0.5em;">');
		ini_set('error_append_string', '</pre>');
		set_time_limit(1);
		define('BR', "<br />\n");
		if (!extension_loaded('xdebug')) {
			require_once 'pi1/ext/class.mojoDebugger.php';
		}
		require_once 'pi1/functions.php';
		require_once 'nadlib/init.php';
		$this->initAutoload();
	}

	function initTYPO3() {
		define('PATH_tslib', 't3mock/');
		define('PATH_xajax', 'vendor/typo3-ter/xajax/');
		define('PATH_t3lib', 't3mock/');
		define('PATH_site', getcwd() . '/');
		define('PATH_typo3', getcwd() . '/');
		define('PATH_typo3conf', getcwd() . '/');
		define('TYPO3_OS', php_sapi_name());
		define('TYPO3_MODE', 'frontend');
		define('DEVELOPMENT', true);
		$GLOBALS['TYPO3_CONF_VARS'] = array(
		TYPO3_MODE => array(
		'XCLASS' => array(
		'ext/xajax/class.tx_xajax.php'                            => NULL,
		'ext/xajax/class.tx_xajax_response.php'                   => NULL,
		'ext/sl_timetracking/pi1/class.tx_sltimetracking_pi1.php' => NULL,
		)
		),
		);
		$GLOBALS['TSFE'] = $tsfe = new tslib_fe();
		$GLOBALS['profiler'] = new TaylorProfiler();

		$GLOBALS['TYPO3_DB'] = new stdClass();
		$GLOBALS['TYPO3_DB']->link = NULL;
		$this->config = Config::getInstance();
		$GLOBALS['TYPO3_DB'] = new t3lib_DB();
	}

	function initTimeTracking() {
		$this->pi1 = new tx_sltimetracking_pi1();
		$this->config->afterPI1();

		$this->request = new Request();
	}

	function initAutoload() {
		spl_autoload_register(array($this, 'autoload'));
	}

	function autoload($class) {
		global $TYPO3_CONF_VARS;
		if (false !== strpos($class, '\\')) {
			// PSR-4
			$file = str_replace('\\', '/', $class).'.php';
			/** @noinspection PhpIncludeInspection */
			require_once $file;
		}
		$folders = array(
		'pi1/class.'.$class.'.php',
		't3mock/class.'.$class.'.php',
		'nadlib1/class.'.$class.'.php',
		'pi1/ext/class.'.$class.'.php',
		'pi1/model/class.'.$class.'.php',
		'nadlib/class.'.$class.'.php',
		'nadlib/DB/class.'.$class.'.php',
		'nadlib/HTTP/class.'.$class.'.php',
		'nadlib/HTMLForm/class.'.$class.'.php',
		'nadlib/HTMLForm/'.$class.'.php',
		'nadlib/HTML/class.'.$class.'.php',
		'nadlib/Data/class.'.$class.'.php',
		'nadlib/Controller/class.'.$class.'.php',
		'nadlib/Debug/class.'.$class.'.php',
		'nadlib/LocalLang/class.'.$class.'.php',
		'nadlib/ORM/class.'.$class.'.php',
		'nadlib/SQL/class.'.$class.'.php',
		);
		foreach ($folders as $file) {
			if ($class == 'dbLayerBase') {
				//d($class, $file, file_exists($file));
			}
			if (file_exists($file)) {
				/** @noinspection PhpIncludeInspection */
				require_once $file;
				break;
			}
		}
	}

	function getMainFunction() {
		$main = $this->request->getControllerString();
		return $main;
	}

	function run() {
		$this->initTYPO3();
		$feSession = new FESession();
		if ($feSession->isLoggedIn()) {
			$this->initTimeTracking();
			$content = $this->renderTimeTracking();
		} elseif ($feSession->isLoginAttempt()) {
			$content = $feSession->tryToLogin();
		} else {
			$content = $feSession->showLoginForm();

			//$this->initTimeTracking();
			//$content = $this->renderTimeTracking();
		}
		return $content;
	}

	function renderTimeTracking() {
		$mainFunction = $this->getMainFunction();
		t3lib_befunc::setMainFunction($mainFunction);
		$pageByMenu = array_flip($this->pi1->menuByPage);
		$activeMenuID = $pageByMenu[$this->getActiveMenu()];
		$this->pi1->tsfe->id = $activeMenuID;

		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']
		['t3lib/cache/frontend/class.t3lib_Cache\Frontend\VariableFrontend.php']
		['set'] = NULL;
		$GLOBALS['LANG'] = NULL;
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['xml']
		= \TYPO3\CMS\Core\Localization\Parser\LocallangXmlParser::class;
		$be = new \TYPO3\CMS\Core\Cache\Backend\NullBackend('context');
		$fe = new \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend('t3lib_l10n', $be);
		$cm = new \TYPO3\CMS\Core\Cache\CacheManager();
		$cm->registerCache($fe);
		$GLOBALS['typo3CacheManager'] = $cm;

		$content[] = $this->pi1->main('', array());

		$html = new View('template/template.phtml');
		$html->title = 'Rechnung+ 2.0';
		$html->headerData = implode("\n", $this->pi1->tsfe->additionalHeaderData);
		$html->content = implode("\n", $content);

		$html->h1 = $this->pi1->menuOptions[$this->getActiveMenu()];
		$html->h2 = $mainFunction;

		$html->activeMenu = $this->pi1->tsfe->id;

		$html->userSelection = $this->getUserSelection();
		$html->countUsers = sizeof($this->pi1->getFEUserChoice());
		$html->clientCount = $this->pi1->clients;
		$html->projectCount = $this->pi1->projects;
		$html->invoiceCount = $this->pi1->invoices;
		$html->workCount = $this->pi1->works;
		$html->groupCount = 0;
		return $html;
	}

	function getActiveMenu() {
		$mainFunction = $this->getMainFunction();
		if (in_array($mainFunction, ['editForm', 'deleteForm', 'saveForm'])) {
			$activeMenu = $this->request->getTrim('action');
			$activeMenu = $activeMenu ?:
			$this->request->getSubRequest($this->pi1->prefixId)
			->getTrim('action');	// saveForm
		} else {
			$activeMenu = $mainFunction;
		}
		return $activeMenu;
	}

	function getUserSelection() {
		$content = [];
		$users = $this->pi1->getFEUserChoice();
		foreach ($users as $user) {
			$userObj = new FEUser($user);
			$v = new View(__DIR__ . '/template/UserSelection.phtml');
			$v->user = $userObj;
			$content[] = $v->render();
		}
		$content = implode("\n", $content);
		return $content;
	}

}

echo (new Typo3Index())->run();
