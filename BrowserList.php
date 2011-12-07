<?php
class BrowserList {
	public static function getBrowsers() {
		$browsers = array();
		$browsers[] = new BrowserUA('chrome', 'Google Chrome', array('^Mozilla\/5.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko\).* Chrome\/.* Safari\/.*$'), array('webkit'));
		$browsers[] = new BrowserUA('rockmelt', 'RockMelt', array('^Mozilla\/5.0 \(Macintosh; .*\) AppleWebKit\/534\.24 \(KHTML, like Gecko\) RockMelt\/.* Chrome\/.* Safari\/.*$'), array('webkit'));
		$browsers[] = new BrowserUA('safaridesktop', 'Safari', array('^Mozilla\/5\.0 \(.*; .*\) AppleWebKit\/.* \(KHTML, like Gecko\) Version\/[\d\.]+ Safari\/.*$'), array('webkit'));
		$browsers[] = new BrowserUA('firefox', 'Firefox', array('^Mozilla\/5\.0 \(.*\) Gecko\/.* Firefox\/.*$'), array('gecko'));
		$browsers[] = new BrowserUA('safarimobile', 'Safari Mobile', array('^Mozilla\/5.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko\)( Version\/.*){0,1} Mobile[\/A-Z0-9]{0,}( Safari\/.*){0,1}$'), array('webkit','mobile'));
		$browsers[] = new BrowserUA('ie6', 'Internet Explorer 6', array('^Mozilla\/4\.0 \(compatible; MSIE 6\.0;.*\).*$'), array('ie'));
		$browsers[] = new BrowserUA('ie7', 'Internet Explorer 7', array('^Mozilla\/4\.0 \(compatible; MSIE 7\.0; Windows NT.*\).*$'), array('ie'));
		$browsers[] = new BrowserUA('ie8', 'Internet Explorer 8', array('Mozilla\/4\.0 \(compatible; MSIE 8\.0;.*\).*'), array('ie'));
		$browsers[] = new BrowserUA('ie9', 'Internet Explorer 9', array('^Mozilla\/5\.0 \(compatible; MSIE 9\.0;.*\).*$'), array('ie'));
		$browsers[] = new BrowserUA('ie5', 'Internet Explorer 5', array('^Mozilla\/4\.0 \(compatible; MSIE 5\.0.*\).*$'), array('ie'));
		$browsers[] = new BrowserUA('ie55', 'Internet Explorer 5.5', array('^Mozilla\/4\.0 \(compatible; MSIE 5\.5;.*\).*$'), array('ie'));
		$browsers[] = new BrowserUA('ie2', 'Internet Explorer 2', array('^Mozilla\/1\.22 \(compatible; MSIE 2\.0;.*\).*$'), array('ie'));
		$browsers[] = new BrowserUA('operamini', 'Opera Mini', array('^Opera\/.* \(.*Opera Mini\/.*\).*$'), array('opera','mobile'));
		$browsers[] = new BrowserUA('camino', 'Camino', array('^Mozilla\/5.0 \(.*\) Gecko\/[\d]+ Camino\/[\d\.]+ \(like Firefox\/[\d\.]+\)$'), array('gecko'));
		$browsers[] = new BrowserUA('operadesktop', 'Opera', array('^Opera\/[\d\.]+( ){0,1}\(.*\).*$'), array('opera'));
		$browsers[] = new BrowserUA('iceweasel', 'IceWeasel', array('^Mozilla\/5.0 \(.*\) Gecko\/[\d]+ Iceweasel\/[\d\.]+ \(Debian-.*\).*$'), array('gecko'));
		$browsers[] = new BrowserUA('ipad', 'iPad', array('^Mozilla\/5.0 \(iPad; .*\).*$'), array('tablet'));
		$browsers[] = new BrowserUA('kindle', 'Amazon Kindle', array('^Mozilla\/5\.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko(.*) Version\/[\d\.]+ Kindle\/.*$'), array('tablet'));
		$browsers[] = new BrowserUA('wordpressweb', 'Wordpress Web', array('^WordPress\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('wordpressandroid', 'Wordpress Android', array('^wp-android\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('googlebot', 'GoogleBot', array('^Mozilla\/5\.0 \(compatible; Googlebot\/.\..; \+http:\/\/www\.google\.com\/bot\.html\)$'), array('google'));
		$browsers[] = new BrowserUA('googlebotmobile', 'GoogleBot Mobile', array('\(compatible; Googlebot-Mobile\/.\..; \+http:\/\/www\.google.com\/bot.html\)$'), array('google','mobile'));
		$browsers[] = new BrowserUA('ie7mobile', 'Internet Explorer Mobile 7', array('^Mozilla\/4\.0 \(compatible; MSIE 7\.0; Windows Phone OS.*\).*$'), array('ie','mobile'));
		$browsers[] = new BrowserUA('wordpressiphone', 'Wordpress iPhone', array('^wp-iphone\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('wordpressipad', 'Wordpress iPad', array('^WordPress .* \(iPad; iPhone OS .*\)$'), array('spider'));
		$browsers[] = new BrowserUA('java', 'Java', array('^Java\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('bitacoras', 'Bitacoras', array('^Bitacoras.com\/2\.0 \(http:\/\/bitacoras\.com\)$'), array('spider'));
		$browsers[] = new BrowserUA('flash', 'Flash', array('^Shockwave Flash$'), array('spider'));
		$browsers[] = new BrowserUA('zendcrawler', 'Zend Crawler', array('^Zend_Http_Client$'), array('spider'));
		$browsers[] = new BrowserUA('wget', 'wget', array('^Wget.*$'), array('spider'));
		$browsers[] = new BrowserUA('powermarks', 'Powermarks', array('^Mozilla\/4\.0 \(compatible; Powermarks/.*$'), array('spider'));
		$browsers[] = new BrowserUA('applepubsub', 'Apple-PubSub', array('^Apple\-PubSub\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('alexa', 'Alexa', array('^ia_archiver \(\+http:\/\/www\.alexa\.com\/site\/help\/webmasters; crawler@alexa\.com\)$'), array('spider'));
		$browsers[] = new BrowserUA('libperl', 'libperl', array('^libwww-perl\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('bitly', 'bit.ly', array('^bitlybot$'), array('spider'));
		$browsers[] = new BrowserUA('twitter', 'Twitter Bot', array('^Twitterbot\/.*$'), array('spider'));
		$browsers[] = new BrowserUA('slurp', 'Yahoo Slurp', array('^Mozilla\/5\.0 \(compatible; Yahoo\! Slurp; http:\/\/help\.yahoo\.com\/help\/us\/ysearch\/slurp\)$'), array('spider'));
		$browsers[] = new BrowserUA('firefoxmobile', 'Firefox Mobile', array('^Mozilla\/5\.0 \(Android.*\) Gecko\/.* Firefox\/.*$'), array('gecko','mobile'));
		$browsers[] = new BrowserUA('yahoocachesystem', 'Yahoo Cache System', array('^YahooCacheSystem$'), array('spider'));
		$browsers[] = new BrowserUA('blackberry', 'Blackberry Browser', array('^BlackBerry.* Profile\/MIDP-.* Configuration\/CLDC-.* VendorID\/.*$'), array('mobile'));
		$browsers[] = new BrowserUA('msnbot', 'MSNBot', array('^msnbot-UDiscovery\/2\.0b \( http:\/\/search\.msn\.com\/msnbot\.htm\)$'), array('spider'));
		$browsers[] = new BrowserUA('facebook', 'Facebook', array('^facebookexternalhit\/1\.1 \( http:\/\/www\.facebook.com\/externalhit_uatext\.php\)$'), array('spider'));
		$browsers[] = new BrowserUA('moreoverbot', 'Moreoverbot', array('^Moreoverbot\/5\.1 \( http:\/\/w\.moreover\.com; webmaster@moreover\.com\) Mozilla\/5\.0$'), array('spider'));
		return $browsers;
	}
}
?>