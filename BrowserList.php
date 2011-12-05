<?php
class BrowserList {
	public static function getBrowsers() {
		$browsers = array();

		$browsers[] = new BrowserUA('googlebot', 'GoogleBot', null, '^Mozilla\/5\.0 \(compatible; Googlebot\/.\..; \+http:\/\/www\.google\.com\/bot\.html\)$', 'google');
		$browsers[] = new BrowserUA('ie2', 'Internet Explorer 2', null, '^Mozilla\/1\.22 \(compatible; MSIE 2\.0;.*\).*$', 'ie');
		$browsers[] = new BrowserUA('ie5', 'Internet Explorer 5', null, '^Mozilla\/4\.0 \(compatible; MSIE 5\.0.*\).*$', 'ie');
		$browsers[] = new BrowserUA('ie55', 'Internet Explorer 5.5', null, '^Mozilla\/4\.0 \(compatible; MSIE 5\.5;.*\).*$', 'ie');
		$browsers[] = new BrowserUA('ie6', 'Internet Explorer 6', null, '^Mozilla\/4\.0 \(compatible; MSIE 6\.0;.*\).*$', 'ie');
		$browsers[] = new BrowserUA('ie7', 'Internet Explorer 7', null, '^Mozilla\/4\.0 \(compatible; MSIE 7\.0; Windows NT.*\).*$', 'ie');
		$browsers[] = new BrowserUA('ie8', 'Internet Explorer 8', null, '^Mozilla\/4\.0 \(compatible; MSIE 8\.0;.*\).*$', 'ie');
		$browsers[] = new BrowserUA('ie9', 'Internet Explorer 9', null, '^Mozilla\/5\.0 \(compatible; MSIE 9\.0;.*\).*$', 'ie');
		$browsers[] = new BrowserUA('chrome', 'Google Chrome', null, '^Mozilla\/5.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko\).* Chrome\/.* Safari\/.*$', 'webkit');
		$browsers[] = new BrowserUA('safaridesktop', 'Safari', null, '^Mozilla\/5\.0 \(.*; .*\) AppleWebKit\/.* \(KHTML, like Gecko\) Version\/[\d\.]+ Safari\/.*$', 'webkit');
		$browsers[] = new BrowserUA('firefox', 'Firefox', null, '^Mozilla\/5\.0 \(.*\) Gecko\/.* Firefox\/.*$', 'gecko');
		$browsers[] = new BrowserUA('operadesktop', 'Opera', null, '^Opera\/[\d\.]+( ){0,1}\(.*\).*$', 'opera');
		$browsers[] = new BrowserUA('safarimobile', 'Safari Mobile', null, '^Mozilla\/5.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko\)( Version\/.*){0,1} Mobile[\/A-Z0-9]{0,}( Safari\/.*){0,1}$', 'webkit,mobile');
		$browsers[] = new BrowserUA('operamini', 'Opera Mini', null, '^Opera\/.* \(.*Opera Mini\/.*\).*$', 'opera,mobile');
		$browsers[] = new BrowserUA('camino', 'Camino', null, '^Mozilla\/5.0 \(.*\) Gecko\/[\d]+ Camino\/[\d\.]+ \(like Firefox\/[\d\.]+\)$', 'gecko');

		$browsers[] = new BrowserUA('iceweasel', 'IceWeasel', null, '^Mozilla\/5.0 \(.*\) Gecko\/[\d]+ Iceweasel\/[\d\.]+ \(Debian-.*\).*$', 'gecko');
		$browsers[] = new BrowserUA('rockmelt', 'RockMelt', null, '^Mozilla\/5.0 \(Macintosh; .*\) AppleWebKit\/534\.24 \(KHTML, like Gecko\) RockMelt\/.* Chrome\/.* Safari\/.*$', 'webkit');

		$browsers[] = new BrowserUA('ipad', 'iPad', null, '^Mozilla\/5.0 \(iPad; .*\).*$', 'tablet');
		$browsers[] = new BrowserUA('kindle', 'Amazon Kindle', null, '^Mozilla\/5\.0 \(.*\) AppleWebKit\/.* \(KHTML, like Gecko(.*) Version\/[\d\.]+ Kindle\/.*$', 'tablet');
		$browsers[] = new BrowserUA('ie7mobile', 'Internet Explorer Mobile 7', null, '^Mozilla\/4\.0 \(compatible; MSIE 7\.0; Windows Phone OS.*\).*$', 'ie,mobile');
		$browsers[] = new BrowserUA('firefoxmobile', 'Firefox Mobile', null, '^Mozilla\/5\.0 \(Android.*\) Gecko\/.* Firefox\/.*$', 'gecko,mobile');

		$browsers[] = new BrowserUA('googlebotmobile', 'GoogleBot Mobile', null, '\(compatible; Googlebot-Mobile\/.\..; \+http:\/\/www\.google.com\/bot.html\)$', 'google,mobile');

		$browsers[] = new BrowserUA('wordpressandroid', 'Wordpress Android', null, '^wp-android\/.*$', 'spider');
		$browsers[] = new BrowserUA('wordpressiphone', 'Wordpress iPhone', null, '^wp-iphone\/.*$', 'spider');
		$browsers[] = new BrowserUA('wordpressipad', 'Wordpress iPad', null, '^WordPress .* \(iPad; iPhone OS .*\)$', 'spider');
		$browsers[] = new BrowserUA('wordpressweb', 'Wordpress Web', null, '^WordPress\/.*$', 'spider');

		$browsers[] = new BrowserUA('java', 'Java', null, '^Java\/.*$', 'spider');
		$browsers[] = new BrowserUA('bitacoras', 'Bitacoras', null, '^Bitacoras.com\/2\.0 \(http:\/\/bitacoras\.com\)$', 'spider');
		$browsers[] = new BrowserUA('flash', 'Flash', null, '^Shockwave Flash$', 'spider');
		$browsers[] = new BrowserUA('zendcrawler', 'Zend Crawler', null, '^Zend_Http_Client$', 'spider');
		$browsers[] = new BrowserUA('wget', 'wget', null, '^Wget.*$', 'spider');
		$browsers[] = new BrowserUA('powermarks', 'Powermarks', null, '^Mozilla\/4\.0 \(compatible; Powermarks/.*$', 'spider');
		$browsers[] = new BrowserUA('applepubsub', 'Apple-PubSub', null, '^Apple\-PubSub\/.*$', 'spider');
		$browsers[] = new BrowserUA('alexa', 'Alexa', null, '^ia_archiver \(\+http:\/\/www\.alexa\.com\/site\/help\/webmasters; crawler@alexa\.com\)$', 'spider');
		$browsers[] = new BrowserUA('libperl', 'libperl', null, '^libwww-perl\/.*$', 'spider');
		$browsers[] = new BrowserUA('bitly', 'bit.ly', null, '^bitlybot$', 'spider');
		$browsers[] = new BrowserUA('twitter', 'Twitter Bot', null, '^Twitterbot\/.*$', 'spider');
		$browsers[] = new BrowserUA('slurp', 'Yahoo Slurp', null, '^Mozilla\/5\.0 \(compatible; Yahoo\! Slurp; http:\/\/help\.yahoo\.com\/help\/us\/ysearch\/slurp\)$', 'spider');
		$browsers[] = new BrowserUA('yahoocachesystem', 'Yahoo Cache System', null, '^YahooCacheSystem$', 'spider');
		$browsers[] = new BrowserUA('blackberry', 'Blackberry Browser', null, '^BlackBerry.* Profile\/MIDP-.* Configuration\/CLDC-.* VendorID\/.*$', 'mobile');
		$browsers[] = new BrowserUA('msnbot', 'MSNBot', null, '^msnbot-UDiscovery\/2\.0b \( http:\/\/search\.msn\.com\/msnbot\.htm\)$', 'crawler');


		$browsers[] = new BrowserUA('facebook', 'Facebook', null, '^facebookexternalhit\/1\.1 \( http:\/\/www\.facebook.com\/externalhit_uatext\.php\)$', 'crawler');
		$browsers[] = new BrowserUA('moreoverbot', 'Moreoverbot', null, '^Moreoverbot\/5\.1 \( http:\/\/w\.moreover\.com; webmaster@moreover\.com\) Mozilla\/5\.0$', 'crawler');
	}
}
?>