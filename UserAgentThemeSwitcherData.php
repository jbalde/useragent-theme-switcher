<?php
/**
 * UserAgent Theme Switcher Data class to access to database
 * @author Juan Benavides Romero <juan@altairstudios.es>
 */
class UserAgentThemeSwitcherData {
	/**
	 * Database connection
	 * @var wpdb
	 */
	private $connection;


	/**
	 * Database table prefix
	 * @var string
	 */
	private $tableprefix;


	/**
	 * List of browsers
	 * @var array<BrowserUA>
	 */
	private $browsers;


	/**
	 * Database option key for plugin version
	 */
	const VERSION_KEY = 'uats_version';


	/**
	 * Database option key for debug mode
	 */
	const DEBUG_KEY = 'uats_debug';


	/**
	 * Database table for useragents
	 */
	const USERAGENTS_TABLE = 'uats_useragents';


	/**
	 * Database table for browsers
	 */
	const BROWSERS_TABLE = 'uats_browsers';


	/**
	 * Default constructor
	 * @param wpdb $connection Database connection
	 * @param string $tableprefix Database table prefix
	 */
	public function __construct(wpdb $connection = null, $tableprefix = '') {
		$this->connection = $connection;
		$this->tablePrefix = $tableprefix;

		$this->generateBrowsers();
	}//__construct


	/**
	 * Check database and create tables and contet if not exist or update this
	 * if have installed a lower version
	 * @param int $version Current plugin version
	 */
	public function updateDatabase($version) {
		$installedVersion = get_option(UserAgentThemeSwitcherData::VERSION_KEY, 0);

		if($installedVersion == 0) {
			$this->createDatabase($version);
		}

		if($installedVersion != $version) {
			if($version != 0) {
				add_option(UserAgentThemeSwitcherData::VERSION_KEY, $version);
			}
		}
	}//updateDatabase


	/**
	 * Create the database tables
	 * @param int $version Current plugin version
	 */
	private function createDatabase($version = 0) {
		$sql = 'CREATE TABLE IF NOT EXISTS '.$this->tablePrefix.UserAgentThemeSwitcherData::USERAGENTS_TABLE.' (';
		$sql .= '`id` INT NOT NULL AUTO_INCREMENT,';
		$sql .= '`useragent` VARCHAR(255) NOT NULL,';
		$sql .= 'PRIMARY KEY (`id`)';
		$sql .= ') ENGINE=MYISAM;';
		$this->connection->get_results($sql);

		$sql = 'CREATE TABLE IF NOT EXISTS '.$this->tablePrefix.UserAgentThemeSwitcherData::BROWSERS_TABLE.' (';
		$sql .= '`code` varchar(20) NOT NULL,';
		$sql .= '`theme` varchar(50) DEFAULT NULL,';
		$sql .= 'PRIMARY KEY (`code`)';
		$sql .= ') ENGINE=MyISAM;';
		$this->connection->get_results($sql);

		add_option(UserAgentThemeSwitcherData::DEBUG_KEY, 'false');
		add_option(UserAgentThemeSwitcherData::VERSION_KEY, $version);
	}//createDatabase


	/**
	 * Return all supported browsers
	 * @return array<BrowserUA> List of browsers
	 */
	public function getBrowsers() {
		return $this->browsers;
	}//getBrowsers


	/**
	 * Return all browsers without theme
	 * @return array<BrowserUA> List of browsers without theme
	 */
	public function getBrowsersWithoutTheme() {
		$countBrowsers = count($this->browsers);
		$browsers = array();

		for($i = 0; $i < $countBrowsers; $i++) {
			if(!$this->browsers[$i]->isThemeByCode() && !$this->browsers[$i]->hasTag('spider')) {
				$browsers[] = $this->browsers[$i];
			}
		}

		return $browsers;
	}//getBrowsersWithoutTheme


	/**
	 * Return all browsers with theme
	 * @return array<BrowserUA> List of browsers with theme
	 */
	public function getBrowsersWithTheme() {
		$countBrowsers = count($this->browsers);
		$browsers = array();

		for($i = 0; $i < $countBrowsers; $i++) {
			if($this->browsers[$i]->hasTheme()) {
				$browsers[] = $this->browsers[$i];
			}
		}

		return $browsers;
	}//getBrowsersWithTheme


	/**
	 * Return a list of tags
	 * @return array List of tags
	 */
	public function getTags() {
		$tags = array();
		$countBrowsers = count($this->browsers);

		for($i = 0; $i < $countBrowsers; $i++) {
			for($j = 0; $j < count($this->browsers[$i]->getTags()); $j++) {
				if(!in_array($this->browsers[$i]->getTag($j), $tags)) {
					$tags[] = $this->browsers[$i]->getTag($j);
				}
			}
		}

		return $tags;
	}//getTags


	/**
	 * Return a list of tags for show in web (without spider)
	 * @return array List of tags
	 */
	public function getWebTags() {
		$tags = $this->getTags();
		$webTags = array();

		for($i = 0; $i < count($tags); $i++) {
			if($tags[$i] != 'spider') {
				$webTags[] = $tags[$i];
			}
		}

		return $webTags;
	}//getWebTags


	/**
	 * Return all configurated templates
	 * @return array Configurated templates
	 */
	public function getConfiguratedTemplates() {
		$sql = 'SELECT * FROM '.$this->tablePrefix.UserAgentThemeSwitcherData::BROWSERS_TABLE;
		$results = $this->connection->get_results($sql);

		return $results;
	}//getConfiguratedTemplates


	/**
	 * Add new browser to the browser list
	 * @param BrowserUA $browser Browser to add at the list
	 */
	public function addBrowser(BrowserUA $browser) {
		$this->browsers[] = $browser;
	}//addBrowser


	/**
	 * Add template to browser
	 * @param BrowserUA $browser Browser to add the template
	 * @param string $theme theme code
	 */
	public function addRule($code, $theme) {
		$sql = 'INSERT INTO '.$this->tablePrefix.UserAgentThemeSwitcherData::BROWSERS_TABLE.' (code, theme) VALUES("'.$code.'", "'.$theme.'")';
		$this->connection->get_results($sql);
		$this->generateBrowsers();
	}//addTemplateToBrowser


	/**
	 * Delete all debug unsoported user agents
	 */
	public function truncateDebugUserAgents() {
		$sql = 'TRUNCATE TABLE '.$this->tablePrefix.UserAgentThemeSwitcherData::USERAGENTS_TABLE;
		$this->connection->get_results($sql);
	}//truncateDebugUserAgents


	/**
	 * Delete a rule and update the browsers
	 * @param string $rule Rule to delete
	 */
	public function deleteRule($rule) {
		$sql = 'DELETE FROM '.$this->tablePrefix.UserAgentThemeSwitcherData::BROWSERS_TABLE.' WHERE code = "'.$rule.'"';
		$this->connection->get_results($sql);
		$this->generateBrowsers();
	}//deleteRule


	/**
	 * Add a new unsoported useragent if the debugmode are active
	 * @param string $useragent Useragent to add
	 */
	public function addDebugUserAgent($useragent) {
		$sql = 'SELECT id FROM '.$this->tablePrefix.UserAgentThemeSwitcherData::USERAGENTS_TABLE.' where useragent = "'.$useragent.'"';
		$exists = $this->connection->get_results($sql);

		if($exists == null) {
			$sql = 'INSERT INTO '.$this->tablePrefix.UserAgentThemeSwitcherData::USERAGENTS_TABLE.' (useragent) VALUES ("'.$useragent.'")';
			$this->connection->get_results($sql);
		}
	}//addDebugUserAgent


	/**
	 * Return a list of unsoported useragents
	 * @return array Unsporoted useragents
	 */
	public function getDebugUserAgents() {
		$sql = 'SELECT * FROM '.$this->tablePrefix.UserAgentThemeSwitcherData::USERAGENTS_TABLE;
		$results = $this->connection->get_results($sql);

		return $results;
	}//getDebugUserAgents


	/**
	 * Delete a unsoported useragent
	 * @param int $id Id of the useragent
	 */
	public function deleteUserAgent($id) {
		$sql = 'DELETE FROM '.$this->tablePrefix.UserAgentThemeSwitcherData::USERAGENTS_TABLE.' WHERE id = "'.$id.'"';
		$this->connection->get_results($sql);
	}//deleteUserAgent


	/**
	 * Generate all supported browsers
	 */
	private function generateBrowsers() {
		$this->browsers = BrowserList::getBrowsers();

		$configuratedTemplates = $this->getConfiguratedTemplates();
		$countConfiguredBrowsers = count($configuratedTemplates);
		$countBrowsers = count($this->browsers);

		for($i = 0; $i < $countConfiguredBrowsers; $i++) {
			for($j = 0; $j < $countBrowsers; $j++) {
				$this->browsers[$j]->setThemeByCodeTag($configuratedTemplates[$i]->code, $configuratedTemplates[$i]->theme);
			}
		}
	}//generateBrowsers
}//UserAgentThemeSwitcherData
?>