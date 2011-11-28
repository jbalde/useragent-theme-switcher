<?php
/**
 * UserAgent Theme Switcher Data class to access to database
 * @author Juan Benavides Romero <juan.benavides.romero@gmail.com>
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
		$this->browsers = array();

		$this->addBrowser(new BrowserUA('googlebot', 'GoogleBot', null, '^Mozilla\/5\.0 \(compatible; Googlebot\/.\..; \+http:\/\/www\.google\.com\/bot\.html\)$', 'google'));
		$this->addBrowser(new BrowserUA('ie2', 'Internet Explorer 2', null, '^Mozilla\/1\.22 \(compatible; MSIE 2\.0;.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('ie5', 'Internet Explorer 5', null, '^Mozilla\/4\.0 \(compatible; MSIE 5\.0.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('ie55', 'Internet Explorer 5.5', null, '^Mozilla\/4\.0 \(compatible; MSIE 5\.5;.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('ie6', 'Internet Explorer 6', null, '^Mozilla\/4\.0 \(compatible; MSIE 6\.0;.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('ie7', 'Internet Explorer 7', null, '^Mozilla\/4\.0 \(compatible; MSIE 7\.0; Windows NT.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('ie8', 'Internet Explorer 8', null, '^Mozilla\/4\.0 \(compatible; MSIE 8\.0;.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('ie9', 'Internet Explorer 9', null, '^Mozilla\/5\.0 \(compatible; MSIE 9\.0;.*\).*$', 'ie'));
		$this->addBrowser(new BrowserUA('chrome', 'Google Chrome', null, '^Mozilla\/5.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko\).* Chrome\/.* Safari\/.*$', 'webkit'));
		$this->addBrowser(new BrowserUA('safaridesktop', 'Safari', null, '^Mozilla\/5\.0 \(.*; .*\) AppleWebKit\/.* \(KHTML, like Gecko\) Version\/[\d\.]+ Safari\/.*$', 'webkit'));
		$this->addBrowser(new BrowserUA('firefox', 'Firefox', null, '^Mozilla\/5\.0 \(.*\) Gecko\/.* Firefox\/.*$', 'gecko'));
		$this->addBrowser(new BrowserUA('operadesktop', 'Opera', null, '^Opera\/[\d\.]+( ){0,1}\(.*\).*$', 'opera'));
		$this->addBrowser(new BrowserUA('safarimobile', 'Safari Mobile', null, '^Mozilla\/5.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko\)( Version\/.*){0,1} Mobile[\/A-Z0-9]{0,}( Safari\/.*){0,1}$', 'webkit,mobile'));
		$this->addBrowser(new BrowserUA('operamini', 'Opera Mini', null, '^Opera\/.* \(.*Opera Mini\/.*\).*$', 'opera,mobile'));
		$this->addBrowser(new BrowserUA('camino', 'Camino', null, '^Mozilla\/5.0 \(.*\) Gecko\/[\d]+ Camino\/[\d\.]+ \(like Firefox\/[\d\.]+\)$', 'gecko'));
		
		$this->addBrowser(new BrowserUA('iceweasel', 'IceWeasel', null, '^Mozilla\/5.0 \(.*\) Gecko\/[\d]+ Iceweasel\/[\d\.]+ \(Debian-.*\).*$', 'gecko'));
		$this->addBrowser(new BrowserUA('rockmelt', 'RockMelt', null, '^Mozilla\/5.0 \(Macintosh; .*\) AppleWebKit\/534\.24 \(KHTML, like Gecko\) RockMelt\/.* Chrome\/.* Safari\/.*$', 'webkit'));
		
		$this->addBrowser(new BrowserUA('ipad', 'iPad', null, '^Mozilla\/5.0 \(iPad; .*\).*$', 'tablet'));
		$this->addBrowser(new BrowserUA('kindle', 'Amazon Kindle', null, '^Mozilla\/5\.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko(.*) Version\/[\d\.]+ Kindle\/.*$', 'tablet'));
		$this->addBrowser(new BrowserUA('ie7mobile', 'Internet Explorer Mobile 7', null, '^Mozilla\/4\.0 \(compatible; MSIE 7\.0; Windows Phone OS.*\).*$', 'ie,mobile'));
		$this->addBrowser(new BrowserUA('firefoxmobile', 'Firefox Mobile', null, '^Mozilla\/5\.0 \(Android.*\) Gecko\/.* Firefox\/.*$', 'gecko,mobile'));
		
		$this->addBrowser(new BrowserUA('googlebotmobile', 'GoogleBot Mobile', null, '\(compatible; Googlebot-Mobile\/.\..; \+http:\/\/www\.google.com\/bot.html\)$', 'google,mobile'));
		
		$this->addBrowser(new BrowserUA('wordpressandroid', 'Wordpress Android', null, '^wp-android\/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('wordpressiphone', 'Wordpress iPhone', null, '^wp-iphone\/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('wordpressipad', 'Wordpress iPad', null, '^WordPress .* \(iPad; iPhone OS .*\)$', 'spider'));
		$this->addBrowser(new BrowserUA('wordpressweb', 'Wordpress Web', null, '^WordPress\/.*$', 'spider'));
		
		$this->addBrowser(new BrowserUA('java', 'Java', null, '^Java\/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('bitacoras', 'Bitacoras', null, '^Bitacoras.com\/2\.0 \(http:\/\/bitacoras\.com\)$', 'spider'));
		$this->addBrowser(new BrowserUA('flash', 'Flash', null, '^Shockwave Flash$', 'spider'));
		$this->addBrowser(new BrowserUA('zendcrawler', 'Zend Crawler', null, '^Zend_Http_Client$', 'spider'));
		$this->addBrowser(new BrowserUA('wget', 'wget', null, '^Wget.*$', 'spider'));
		$this->addBrowser(new BrowserUA('powermarks', 'Powermarks', null, '^Mozilla\/4\.0 \(compatible; Powermarks/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('applepubsub', 'Apple-PubSub', null, '^Apple\-PubSub\/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('alexa', 'Alexa', null, '^ia_archiver \(\+http:\/\/www\.alexa\.com\/site\/help\/webmasters; crawler@alexa\.com\)$', 'spider'));
		$this->addBrowser(new BrowserUA('libperl', 'libperl', null, '^libwww-perl\/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('bitly', 'bit.ly', null, '^bitlybot$', 'spider'));
		$this->addBrowser(new BrowserUA('twitter', 'Twitter Bot', null, '^Twitterbot\/.*$', 'spider'));
		$this->addBrowser(new BrowserUA('slurp', 'Yahoo Slurp', null, '^Mozilla\/5\.0 \(compatible; Yahoo\! Slurp; http:\/\/help\.yahoo\.com\/help\/us\/ysearch\/slurp\)$', 'spider'));
		$this->addBrowser(new BrowserUA('yahoocachesystem', 'Yahoo Cache System', null, '^YahooCacheSystem$', 'spider'));
		$this->addBrowser(new BrowserUA('blackberry', 'Blackberry Browser', null, '^BlackBerry.* Profile\/MIDP-.* Configuration\/CLDC-.* VendorID\/.*$', 'mobile'));
		$this->addBrowser(new BrowserUA('msnbot', 'MSNBot', null, '^msnbot-UDiscovery\/2\.0b \( http:\/\/search\.msn\.com\/msnbot\.htm\)$', 'crawler'));
		
		
		$this->addBrowser(new BrowserUA('facebook', 'Facebook', null, '^facebookexternalhit\/1\.1 \( http:\/\/www\.facebook.com\/externalhit_uatext\.php\)$', 'crawler'));
		$this->addBrowser(new BrowserUA('moreoverbot', 'Moreoverbot', null, '^Moreoverbot\/5\.1 \( http:\/\/w\.moreover\.com; webmaster@moreover\.com\) Mozilla\/5\.0$', 'crawler'));
		
		
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