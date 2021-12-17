<?php

/**
 * Browser
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Browser
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Browser;

class Browser
{

	/**
	 * @var string
	 */
	private $_agent = '';

	/**
	 * @var string
	 */
	private $_browser_name = '';

	/**
	 * @var string
	 */
	private $_version = '';

	/**
	 * @var string
	 */
	private $_platform = '';

	/**
	 * @var string
	 */
	private $_os = '';

	/**
	 * @var boolean
	 */
	private $_is_aol = false;

	/**
	 * @var boolean
	 */
	private $_is_mobile = false;

	/**
	 * @var boolean
	 */
	private $_is_tablet = false;

	/**
	 * @var boolean
	 */
	private $_is_robot = false;

	/**
	 * @var boolean
	 */
	private $_is_facebook = false;

	/**
	 * @var string
	 */
	private $_aol_version = '';

	const BROWSER_UNKNOWN = 'unknown';
	const VERSION_UNKNOWN = 'unknown';

	const BROWSER_OPERA = 'Opera'; // http://www.opera.com/
	const BROWSER_OPERA_MINI = 'Opera Mini'; // http://www.opera.com/mini/
	const BROWSER_WEBTV = 'WebTV'; // http://www.webtv.net/pc/
	const BROWSER_EDGE = 'Edge'; // https://www.microsoft.com/edge
	const BROWSER_IE = 'Internet Explorer'; // http://www.microsoft.com/ie/
	const BROWSER_POCKET_IE = 'Pocket Internet Explorer'; // http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
	const BROWSER_KONQUEROR = 'Konqueror'; // http://www.konqueror.org/
	const BROWSER_ICAB = 'iCab'; // http://www.icab.de/
	const BROWSER_OMNIWEB = 'OmniWeb'; // http://www.omnigroup.com/applications/omniweb/
	const BROWSER_FIREBIRD = 'Firebird'; // http://www.ibphoenix.com/
	const BROWSER_FIREFOX = 'Firefox'; // http://www.mozilla.com/en-US/firefox/firefox.html
	const BROWSER_ICEWEASEL = 'Iceweasel'; // http://www.geticeweasel.org/
	const BROWSER_SHIRETOKO = 'Shiretoko'; // http://wiki.mozilla.org/Projects/shiretoko
	const BROWSER_MOZILLA = 'Mozilla'; // http://www.mozilla.com/en-US/
	const BROWSER_AMAYA = 'Amaya'; // http://www.w3.org/Amaya/
	const BROWSER_LYNX = 'Lynx'; // http://en.wikipedia.org/wiki/Lynx
	const BROWSER_SAFARI = 'Safari'; // http://apple.com
	const BROWSER_IPHONE = 'iPhone'; // http://apple.com
	const BROWSER_IPOD = 'iPod'; // http://apple.com
	const BROWSER_IPAD = 'iPad'; // http://apple.com
	const BROWSER_CHROME = 'Chrome'; // http://www.google.com/chrome
	const BROWSER_ANDROID = 'Android'; // http://www.android.com/
	const BROWSER_GOOGLEBOT = 'GoogleBot'; // http://en.wikipedia.org/wiki/Googlebot

	const BROWSER_YANDEXBOT = 'YandexBot'; // http://yandex.com/bots
	const BROWSER_YANDEXIMAGERESIZER_BOT = 'YandexImageResizer'; // http://yandex.com/bots
	const BROWSER_YANDEXIMAGES_BOT = 'YandexImages'; // http://yandex.com/bots
	const BROWSER_YANDEXVIDEO_BOT = 'YandexVideo'; // http://yandex.com/bots
	const BROWSER_YANDEXMEDIA_BOT = 'YandexMedia'; // http://yandex.com/bots
	const BROWSER_YANDEXBLOGS_BOT = 'YandexBlogs'; // http://yandex.com/bots
	const BROWSER_YANDEXFAVICONS_BOT = 'YandexFavicons'; // http://yandex.com/bots
	const BROWSER_YANDEXWEBMASTER_BOT = 'YandexWebmaster'; // http://yandex.com/bots
	const BROWSER_YANDEXDIRECT_BOT = 'YandexDirect'; // http://yandex.com/bots
	const BROWSER_YANDEXMETRIKA_BOT = 'YandexMetrika'; // http://yandex.com/bots
	const BROWSER_YANDEXNEWS_BOT = 'YandexNews'; // http://yandex.com/bots
	const BROWSER_YANDEXCATALOG_BOT = 'YandexCatalog'; // http://yandex.com/bots

	const BROWSER_SLURP = 'Yahoo! Slurp'; // http://en.wikipedia.org/wiki/Yahoo!_Slurp
	const BROWSER_W3CVALIDATOR = 'W3C Validator'; // http://validator.w3.org/
	const BROWSER_BLACKBERRY = 'BlackBerry'; // http://www.blackberry.com/
	const BROWSER_ICECAT = 'IceCat'; // http://en.wikipedia.org/wiki/GNU_IceCat
	const BROWSER_NOKIA_S60 = 'Nokia S60 OSS Browser'; // http://en.wikipedia.org/wiki/Web_Browser_for_S60
	const BROWSER_NOKIA = 'Nokia Browser'; // * all other WAP-based browsers on the Nokia Platform
	const BROWSER_MSN = 'MSN Browser'; // http://explorer.msn.com/
	const BROWSER_MSNBOT = 'MSN Bot'; // http://search.msn.com/msnbot.htm
	const BROWSER_BINGBOT = 'Bing Bot'; // http://en.wikipedia.org/wiki/Bingbot
	const BROWSER_VIVALDI = 'Vivalidi'; // https://vivaldi.com/
	const BROWSER_YANDEX = 'Yandex'; // https://browser.yandex.ua/

	const BROWSER_NETSCAPE_NAVIGATOR = 'Netscape Navigator'; // http://browser.netscape.com/ (DEPRECATED)
	const BROWSER_GALEON = 'Galeon'; // http://galeon.sourceforge.net/ (DEPRECATED)
	const BROWSER_NETPOSITIVE = 'NetPositive'; // http://en.wikipedia.org/wiki/NetPositive (DEPRECATED)
	const BROWSER_PHOENIX = 'Phoenix'; // http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox (DEPRECATED)
	const BROWSER_PLAYSTATION = "PlayStation";
	const BROWSER_SAMSUNG = "SamsungBrowser";
	const BROWSER_SILK = "Silk";
	const BROWSER_I_FRAME = "Iframely";
	const BROWSER_COCOA = "CocoaRestClient";

	const PLATFORM_UNKNOWN = 'unknown';
	const PLATFORM_WINDOWS = 'Windows';
	const PLATFORM_WINDOWS_CE = 'Windows CE';
	const PLATFORM_APPLE = 'Apple';
	const PLATFORM_LINUX = 'Linux';
	const PLATFORM_OS2 = 'OS/2';
	const PLATFORM_BEOS = 'BeOS';
	const PLATFORM_IPHONE = 'iPhone';
	const PLATFORM_IPOD = 'iPod';
	const PLATFORM_IPAD = 'iPad';
	const PLATFORM_BLACKBERRY = 'BlackBerry';
	const PLATFORM_NOKIA = 'Nokia';
	const PLATFORM_FREEBSD = 'FreeBSD';
	const PLATFORM_OPENBSD = 'OpenBSD';
	const PLATFORM_NETBSD = 'NetBSD';
	const PLATFORM_SUNOS = 'SunOS';
	const PLATFORM_OPENSOLARIS = 'OpenSolaris';
	const PLATFORM_ANDROID = 'Android';
	const PLATFORM_PLAYSTATION = "Sony PlayStation";
	const PLATFORM_ROKU = "Roku";
	const PLATFORM_APPLE_TV = "Apple TV";
	const PLATFORM_TERMINAL = "Terminal";
	const PLATFORM_FIRE_OS = "Fire OS";
	const PLATFORM_SMART_TV = "SMART-TV";
	const PLATFORM_CHROME_OS = "Chrome OS";
	const PLATFORM_JAVA_ANDROID = "Java/Android";
	const PLATFORM_POSTMAN = "Postman";
	const PLATFORM_I_FRAME = "Iframely";

	const OPERATING_SYSTEM_UNKNOWN = 'unknown';

	public function __construct($userAgent = "")
	{
		$this->reset();
		if ($userAgent != "") {
			$this->setUserAgent($userAgent);
		} else {
			$this->determine();
		}
	}

	public function reset(): void
	{
		$this->_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
		$this->_browser_name = self::BROWSER_UNKNOWN;
		$this->_version = self::VERSION_UNKNOWN;
		$this->_platform = self::PLATFORM_UNKNOWN;
		$this->_os = self::OPERATING_SYSTEM_UNKNOWN;
		$this->_is_aol = false;
		$this->_is_mobile = false;
		$this->_is_tablet = false;
		$this->_is_robot = false;
		$this->_is_facebook = false;
		$this->_aol_version = self::VERSION_UNKNOWN;
	}

	/**
	 * @param  string|null $browserName
	 * @return boolean
	 */
	function isBrowser(string $browserName = null): bool
	{
		return (0 == strcasecmp($this->_browser_name, trim($browserName)));
	}

	public function getBrowser(): string
	{
		return $this->_browser_name;
	}

	public function setBrowser(string $browser): void
	{
		$this->_browser_name = $browser;
	}

	public function getPlatform(): string
	{
		return $this->_platform;
	}

	/**
	 * @psalm-param 'Windows CE' $platform
	 */
	public function setPlatform(string $platform): void
	{
		$this->_platform = $platform;
	}

	public function getVersion(): string
	{
		return $this->_version;
	}

	/**
	 * @param null|string $version
	 */
	public function setVersion(?string $version): void
	{
		$this->_version = preg_replace('/[^0-9,.,a-z,A-Z-]/', '', $version);
	}

	public function getAolVersion(): string
	{
		return $this->_aol_version;
	}

	/**
	 * @param string $version
	 */
	public function setAolVersion(string $version): void
	{
		$this->_aol_version = preg_replace('/[^0-9,.,a-z,A-Z]/', '', $version);
	}

	public function getOs(): string
	{
		$os = [
			'/windows nt 10/i'      =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
		];

		$platform = null;
		foreach ($os as $regex => $value) {
			if (preg_match($regex, $this->_agent)) {
				$platform = $value;
			}
		}

		return $platform ? $platform : $this->_os;
	}

	public function isAol(): bool
	{
		return $this->_is_aol;
	}

	public function isMobile(): bool
	{
		return $this->_is_mobile;
	}

	public function isTablet(): bool
	{
		return $this->_is_tablet;
	}

	public function isRobot(): bool
	{
		return $this->_is_robot;
	}

	public function isFacebook(): bool
	{
		return $this->_is_facebook;
	}

	public function setAol(bool $isAol): void
	{
		$this->_is_aol = $isAol;
	}

	protected function setMobile(bool $value = true): void
	{
		$this->_is_mobile = $value;
	}

	/**
	 * @param true $value
	 */
	protected function setTablet(bool $value = true): void
	{
		$this->_is_tablet = $value;
	}

	/**
	 * @param true $value
	 */
	protected function setRobot(bool $value = true): void
	{
		$this->_is_robot = $value;
	}

	/**
	 * @param true $value
	 */
	protected function setFacebook(bool $value = true): void
	{
		$this->_is_facebook = $value;
	}

	public function getUserAgent(): string
	{
		return $this->_agent;
	}

	/**
	 * @param string $agent_string
	 */
	public function setUserAgent(string $agent_string): void
	{
		$this->reset();
		$this->_agent = $agent_string;
		$this->determine();
	}

	public function isChromeFrame(): bool
	{
		return (strpos($this->_agent, "chromeframe") !== false);
	}

	public function __toString()
	{
		return "<strong>Browser Name:</strong> {$this->getBrowser()}<br/>\n" .
		"<strong>Browser Version:</strong> {$this->getVersion()}<br/>\n" .
		"<strong>Browser User Agent String:</strong> {$this->getUserAgent()}<br/>\n" .
		"<strong>Platform:</strong> {$this->getPlatform()}<br/>";
	}

	protected function determine(): void
	{
		$this->checkPlatform();
		$this->checkBrowsers();
		$this->checkForAol();
	}

	protected function checkBrowsers(): bool
	{
		return (
			// well-known, well-used
			// Special Notes:
			// (1) Opera must be checked before FireFox due to the odd
			//     user agents used in some older versions of Opera
			// (2) WebTV is strapped onto Internet Explorer so we must
			//     check for WebTV before IE
			// (3) (deprecated) Galeon is based on Firefox and needs to be
			//     tested before Firefox is tested
			// (4) OmniWeb is based on Safari so OmniWeb check must occur
			//     before Safari
			// (5) Netscape 9+ is based on Firefox so Netscape checks
			//     before FireFox are necessary
			// (6) Vivalid is UA contains both Firefox and Chrome so Vivalid checks
			//     before Firefox and Chrome
			$this->checkBrowserWebTv() ||
			$this->checkBrowserEdge() ||
			$this->checkBrowserInternetExplorer() ||
			$this->checkBrowserOpera() ||
			$this->checkBrowserGaleon() ||
			$this->checkBrowserNetscapeNavigator9Plus() ||
			$this->checkBrowserVivaldi() ||
			$this->checkBrowserYandex() ||
			$this->checkBrowserFirefox() ||
			$this->checkBrowserChrome() ||
			$this->checkBrowserOmniWeb() ||

			// common mobile
			$this->checkBrowserAndroid() ||
			$this->checkBrowseriPad() ||
			$this->checkBrowseriPod() ||
			$this->checkBrowseriPhone() ||
			$this->checkBrowserBlackBerry() ||
			$this->checkBrowserNokia() ||

			// common bots
			$this->checkBrowserGoogleBot() ||
			$this->checkBrowserMSNBot() ||
			$this->checkBrowserBingBot() ||
			$this->checkBrowserSlurp() ||

			// Yandex bots
			$this->checkBrowserYandexBot() ||
			$this->checkBrowserYandexImageResizerBot() ||
			$this->checkBrowserYandexBlogsBot() ||
			$this->checkBrowserYandexCatalogBot() ||
			$this->checkBrowserYandexDirectBot() ||
			$this->checkBrowserYandexFaviconsBot() ||
			$this->checkBrowserYandexImagesBot() ||
			$this->checkBrowserYandexMediaBot() ||
			$this->checkBrowserYandexMetrikaBot() ||
			$this->checkBrowserYandexNewsBot() ||
			$this->checkBrowserYandexVideoBot() ||
			$this->checkBrowserYandexWebmasterBot() ||

			// check for facebook external hit when loading URL
			$this->checkFacebookExternalHit() ||

			// WebKit base check (post mobile and others)
			$this->checkBrowserSamsung() ||
			$this->checkBrowserSilk() ||
			$this->checkBrowserSafari() ||

			// everyone else
			$this->checkBrowserNetPositive() ||
			$this->checkBrowserFirebird() ||
			$this->checkBrowserKonqueror() ||
			$this->checkBrowserIcab() ||
			$this->checkBrowserPhoenix() ||
			$this->checkBrowserAmaya() ||
			$this->checkBrowserLynx() ||
			$this->checkBrowserShiretoko() ||
			$this->checkBrowserIceCat() ||
			$this->checkBrowserIceweasel() ||
			$this->checkBrowserW3CValidator() ||
			$this->checkBrowserPlayStation() ||
			$this->checkBrowserIframely() ||
			$this->checkBrowserCocoa() ||
			$this->checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */
		);
	}

	protected function checkBrowserBlackBerry(): bool
	{
		if (stripos($this->_agent, 'blackberry') !== false) {
			$aresult = explode("/", (string) stristr($this->_agent, "BlackBerry"));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = self::BROWSER_BLACKBERRY;
				$this->setMobile(true);
				return true;
			}
		}
		return false;
	}

	protected function checkForAol(): bool
	{
		$this->setAol(false);
		$this->setAolVersion(self::VERSION_UNKNOWN);

		if (stripos($this->_agent, 'aol') !== false) {
			$aversion = explode(' ', (string) stristr($this->_agent, 'AOL'));
			if (isset($aversion[1])) {
				$this->setAol(true);
				$this->setAolVersion(preg_replace('/[^0-9\.a-z]/i', '', $aversion[1]));
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserGoogleBot(): bool
	{
		if (stripos($this->_agent, 'googlebot') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'googlebot'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_GOOGLEBOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexBot(): bool
	{
		if (stripos($this->_agent, 'YandexBot') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexBot'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXBOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexImageResizerBot(): bool
	{
		if (stripos($this->_agent, 'YandexImageResizer') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexImageResizer'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXIMAGERESIZER_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexCatalogBot(): bool
	{
		if (stripos($this->_agent, 'YandexCatalog') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexCatalog'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXCATALOG_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexNewsBot(): bool
	{
		if (stripos($this->_agent, 'YandexNews') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexNews'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXNEWS_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexMetrikaBot(): bool
	{
		if (stripos($this->_agent, 'YandexMetrika') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexMetrika'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXMETRIKA_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexDirectBot(): bool
	{
		if (stripos($this->_agent, 'YandexDirect') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexDirect'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXDIRECT_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexWebmasterBot(): bool
	{
		if (stripos($this->_agent, 'YandexWebmaster') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexWebmaster'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXWEBMASTER_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexFaviconsBot(): bool
	{
		if (stripos($this->_agent, 'YandexFavicons') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexFavicons'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXFAVICONS_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexBlogsBot(): bool
	{
		if (stripos($this->_agent, 'YandexBlogs') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexBlogs'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXBLOGS_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexMediaBot(): bool
	{
		if (stripos($this->_agent, 'YandexMedia') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexMedia'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXMEDIA_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexVideoBot(): bool
	{
		if (stripos($this->_agent, 'YandexVideo') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexVideo'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXVIDEO_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandexImagesBot(): bool
	{
		if (stripos($this->_agent, 'YandexImages') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YandexImages'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(';', '', $aversion[0]));
				$this->_browser_name = self::BROWSER_YANDEXIMAGES_BOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserMSNBot(): bool
	{
		if (stripos($this->_agent, "msnbot") !== false) {
			$aresult = explode("/", (string) stristr($this->_agent, "msnbot"));
			if (isset($aresult[1])) {
				$aversion = explode(" ", $aresult[1]);
				$this->setVersion(str_replace(";", "", $aversion[0]));
				$this->_browser_name = self::BROWSER_MSNBOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserBingBot(): bool
	{
		if (stripos($this->_agent, "bingbot") !== false) {
			$aresult = explode("/", (string) stristr($this->_agent, "bingbot"));
			if (isset($aresult[1])) {
				$aversion = explode(" ", $aresult[1]);
				$this->setVersion(str_replace(";", "", $aversion[0]));
				$this->_browser_name = self::BROWSER_BINGBOT;
				$this->setRobot(true);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserW3CValidator(): bool
	{
		if (stripos($this->_agent, 'W3C-checklink') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'W3C-checklink'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = self::BROWSER_W3CVALIDATOR;
				return true;
			}
		} else if (stripos($this->_agent, 'W3C_Validator') !== false) {
			// Some of the Validator versions do not delineate w/ a slash - add it back in
			$ua = str_replace("W3C_Validator ", "W3C_Validator/", $this->_agent);
			$aresult = explode('/', (string) stristr($ua, 'W3C_Validator'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = self::BROWSER_W3CVALIDATOR;
				return true;
			}
		} else if (stripos($this->_agent, 'W3C-mobileOK') !== false) {
			$this->_browser_name = self::BROWSER_W3CVALIDATOR;
			$this->setMobile(true);
			return true;
		}
		return false;
	}

	protected function checkBrowserSlurp(): bool
	{
		if (stripos($this->_agent, 'slurp') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Slurp'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->_browser_name = self::BROWSER_SLURP;
				$this->setRobot(true);
				$this->setMobile(false);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserEdge(): bool
	{
		if (stripos($this->_agent, 'Edge/') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Edge'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_EDGE);
				if (stripos($this->_agent, 'Windows Phone') !== false || stripos($this->_agent, 'Android') !== false) {
					$this->setMobile(true);
				}
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserInternetExplorer(): bool
	{
		//  Test for IE11
		if (stripos($this->_agent, 'Trident/7.0; rv:11.0') !== false) {
			$this->setBrowser(self::BROWSER_IE);
			$this->setVersion('11.0');
			return true;
		} // Test for v1 - v1.5 IE
		else if (stripos($this->_agent, 'microsoft internet explorer') !== false) {
			$this->setBrowser(self::BROWSER_IE);
			$this->setVersion('1.0');
			$aresult = (string) stristr($this->_agent, '/');
			if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
				$this->setVersion('1.5');
			}
			return true;
		} // Test for versions > 1.5
		else if (stripos($this->_agent, 'msie') !== false && stripos($this->_agent, 'opera') === false) {
			// See if the browser is the odd MSN Explorer
			if (stripos($this->_agent, 'msnb') !== false) {
				$aresult = explode(' ', (string) stristr(str_replace(';', '; ', $this->_agent), 'MSN'));
				if (isset($aresult[1])) {
					$this->setBrowser(self::BROWSER_MSN);
					$this->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
					return true;
				}
			}
			$aresult = explode(' ', (string) stristr(str_replace(';', '; ', $this->_agent), 'msie'));
			if (isset($aresult[1])) {
				$this->setBrowser(self::BROWSER_IE);
				$this->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
				if (preg_match('#trident/([0-9\.]+);#i', $this->_agent, $aresult)) {
					if ($aresult[1] == '3.1') {
						$this->setVersion('7.0');
					} else if ($aresult[1] == '4.0') {
						$this->setVersion('8.0');
					} else if ($aresult[1] == '5.0') {
						$this->setVersion('9.0');
					} else if ($aresult[1] == '6.0') {
						$this->setVersion('10.0');
					} else if ($aresult[1] == '7.0') {
						$this->setVersion('11.0');
					} else if ($aresult[1] == '8.0') {
						$this->setVersion('11.0');
					}
				}
				if (stripos($this->_agent, 'IEMobile') !== false) {
					$this->setBrowser(self::BROWSER_POCKET_IE);
					$this->setMobile(true);
				}
				return true;
			}
		} // Test for versions > IE 10
		else if (stripos($this->_agent, 'trident') !== false) {
			$this->setBrowser(self::BROWSER_IE);
			$result = explode('rv:', $this->_agent);
			if (isset($result[1])) {
				$this->setVersion(preg_replace('/[^0-9.]+/', '', $result[1]));
				$this->_agent = str_replace(array("Mozilla", "Gecko"), "MSIE", $this->_agent);
			}
		} // Test for Pocket IE
		else if (stripos($this->_agent, 'mspie') !== false || stripos($this->_agent, 'pocket') !== false) {
			$aresult = explode(' ', (string) stristr($this->_agent, 'mspie'));
			if (isset($aresult[1])) {
				$this->setPlatform(self::PLATFORM_WINDOWS_CE);
				$this->setBrowser(self::BROWSER_POCKET_IE);
				$this->setMobile(true);

				if (stripos($this->_agent, 'mspie') !== false) {
					$this->setVersion($aresult[1]);
				} else {
					$aversion = explode('/', $this->_agent);
					if (isset($aversion[1])) {
						$this->setVersion($aversion[1]);
					}
				}
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserOpera(): bool
	{
		if (stripos($this->_agent, 'opera mini') !== false) {
			$resultant = (string) stristr($this->_agent, 'opera mini');
			if (preg_match('/\//', $resultant)) {
				$aresult = explode('/', $resultant);
				if (isset($aresult[1])) {
					$aversion = explode(' ', $aresult[1]);
					$this->setVersion($aversion[0]);
				}
			} else {
				$aversion = explode(' ', (string) stristr($resultant, 'opera mini'));
				if (isset($aversion[1])) {
					$this->setVersion($aversion[1]);
				}
			}
			$this->_browser_name = self::BROWSER_OPERA_MINI;
			$this->setMobile(true);
			return true;
		} else if (stripos($this->_agent, 'opera') !== false) {
			$resultant = (string) stristr($this->_agent, 'opera');
			if (preg_match('/Version\/(1*.*)$/', $resultant, $matches)) {
				$this->setVersion($matches[1]);
			} else if (preg_match('/\//', $resultant)) {
				$aresult = explode('/', str_replace("(", " ", $resultant));
				if (isset($aresult[1])) {
					$aversion = explode(' ', $aresult[1]);
					$this->setVersion($aversion[0]);
				}
			} else {
				$aversion = explode(' ', (string) stristr($resultant, 'opera'));
				$this->setVersion(isset($aversion[1]) ? $aversion[1] : "");
			}
			if (stripos($this->_agent, 'Opera Mobi') !== false) {
				$this->setMobile(true);
			}
			$this->_browser_name = self::BROWSER_OPERA;
			return true;
		} else if (stripos($this->_agent, 'OPR') !== false) {
			$resultant = (string) stristr($this->_agent, 'OPR');
			if (preg_match('/\//', $resultant)) {
				$aresult = explode('/', str_replace("(", " ", $resultant));
				if (isset($aresult[1])) {
					$aversion = explode(' ', $aresult[1]);
					$this->setVersion($aversion[0]);
				}
			}
			if (stripos($this->_agent, 'Mobile') !== false) {
				$this->setMobile(true);
			}
			$this->_browser_name = self::BROWSER_OPERA;
			return true;
		}
		return false;
	}

	protected function checkBrowserChrome(): bool
	{
		if (stripos($this->_agent, 'Chrome') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Chrome'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_CHROME);
				//Chrome on Android
				if (stripos($this->_agent, 'Android') !== false) {
					if (stripos($this->_agent, 'Mobile') !== false) {
						$this->setMobile(true);
					} else {
						$this->setTablet(true);
					}
				}
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserWebTv(): bool
	{
		if (stripos($this->_agent, 'webtv') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'webtv'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_WEBTV);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserNetPositive(): bool
	{
		if (stripos($this->_agent, 'NetPositive') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'NetPositive'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
				$this->setBrowser(self::BROWSER_NETPOSITIVE);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserGaleon(): bool
	{
		if (stripos($this->_agent, 'galeon') !== false) {
			$aresult = explode(' ', (string) stristr($this->_agent, 'galeon'));
			$aversion = explode('/', $aresult[0]);
			if (isset($aversion[1])) {
				$this->setVersion($aversion[1]);
				$this->setBrowser(self::BROWSER_GALEON);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserKonqueror(): bool
	{
		if (stripos($this->_agent, 'Konqueror') !== false) {
			$aresult = explode(' ', (string) stristr($this->_agent, 'Konqueror'));
			$aversion = explode('/', $aresult[0]);
			if (isset($aversion[1])) {
				$this->setVersion($aversion[1]);
				$this->setBrowser(self::BROWSER_KONQUEROR);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserIcab(): bool
	{
		if (stripos($this->_agent, 'icab') !== false) {
			$aversion = explode(' ', (string) stristr(str_replace('/', ' ', $this->_agent), 'icab'));
			if (isset($aversion[1])) {
				$this->setVersion($aversion[1]);
				$this->setBrowser(self::BROWSER_ICAB);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserOmniWeb(): bool
	{
		if (stripos($this->_agent, 'omniweb') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'omniweb'));
			$aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : "");
			$this->setVersion($aversion[0]);
			$this->setBrowser(self::BROWSER_OMNIWEB);
			return true;
		}
		return false;
	}

	protected function checkBrowserPhoenix(): bool
	{
		if (stripos($this->_agent, 'Phoenix') !== false) {
			$aversion = explode('/', (string) stristr($this->_agent, 'Phoenix'));
			if (isset($aversion[1])) {
				$this->setVersion($aversion[1]);
				$this->setBrowser(self::BROWSER_PHOENIX);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserFirebird(): bool
	{
		if (stripos($this->_agent, 'Firebird') !== false) {
			$aversion = explode('/', (string) stristr($this->_agent, 'Firebird'));
			if (isset($aversion[1])) {
				$this->setVersion($aversion[1]);
				$this->setBrowser(self::BROWSER_FIREBIRD);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserNetscapeNavigator9Plus(): bool
	{
		if (stripos($this->_agent, 'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i', $this->_agent, $matches)) {
			$this->setVersion($matches[1]);
			$this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
			return true;
		} else if (stripos($this->_agent, 'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i', $this->_agent, $matches)) {
			$this->setVersion($matches[1]);
			$this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
			return true;
		}
		return false;
	}

	protected function checkBrowserShiretoko(): bool
	{
		if (stripos($this->_agent, 'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i', $this->_agent, $matches)) {
			$this->setVersion($matches[1]);
			$this->setBrowser(self::BROWSER_SHIRETOKO);
			return true;
		}
		return false;
	}

	protected function checkBrowserIceCat(): bool
	{
		if (stripos($this->_agent, 'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i', $this->_agent, $matches)) {
			$this->setVersion($matches[1]);
			$this->setBrowser(self::BROWSER_ICECAT);
			return true;
		}
		return false;
	}

	protected function checkBrowserNokia(): bool
	{
		if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", $this->_agent, $matches)) {
			$this->setVersion($matches[2]);
			if (stripos($this->_agent, 'Series60') !== false || strpos($this->_agent, 'S60') !== false) {
				$this->setBrowser(self::BROWSER_NOKIA_S60);
			} else {
				$this->setBrowser(self::BROWSER_NOKIA);
			}
			$this->setMobile(true);
			return true;
		}
		return false;
	}

	protected function checkBrowserFirefox(): bool
	{
		if (stripos($this->_agent, 'safari') === false) {
			if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
				$this->setVersion($matches[1]);
				$this->setBrowser(self::BROWSER_FIREFOX);
										//Firefox on Android
				if (stripos($this->_agent, 'Android') !== false) {
					if (stripos($this->_agent, 'Mobile') !== false) {
						$this->setMobile(true);
					} else {
						$this->setTablet(true);
					}
				}
				return true;
			} else if (preg_match("/Firefox$/i", $this->_agent, $matches)) {
				$this->setVersion("");
				$this->setBrowser(self::BROWSER_FIREFOX);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserIceweasel(): bool
	{
		if (stripos($this->_agent, 'Iceweasel') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Iceweasel'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_ICEWEASEL);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserMozilla(): bool
	{
		if (stripos($this->_agent, 'mozilla') !== false && preg_match('/rv:[0-9].[0-9][a-b]?/i', $this->_agent) && stripos($this->_agent, 'netscape') === false) {
			$aversion = explode(' ', (string) stristr($this->_agent, 'rv:'));
			preg_match('/rv:[0-9].[0-9][a-b]?/i', $this->_agent, $aversion);
			$this->setVersion(str_replace('rv:', '', $aversion[0]));
			$this->setBrowser(self::BROWSER_MOZILLA);
			return true;
		} else if (stripos($this->_agent, 'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i', $this->_agent) && stripos($this->_agent, 'netscape') === false) {
			$aversion = explode('', (string) stristr($this->_agent, 'rv:'));
			if ($aversion != null) {
				$this->setVersion(str_replace('rv:', '', $aversion[0]));
			}
			$this->setBrowser(self::BROWSER_MOZILLA);
			return true;
		} else if (stripos($this->_agent, 'mozilla') !== false && preg_match('/mozilla\/([^ ]*)/i', $this->_agent, $matches) && stripos($this->_agent, 'netscape') === false) {
			$this->setVersion($matches[1]);
			$this->setBrowser(self::BROWSER_MOZILLA);
			return true;
		}
		return false;
	}

	protected function checkBrowserLynx(): bool
	{
		if (stripos($this->_agent, 'lynx') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Lynx'));
			$aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ""));
			$this->setVersion($aversion[0]);
			$this->setBrowser(self::BROWSER_LYNX);
			return true;
		}
		return false;
	}

	protected function checkBrowserAmaya(): bool
	{
		if (stripos($this->_agent, 'amaya') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Amaya'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_AMAYA);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserSafari(): bool
	{
		if (
			stripos($this->_agent, 'Safari') !== false && 
			stripos($this->_agent, 'iPhone') === false && 
			stripos($this->_agent, 'iPod') === false
		) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Version'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
			} else {
				$this->setVersion(self::VERSION_UNKNOWN);
			}
			$this->setBrowser(self::BROWSER_SAFARI);
			return true;
		}
		return false;
	}

	protected function checkBrowserSamsung(): bool
	{
		if (stripos($this->_agent, 'SamsungBrowser') !== false) {

			$aresult = explode('/', (string) stristr($this->_agent, 'SamsungBrowser'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
			} else {
				$this->setVersion(self::VERSION_UNKNOWN);
			}
			$this->setBrowser(self::BROWSER_SAMSUNG);
			return true;
		}
		return false;
	}

	protected function checkBrowserSilk(): bool
	{
		if (stripos($this->_agent, 'Silk') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Silk'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
			} else {
				$this->setVersion(self::VERSION_UNKNOWN);
			}
			$this->setBrowser(self::BROWSER_SILK);
			return true;
		}
		return false;
	}

	protected function checkBrowserIframely(): bool
	{
		if (stripos($this->_agent, 'Iframely') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Iframely'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
			} else {
				$this->setVersion(self::VERSION_UNKNOWN);
			}
			$this->setBrowser(self::BROWSER_I_FRAME);
			return true;
		}
		return false;
	}

	protected function checkBrowserCocoa(): bool
	{
		if (stripos($this->_agent, 'CocoaRestClient') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'CocoaRestClient'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
			} else {
				$this->setVersion(self::VERSION_UNKNOWN);
			}
			$this->setBrowser(self::BROWSER_COCOA);
			return true;
		}
		return false;
	}

	protected function checkFacebookExternalHit(): bool
	{
		if (stristr($this->_agent, 'FacebookExternalHit')) {
			$this->setRobot(true);
			$this->setFacebook(true);
			return true;
		}
		return false;
	}
	
	protected function checkForFacebookIos(): bool
	{
		if (stristr($this->_agent, 'FBIOS')) {
			$this->setFacebook(true);
			return true;
		}
		return false;
	}

	protected function getSafariVersionOnIos(): bool
	{
		$aresult = explode('/', (string) stristr($this->_agent, 'Version'));
		if (isset($aresult[1])) {
			$aversion = explode(' ', $aresult[1]);
			$this->setVersion($aversion[0]);
			return true;
		}
		return false;
	}

	protected function getChromeVersionOnIos(): bool
	{
		$aresult = explode('/', (string) stristr($this->_agent, 'CriOS'));
		if (isset($aresult[1])) {
			$aversion = explode(' ', $aresult[1]);
			$this->setVersion($aversion[0]);
			$this->setBrowser(self::BROWSER_CHROME);
			return true;
		}
		return false;
	}

	protected function checkBrowseriPhone(): bool
	{
		if (stripos($this->_agent, 'iPhone') !== false) {
			$this->setVersion(self::VERSION_UNKNOWN);
			$this->setBrowser(self::BROWSER_IPHONE);
			$this->getSafariVersionOnIos();
			$this->getChromeVersionOnIos();
			$this->checkForFacebookIos();
			$this->setMobile(true);
			return true;

		}
		return false;
	}

	protected function checkBrowseriPad(): bool
	{
		if (stripos($this->_agent, 'iPad') !== false) {
			$this->setVersion(self::VERSION_UNKNOWN);
			$this->setBrowser(self::BROWSER_IPAD);
			$this->getSafariVersionOnIos();
			$this->getChromeVersionOnIos();
			$this->checkForFacebookIos();
			$this->setTablet(true);
			return true;
		}
		return false;
	}

	protected function checkBrowseriPod(): bool
	{
		if (stripos($this->_agent, 'iPod') !== false) {
			$this->setVersion(self::VERSION_UNKNOWN);
			$this->setBrowser(self::BROWSER_IPOD);
			$this->getSafariVersionOnIos();
			$this->getChromeVersionOnIos();
			$this->checkForFacebookIos();
			$this->setMobile(true);
			return true;
		}
		return false;
	}

	protected function checkBrowserAndroid(): bool
	{
		if (stripos($this->_agent, 'Android') !== false) {
			$aresult = explode(' ', (string) stristr($this->_agent, 'Android'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
			} else {
				$this->setVersion(self::VERSION_UNKNOWN);
			}
			if (stripos($this->_agent, 'Mobile') !== false) {
				$this->setMobile(true);
			} else {
				$this->setTablet(true);
			}
			$this->setBrowser(self::BROWSER_ANDROID);
			return true;
		}
		return false;
	}

	protected function checkBrowserVivaldi(): bool
	{
		if (stripos($this->_agent, 'Vivaldi') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'Vivaldi'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_VIVALDI);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserYandex(): bool
	{
		if (stripos($this->_agent, 'YaBrowser') !== false) {
			$aresult = explode('/', (string) stristr($this->_agent, 'YaBrowser'));
			if (isset($aresult[1])) {
				$aversion = explode(' ', $aresult[1]);
				$this->setVersion($aversion[0]);
				$this->setBrowser(self::BROWSER_YANDEX);

				if (stripos($this->_agent, 'iPad') !== false) {
					$this->setTablet(true);
				} elseif (stripos($this->_agent, 'Mobile') !== false) {
					$this->setMobile(true);
				} elseif (stripos($this->_agent, 'Android') !== false) {
					$this->setTablet(true);
				}

				return true;
			}
		}

		return false;
	}

	protected function checkBrowserPlayStation(): bool
	{
		if (stripos($this->_agent, 'PlayStation ') !== false) {
			$aresult = explode(' ', (string) stristr($this->_agent, 'PlayStation '));
			$this->setBrowser(self::BROWSER_PLAYSTATION);
			if (isset($aresult[0])) {
				$aversion = explode(')', $aresult[2]);
				$this->setVersion($aversion[0]);
				if (stripos($this->_agent, 'Portable)') !== false || stripos($this->_agent, 'Vita') !== false) {
					$this->setMobile(true);
				}
				return true;
			}
		}
		return false;
	}

	protected function checkPlatform(): void
	{
		if (stripos($this->_agent, 'windows') !== false) {
			$this->_platform = self::PLATFORM_WINDOWS;
		} else if (stripos($this->_agent, 'iPad') !== false) {
			$this->_platform = self::PLATFORM_IPAD;
		} else if (stripos($this->_agent, 'iPod') !== false) {
			$this->_platform = self::PLATFORM_IPOD;
		} else if (stripos($this->_agent, 'iPhone') !== false) {
			$this->_platform = self::PLATFORM_IPHONE;
		} elseif (stripos($this->_agent, 'mac') !== false) {
			$this->_platform = self::PLATFORM_APPLE;
		} elseif (stripos($this->_agent, 'android') !== false) {
			$this->_platform = self::PLATFORM_ANDROID;
		} elseif (stripos($this->_agent, 'Silk') !== false) {
			$this->_platform = self::PLATFORM_FIRE_OS;
		} elseif (stripos($this->_agent, 'linux') !== false && stripos($this->_agent, 'SMART-TV') !== false) {
			$this->_platform = self::PLATFORM_LINUX . '/' . self::PLATFORM_SMART_TV;
		} elseif (stripos($this->_agent, 'linux') !== false) {
			$this->_platform = self::PLATFORM_LINUX;
		} else if (stripos($this->_agent, 'Nokia') !== false) {
			$this->_platform = self::PLATFORM_NOKIA;
		} else if (stripos($this->_agent, 'BlackBerry') !== false) {
			$this->_platform = self::PLATFORM_BLACKBERRY;
		} elseif (stripos($this->_agent, 'FreeBSD') !== false) {
			$this->_platform = self::PLATFORM_FREEBSD;
		} elseif (stripos($this->_agent, 'OpenBSD') !== false) {
			$this->_platform = self::PLATFORM_OPENBSD;
		} elseif (stripos($this->_agent, 'NetBSD') !== false) {
			$this->_platform = self::PLATFORM_NETBSD;
		} elseif (stripos($this->_agent, 'OpenSolaris') !== false) {
			$this->_platform = self::PLATFORM_OPENSOLARIS;
		} elseif (stripos($this->_agent, 'SunOS') !== false) {
			$this->_platform = self::PLATFORM_SUNOS;
		} elseif (stripos($this->_agent, 'OS\/2') !== false) {
			$this->_platform = self::PLATFORM_OS2;
		} elseif (stripos($this->_agent, 'BeOS') !== false) {
			$this->_platform = self::PLATFORM_BEOS;
		} elseif (stripos($this->_agent, 'win') !== false) {
			$this->_platform = self::PLATFORM_WINDOWS;
		} elseif (stripos($this->_agent, 'Playstation') !== false) {
			$this->_platform = self::PLATFORM_PLAYSTATION;
		} elseif (stripos($this->_agent, 'Roku') !== false) {
			$this->_platform = self::PLATFORM_ROKU;
		} elseif (stripos($this->_agent, 'iOS') !== false) {
			$this->_platform = self::PLATFORM_IPHONE . '/' . self::PLATFORM_IPAD;
		} elseif (stripos($this->_agent, 'tvOS') !== false) {
			$this->_platform = self::PLATFORM_APPLE_TV;
		} elseif (stripos($this->_agent, 'curl') !== false) {
			$this->_platform = self::PLATFORM_TERMINAL;
		} elseif (stripos($this->_agent, 'CrOS') !== false) {
			$this->_platform = self::PLATFORM_CHROME_OS;
		} elseif (stripos($this->_agent, 'okhttp') !== false) {
			$this->_platform = self::PLATFORM_JAVA_ANDROID;
		} elseif (stripos($this->_agent, 'PostmanRuntime') !== false) {
			$this->_platform = self::PLATFORM_POSTMAN;
		} elseif (stripos($this->_agent, 'Iframely') !== false) {
			$this->_platform = self::PLATFORM_I_FRAME;
		}
	}
}