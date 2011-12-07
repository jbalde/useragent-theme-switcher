<?php
/*
Plugin Name: UserAgent theme switcher
Plugin URI: http://labs.altairstudios.es/user-agent-theme-switcher
Description: This plugins switch theme for any useragent, specialy for iphone, chrome mobile, opera mobile, etc. or in a groups of browser same "mobile" or IE to add this template at all browser of this category
Author: Juan Benavides Romero <juan@altairstudios.es>
Author URI: http://www.altairstudios.es
Version: 3.0.0
*/


/**
 * UserAgent Theme Switcher class to process the plugin
 * @author Juan Benavides Romero <juan@altairstudios.es>
 */
class UserAgentThemeSwitcher {
	/**
	 * Replaced theme for the browser
	 * @var array
	 */
	private $theme = null;


	/**
	 * Detected useragent
	 * @var BrowserUA
	 */
	private $userAgent = null;


	/**
	 * To check useragent
	 * @var BrowserUA
	 */
	private $checkUserAgent = null;


	/**
	 * Database helper
	 * @var UserAgentThemeSwitcherData
	 */
	private $database;


	/**
	 * Internal server path
	 * @var string
	 */
	private $path;


	/**
	 * Plugin compilation version to add news database features
	 * @var int
	 */
	private $version = 300;


	/**
	 * Url to blog
	 * @var string
	 */
	private $blogUrl;


	/**
	 * Constant action to delete a rule
	 */
	const ACTION_DELETERULE = 'deleterule';


	/**
	 * Constant action to sync a browser with a theme
	 */
	const ACTION_SYNCBROWSER = 'syncbrowser';


	/**
	 * Constant action to sync a tag with a theme
	 */
	const ACTION_SYNCTAG = 'synctag';


	/**
	 * Constant action to update debug mode
	 */
	const ACTION_DEBUG = 'updatedebug';


	/**
	 * Constant action to check useragent
	 */
	const ACTION_CHECKUSERAGENT = 'checkuseragent';


	/**
	 * Constant action to report a unsoported useragent
	 */
	const ACTION_REPORTUSERAGENT = 'reportuseragent';


	/**
	 * Constant action to delete a unsoported useragent
	 */
	const ACTION_DELETEUSERAGENT = 'deleteuseragent';


	/**
	 * Constant action to truncate all debug useragent
	 */
	const ACTION_TRUNCATEDEBUGUSERAGENT = 'truncatedebuguseragent';


	/**
	 * Constant page template manager
	 */
	const PAGE_TEMPLATE = 'useragent-template';


	/**
	 * Constant page debug manager
	 */
	const PAGE_DEBUG = 'useragent-debug';


	/**
	 * Default constructor. Include the files to works
	 */
	public function __construct() {
		include('BrowserUA.php');
		include('BrowserList.php');
		include('UserAgentThemeSwitcherData.php');
	}//__construct


	/**
	 * Initialize the UserAgent Theme Switcher plugin
	 * @global wpdb $wpdb Wordpress database connection
	 * @global string $table_prefix Database table prefix
	 */
	public function initialize() {
		global $wpdb;
		global $table_prefix;

		$this->database = new UserAgentThemeSwitcherData($wpdb, $table_prefix);
		$this->path = dirname(__FILE__);
		$this->blogUrl = get_bloginfo('wpurl');

		$this->database->updateDatabase($this->version);

		$this->processAction();
		$this->parseBrowser();
		//load_plugin_textdomain('useragenthemeswitcher', false, $this->path);

		add_action('admin_menu', array($this, 'createMenu'));
		add_action('init', array($this, 'pageProcess'));
		add_filter('template', array(&$this, 'switchTemplate'));
		add_filter('stylesheet', array(&$this, 'switchStylesheet'));
	}//initialize


	/**
	 * Process the actions for the admin menu
	 */
	private function processAction() {
		$page = $this->getParameter('page');
		$action = $this->getParameter('action');

		if($page == UserAgentThemeSwitcher::PAGE_TEMPLATE) {
			if($action == UserAgentThemeSwitcher::ACTION_DELETERULE) {
				$this->database->deleteRule($this->getParameter('code'));
			} else if($action == UserAgentThemeSwitcher::ACTION_SYNCBROWSER) {
				$this->database->addRule($this->getParameter('browser'), $this->getParameter('theme'));
			} else if($action == UserAgentThemeSwitcher::ACTION_SYNCTAG) {
				$this->database->addRule($this->getParameter('tag'), $this->getParameter('theme'));
			}
		} else if($page == UserAgentThemeSwitcher::PAGE_DEBUG) {
			if($action == UserAgentThemeSwitcher::ACTION_DEBUG) {
				if($this->getParameter('debug') == null) {
					update_option(UserAgentThemeSwitcherData::DEBUG_KEY, "false");
				} else {
					update_option(UserAgentThemeSwitcherData::DEBUG_KEY, "true");
				}
			} else if($action == UserAgentThemeSwitcher::ACTION_REPORTUSERAGENT) {
				mail('juan.benavides.romero@gmail.com', 'Unsoported useragent report', $this->getParameter('useragent'));
			} else if($action == UserAgentThemeSwitcher::ACTION_DELETEUSERAGENT) {
				$this->database->deleteUserAgent($this->getParameter('useragent'));
			} else if($action == UserAgentThemeSwitcher::ACTION_TRUNCATEDEBUGUSERAGENT) {
				$this->database->truncateDebugUserAgents();
			} else if($action == UserAgentThemeSwitcher::ACTION_CHECKUSERAGENT) {
				$this->checkUserAgent = $this->checkBrowser($this->getParameter('useragenttocheck'));
			}
		}
	}//processAction


	/**
	 * Create the admin menu structure
	 */
	public function createMenu() {
		add_menu_page('Theme Switcher', 'Theme Switcher', 'manage_options', UserAgentThemeSwitcher::PAGE_TEMPLATE, array($this, 'processUserAgentTemplate'), null);
		add_submenu_page(UserAgentThemeSwitcher::PAGE_TEMPLATE, 'Cache', 'cache options', 'manage_options', 'useragent-cache', array($this, 'processUserAgentCache'), null);
		add_submenu_page(UserAgentThemeSwitcher::PAGE_TEMPLATE, 'Unsoported user agents', 'debuged useragents', 'manage_options', 'useragent-debug', array($this, 'processUserAgentDebug'), null);
	}//createMenu


	/**
	 * Load the administrator page for set a theme by browser
	 */
	public function processUserAgentTemplate() {
		$browsersWithoutTheme = $this->database->getBrowsersWithoutTheme();
		$browsersWithTheme = $this->database->getBrowsersWithTheme();
		$rules = $this->database->getConfiguratedTemplates();
		$themes = get_themes();
		$tags = $this->database->getWebTags();

		include('template/'.UserAgentThemeSwitcher::PAGE_TEMPLATE.'.php');
	}//processUserAgentTemplate


	/**
	 * Load the administrator page for set the cache options
	 */
	public function processUserAgentCache() {
		include('template/useragent-cache.php');
	}//processUserAgentCache


	/**
	 * Load the administrator page for debug options
	 */
	public function processUserAgentDebug() {
		$isDebug = get_option(UserAgentThemeSwitcherData::DEBUG_KEY);
		$useragents = $this->database->getDebugUserAgents();

		$useragentName = '';

		if($this->checkUserAgent != null) {
			$useragentName = $this->checkUserAgent->getName();
		}

		include('template/useragent-debug.php');
	}//processUserAgentDebug


	/**
	 * Process a page load to register a new unsporoted useragent if the debug mode is active
	 */
	public function pageProcess() {
		$debugmode = get_option(UserAgentThemeSwitcherData::DEBUG_KEY);

		if($debugmode == 'true' && $this->userAgent == null && $_SERVER['HTTP_USER_AGENT'] != '') {
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$this->database->addDebugUserAgent($useragent);
		}
	}//pageProcess


	/**
	 * Switch a template dinamicly
	 * @param string $template Template name
	 * @return Template name
	 */
	public function switchTemplate($template) {
		if($this->theme != null) {
			$theme = get_theme($this->theme);
		} else {
			return $template;
		}

		return $theme['Template'];
	}//switchTemplate


	/**
	 * Switch a stylesheet dinamicly
	 * @param string $stylesheet Stylesheet name
	 * @return string
	 */
	public function switchStylesheet($stylesheet = '') {
		if($this->theme != null) {
			$theme = get_theme($this->theme);
		} else {
			return $stylesheet;
		}

		return $theme['Stylesheet'];
	}//switchStylesheet


	/**
	 * Parse the browsers to find the current browser
	 * @param string $userAgent Optinal useragent
	 */
	public function parseBrowser($userAgent = null) {
		if($userAgent == null) {
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
		}

		$browsers = $this->database->getBrowsers();
		$countBrowsers = count($browsers);

		for($i = 0; $i < $countBrowsers; $i++) {
			if($browsers[$i]->isUserAgentBrowser($userAgent)) {
				$this->userAgent = $browsers[$i];

				if($browsers[$i]->getTheme() != '') {
					$this->theme = $browsers[$i]->getTheme();
					$i = $countBrowsers;
				}
			}
		}
	}//parseBrowser



	/**
	 * Parse the browsers to find the current browser and return this
	 * @param string $userAgent Optinal useragent
	 * @return BrowserUA Browser if findit, null its don't
	 */
	public function checkBrowser($userAgent = null) {
		if($userAgent == null) {
			return null;
		}

		$browsers = $this->database->getBrowsers();
		$countBrowsers = count($browsers);
		$browser = null;

		for($i = 0; $i < $countBrowsers; $i++) {
			if($browsers[$i]->isUserAgentBrowser($userAgent)) {
				$browser = $browsers[$i];
			}
		}

		return $browser;
	}//checkBrowser



	/**
	 * Get the param by POST or GET methods
	 * @param string $parameterName Param name
	 * @param bool $isNull Indicate if null or empty string if not exists the param
	 * @return string Processed param
	 */
	private function getParameter($parameterName, $isNull = false) {
		if(isset($_REQUEST[$parameterName])) {
			return $_REQUEST[$parameterName];
		} else {
			if($isNull === true) {
				return null;
			} else {
				return '';
			}
		}
	}//getParameter
}//UserAgentThemeSwitcher


$wpUserAgentThemeSwitcher = new UserAgentThemeSwitcher();
$wpUserAgentThemeSwitcher->initialize();
unset($wpUserAgentThemeSwitcher);
?>